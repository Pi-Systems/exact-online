<?php

namespace PISystems\ExactOnline\Builder;

use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\ExactConnectionFactory;
use PISystems\ExactOnline\Exceptions\ExactResponseError;
use PISystems\ExactOnline\Exceptions\MethodNotSupported;
use PISystems\ExactOnline\Model\DataSource;
use PISystems\ExactOnline\Model\EdmDataStructure;
use PISystems\ExactOnline\Model\EdmEncodableDataStructure;
use PISystems\ExactOnline\Model\ExactEnvironment;
use PISystems\ExactOnline\Model\FilterInterface;
use PISystems\ExactOnline\Polyfill\JsonDataStream;
use Psr\Http\Message\UriInterface;

/**
 * Feel free to extend this class to add your own helpers.
 * But know the methods in ExactEnvironment are final.
 */
class Exact extends ExactEnvironment
{
    const int PAGE_SIZE_DEFAULT = 60;
    const int PAGE_SIZE_SYNC_AND_BULK = 1000;

    private ?UriInterface $tokenUri = null;

    public function generateTokenAccessUrl(): UriInterface
    {
        return $this->tokenUri ??= $this->uriFactory->createUri(sprintf(
            "%s://%s%s",
            ExactConnectionFactory::CONN_API_PROTOCOL,
            ExactConnectionFactory::CONN_API_DOMAIN,
            ExactConnectionFactory::CONN_API_TOKEN_PATH
        ));
    }

    /**
     * Simple alias to loadAdministrationData
     *
     * @return int
     */
    public function getAdministration() : int
    {
        return $this->loadAdministrationData();
    }

    /**
     * Only finding by ID is currently supported.
     * For anything more complex, please use the matching() method.
     *
     * @psalm-template T $entry
     * @param string $class
     * @param string $id
     * @return T
     * @template T
     */
    public function find(
        string $class,
        string $id,
    ) : DataSource
    {

        $generator = $this->matching($class, sprintf('ID eq guid\'%s\'', $id));
        return $generator->current();
    }

    /**
     * @psalm-template T $entry
     * @param string $class
     * @param string|FilterInterface|null $filter
     * @return \Generator<T>
     * @template T
     */
    public function matching(
        string $class,
        null|string|FilterInterface $filter = null,
    ): \Generator
    {
        if (!is_a($class, DataSource::class, true)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" is not a valid DataSource', $class));
        }

        if ($filter instanceof FilterInterface) {
            $filter = $filter->getFilter($class);
        }

        /** @var array $methods */
        $methods = constant($class.'::METHODS');
        if (!in_array(HttpMethod::GET, $methods)) {
            throw new MethodNotSupported($this, $class, HttpMethod::GET);
        }


        /** @var DataSource  $class */

        $uri = $this->uriFactory->createUri(sprintf(
            "%s://%s%s",
            ExactConnectionFactory::CONN_API_PROTOCOL,
            ExactConnectionFactory::CONN_API_DOMAIN,
            $class::ENDPOINT
        ));

        if ($filter) {
            $uri = $uri->withQuery(http_build_query(['$filter' => $filter]));
        }

        $request = $this->createRequest($uri);
        do {
            $response = $this->sendAuthenticatedRequest($request);

            if ($response->getStatusCode() !== 200) {
                throw new ExactResponseError('Unable to retrieve (any) data from Exact', $request, $response);
            }

            $data = $this->decodeJsonRequestResponse($request, $response);

            if (!$data) {
                throw new ExactResponseError('Unable to retrieve data from Exact reply.', $request, $response);
            }

            // The data structure is a bit annoying.
            /* {
             *      d {
             *          ?__next: string,
             *          (
             *              ?results: [...entries] |
             *              ...entry
             *          )
             *
             *      }
             */

            if (isset($data['d'])) {
                $data = $data['d'];
            } else {
                throw new ExactResponseError('Malformed reply from Exact, expecting { d: ... }', $request, $response);
            }

            if (!isset($data['results'])) {
                $data = [$data];
            }

            $next = $data['__next'] ?? null;

            foreach ($data as $item) {

                $entry = new $class();

                $this->hydrate($entry, $item);

                yield $entry;
            }

            if ($next) {
                $uri = $this->uriFactory->createUri($next);
            } else {
                $uri = null;
            }
        } while ($uri);

    }


