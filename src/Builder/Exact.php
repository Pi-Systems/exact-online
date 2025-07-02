<?php

namespace PISystems\ExactOnline\Builder;

use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\ExactConnectionManager;
use PISystems\ExactOnline\Exceptions\ExactResponseError;
use PISystems\ExactOnline\Exceptions\MethodNotSupported;
use PISystems\ExactOnline\Model\DataSource;
use PISystems\ExactOnline\Model\ExactEnvironment;
use PISystems\ExactOnline\Model\FilterInterface;
use PISystems\ExactOnline\Model\SelectionInterface;
use PISystems\ExactOnline\Polyfill\JsonDataStream;

/**
 * Feel free to extend this class to add your own helpers.
 * But know the methods in ExactEnvironment are final.
 */
class Exact extends ExactEnvironment
{
    /**
     * Alias to getAdministration, as exact only uses 'division'.
     * Which, imo, is a dumb way to describe it.
     * @return int
     */
    public function getDivision() : int
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
     * @param array|string|SelectionInterface|null $selection
     * @return \Generator<T>
     * @template T
     */
    public function matching(
        string $class,
        null|string|FilterInterface $filter = null,
        null|array|string|SelectionInterface $selection = null,
    ): \Generator
    {

        if (!is_a($class, DataSource::class, true)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" is not a valid DataSource', $class));
        }

        $meta = $class::meta();

        if ($filter instanceof FilterInterface) {
            $filter = $filter->getFilter($class);
        }

        if (!$meta->supports(HttpMethod::GET)) {
            throw new MethodNotSupported($this, $class, HttpMethod::GET);
        }


        /** @var DataSource  $class */

        $uri = $this->manager->uriFactory->createUri(sprintf(
            "%s://%s%s",
            ExactConnectionManager::CONN_API_PROTOCOL,
            ExactConnectionManager::CONN_API_DOMAIN,
            str_replace('{division}', $this->getDivision(),  $meta->endpoint)
        ));

        $query = [];
        if ($filter) {
            if ($filter instanceof FilterInterface) {
                $filter = $filter->getFilter($class);
            }
            $query[] = '$filter='.$filter;

        }

        if ($selection) {
            if (is_array($selection)) {
                $selection = implode(',', $selection);
            }

            if ($selection instanceof SelectionInterface) {
                $selection = $selection->getSelection($class);
            }
            $query[] = '$select='.$selection;
        }
        if (empty($selection)) {
            $query[] = '$top=1';
        }

        if (!empty($query)) {
            $uri = $uri->withQuery(implode('&', $query));
        }

        $request = $this->createRequest($uri);
        do {
            $response = $this->sendAuthenticatedRequest($request);

            if ($response->getStatusCode() !== 200) {
                throw new ExactResponseError('Unable to retrieve (any) data from Exact', $request, $response);
            }

            var_dump($response->getBody()->getContents());
            $data = $this->decodeJsonRequestResponse($request, $response);


            $next = $data['__next'] ?? null;

            foreach ($data as $item) {
                yield $meta->hydrate($item);
            }

            if ($next) {
                $uri = $this->manager->uriFactory->createUri($next);
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
        $meta = $object::meta();
        if (!$meta->supports(HttpMethod::POST)) {
            throw new MethodNotSupported($this, $object::class, HttpMethod::POST);
        }

        $data = $meta->deflate($object, HttpMethod::POST);
        $uri = $this->manager->uriFactory->createUri($meta->endpoint);
        $request = $this->createRequest($uri, 'POST', new JsonDataStream($data));
        $response = $this->sendAuthenticatedRequest($request);
        $data = $this->decodeJsonRequestResponse($request, $response);

        if ($response->getStatusCode() !== 201) {
            throw new ExactResponseError('Unable to create object in Exact', $request, $response);
        }

        $data = $data['d'];

        $class = $object::class;
        return $meta->hydrate($data);
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
        $meta = $object::meta();
        if (!$meta->supports(HttpMethod::PUT)) {
            throw new MethodNotSupported($this, $object::class, HttpMethod::PUT);
        }

        $key = $meta->getPrimaryKeyValue($object);

        if (!$key) {
            throw new \LogicException('Unable to delete object, no primary key found.');
        }

        $data = $meta->deflate($object, HttpMethod::PUT);
        $uri = $this->manager->uriFactory->createUri(sprintf(
            "%s://%s%s(guid'%s')",
            ExactConnectionManager::CONN_API_PROTOCOL,
            ExactConnectionManager::CONN_API_DOMAIN,
            $meta->endpoint,
            $key
        ));
        $request = $this->createRequest($uri, 'POST', new JsonDataStream($data));
        $response = $this->sendAuthenticatedRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new ExactResponseError('Unable to update object in Exact', $request, $response);
        }
        $data = $this->decodeJsonRequestResponse($request, $response);

        if ($data['d']) {
            $meta->hydrate($data['d'], $object);
        }
        return $object;
    }

    public function delete(DataSource $object): bool
    {
        $meta = $object::meta();
        if (!$meta->supports(HttpMethod::DELETE)) {
            throw new MethodNotSupported($this, $object::class, HttpMethod::DELETE);
        }

        $key = $meta->getPrimaryKeyValue($object);

        if (!$key) {
            throw new \LogicException('Unable to delete object, no primary key found.');
        }

        $uri = $this->manager->uriFactory->createUri(sprintf(
            "%s://%s%s(guid'%s')",
            ExactConnectionManager::CONN_API_PROTOCOL,
            ExactConnectionManager::CONN_API_DOMAIN,
            $meta->endpoint,
            $key
        ));

        $request = $this->createRequest($uri, 'DELETE');
        return in_array(
            $this->sendAuthenticatedRequest($request)->getStatusCode(),
            // Intentionally catching 410, we want it deleted, the fact it already was is just w/e at this point.
            [200, 204, 410]
        );
    }
}
