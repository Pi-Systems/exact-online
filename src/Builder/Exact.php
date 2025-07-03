<?php

namespace PISystems\ExactOnline\Builder;

use GuzzleHttp\Psr7\Uri;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\ExactConnectionManager;
use PISystems\ExactOnline\Exceptions\ExactResponseError;
use PISystems\ExactOnline\Exceptions\MethodNotSupported;
use PISystems\ExactOnline\Model\DataSource;
use PISystems\ExactOnline\Model\DataSourceMeta;
use PISystems\ExactOnline\Model\ExactEnvironment;
use PISystems\ExactOnline\Model\ExactMetaDataLoader;
use PISystems\ExactOnline\Model\Expr\Criteria;
use PISystems\ExactOnline\Model\Expr\ExactVisitor;
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

    public function criteriaToUri(
        DataSourceMeta $meta,
        Criteria $criteria,
    ) : Uri
    {
        $visitor = new ExactVisitor();

        if (!$meta->supports(HttpMethod::GET)) {
            throw new MethodNotSupported($this, $meta->name, HttpMethod::GET);
        }

        $uri = $this->manager->uriFactory->createUri(sprintf(
            "%s://%s%s",
            ExactConnectionManager::CONN_API_PROTOCOL,
            ExactConnectionManager::CONN_API_DOMAIN,
            str_replace('{division}', $this->getDivision(),  $meta->endpoint)
        ));



        $filter = $visitor->dispatch($criteria->getWhereExpression());
        if (!empty($filter)) {
            $query = ['$filter=' . $filter];
        }

        if (!empty($filter) && !empty($criteria->expansion)) {
            throw new \LogicException(
                "Cannot use a \$filter expression while also trying to expand a selection."
            );
        }

        $selection = $criteria->selection;
        if (!empty($selection)) {
            $selection = implode(',', $selection);
            $query[] = '$select='.$selection;
        } else {
            $query[] = '$top=1';
        }

        $expand = $criteria->expansion;
        if (!empty($expand)) {
            $expand = implode(',', $expand);
            $query[] = '$expand='.$expand;
        }

        $orderings = $criteria->orderings();
        if (!empty($orderings)) {
            if (count($orderings) > 1) {
                throw new \RuntimeException(
                    "Multiple orderings are only supported on oData4+",
                );
            }
            $query[] = sprintf('$orderby=%s %s', $orderings[0][0], $orderings[0][1]);
        }

        if ($criteria->inlineCount) {
            $query[] = '$inlineCount=allpages';
        }

        if ($criteria->skipToken && $criteria->allowSkipVariable && $criteria->getFirstResult()) {
            throw new \LogicException(
            // How would this even work?
            // Do you skip the amount first, then skip to token possibly missing?
            // Or do you skip to token, then offset the amount, making it volatile?
                "Setting both 'skipToken' and 'firstResult' is not supported."
            );
        }

        if ($criteria->skipToken) {
            $query[] = '$skipToken='.$criteria->skipToken;
        }

        if ($criteria->allowSkipVariable && $criteria->getFirstResult()) {
            $query[] = '$skip='.$criteria->getFirstResult();
        }


        if (!empty($query)) {
            $uri = $uri->withQuery(implode('&', $query));
        }

        return $uri;
    }

    /**
     * @psalm-template T $entry
     * @param DataSource|DataSourceMeta|string $source
     * @param Criteria $criteria
     * @return \Generator<T>
     * @template T
     */
    public function matching(
        DataSource|DataSourceMeta|string $source,
        Criteria                         $criteria,
    ): \Generator
    {
        $meta = ExactMetaDataLoader::meta($source);

        $uri = $this->criteriaToUri($meta, $criteria);
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

    public function count(
        DataSource|DataSourceMeta|string $source
    ) : int
    {
        $meta = ExactMetaDataLoader::meta($source);

        $uri = $this->criteriaToUri($meta, new Criteria());
        // :/ And people praise odata? What a load of...
        $uri = $uri->withPath($uri->getPath() . '$count');

        $request = $this
            ->createRequest($uri)
            // Silly enough, we do not want to send a Accept header (Even though it should be text/plain)
            ->withoutHeader('Accept');
        $response = $this->sendAuthenticatedRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new ExactResponseError('Unable to retrieve (any) data from Exact', $request, $response);
        }

        return (int)$response->getBody()->getContents();
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