    /**
     * Warning: Returns a NEW DataSource object upon success
     * @param DataSource $object
     * @return DataSource
     */
    public function create(DataSource $object): DataSource
    {
        if (!in_array(HttpMethod::POST, $object::METHODS)) {
            throw new MethodNotSupported($this, $object::class, HttpMethod::POST);
        }

        $data = $this->deflate($object);
        $uri = $this->uriFactory->createUri($object::ENDPOINT);
        $request = $this->createRequest($uri, 'POST', new JsonDataStream($data));
        $response = $this->sendAuthenticatedRequest($request);
        $data = $this->decodeJsonRequestResponse($request, $response);

        if ($response->getStatusCode() !== 201) {
            throw new ExactResponseError('Unable to create object in Exact', $request, $response);
        }

        $data = $data['d'];

        $class = $object::class;
        /** @var DataSource $entry */
        $entry = new $class();

        $this->hydrate($entry, $data);

        return $entry;
    }

    /**
     * Warning: Updates the PASSED object, this does NOT return a new object.
     *          Return value is the same as the one passed.
     *
     * @param DataSource $object
     * @return DataSource
     */
    public function update(DataSource $object): DataSource
    {
        if (!in_array(HttpMethod::PUT, $object::METHODS)) {
            throw new MethodNotSupported($this, $object::class, HttpMethod::PUT);
        }

        $key = $this->getPrimaryKey($object);

        if (!$key) {
            throw new \LogicException('Unable to delete object, no primary key found.');
        }

        $data = $this->deflate($object);
        $uri = $this->uriFactory->createUri(sprintf(
            "%s://%s%s(guid'%s')",
            ExactConnectionFactory::CONN_API_PROTOCOL,
            ExactConnectionFactory::CONN_API_DOMAIN,
            $object::ENDPOINT,
            $key
        ));
        $request = $this->createRequest($uri, 'POST', new JsonDataStream($data));
        $response = $this->sendAuthenticatedRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new ExactResponseError('Unable to update object in Exact', $request, $response);
        }
        $data = $this->decodeJsonRequestResponse($request, $response);

        if ($data['d']) {
            $this->hydrate($object, $data['d']);
        }
        return $object;
    }

    public function delete(DataSource $object): bool
    {
        if (!in_array(HttpMethod::DELETE, $object::METHODS)) {
            throw new MethodNotSupported($this, $object::class, HttpMethod::DELETE);
        }

        $key = $this->getPrimaryKey($object);

        if (!$key) {
            throw new \LogicException('Unable to delete object, no primary key found.');
        }

        $uri = $this->uriFactory->createUri(sprintf(
            "%s://%s%s(guid'%s')",
            ExactConnectionFactory::CONN_API_PROTOCOL,
            ExactConnectionFactory::CONN_API_DOMAIN,
            $object::ENDPOINT,
            $key
        ));

        $request = $this->createRequest($uri, 'DELETE');
        return in_array(
            $this->sendAuthenticatedRequest($request)->getStatusCode(),
            // Intentionally catching 410, we want it deleted, the fact it already was is just w/e at this point.
            [200, 204, 410]
        );
    }


    public function hydrate(
        DataSource $object,
        array      $data,
    ): void
    {
        foreach ($data as $key => $value) {

            if (!property_exists($object, $key)) {
                // Ignore non-existent / unknown entries (But still trigger E_NOTICE for dev env)
                trigger_error(sprintf('Unknown property "%s" on %s', $key, get_class($object)));
                continue;
            }
            // Get the type
            $reflectionProperty = new \ReflectionProperty($object, $key);
            $attributes = $reflectionProperty->getAttributes(EDMDataStructure::class, \ReflectionAttribute::IS_INSTANCEOF);

            if (empty($attributes)) {
                // Ignore any attributes we don't know; they could easily cause errors to trigger.
                // Update the source entity if these are needed.
                continue;
            }

            if (count($attributes) > 1) {
                throw new \LogicException('More than one EDMDataStructure attribute found on property ' . $key);
            }

            // Hydrate the entry
            $attribute = $attributes[0]->newInstance();

            if ($attribute instanceof EdmEncodableDataStructure) {
                $value = $attribute->decode($value);
                $reflectionProperty->setValue($object, $value);
                continue;
            }

            // Setting it will just trigger auto-cast without issues.
            $object->{$key} = $value;
        }
    }

    public function deflate(DataSource $object): array
    {
        $reflection = new \ReflectionClass($object);
        $data = [];

        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(EDMDataStructure::class, \ReflectionAttribute::IS_INSTANCEOF);

            if (empty($attributes)) {
                continue;
            }
            $attribute = $attributes[0]->newInstance();
            if ($attribute instanceof EdmEncodableDataStructure) {
                $data[$property->getName()] = $attribute->encode($property->getValue($object));
                continue;
            }

            $data[$property->getName()] = $property->getValue($object);
        }
        return $data;
    }
}