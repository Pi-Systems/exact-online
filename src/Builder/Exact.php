<?php

namespace PISystems\ExactOnline\Builder;

use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Exceptions\ExactResponseError;
use PISystems\ExactOnline\Exceptions\MethodNotSupported;
use PISystems\ExactOnline\Model\DataSource;
use PISystems\ExactOnline\Model\DataSourceMeta;
use PISystems\ExactOnline\Model\ExactEnvironment;
use PISystems\ExactOnline\Model\ExactMetaDataLoader;
use PISystems\ExactOnline\Model\Expr\Criteria;
use PISystems\ExactOnline\Polyfill\JsonDataStream;
use Psr\Cache\InvalidArgumentException;

/**
 * Feel free to extend this class to add your own helpers.
 * But know the methods in ExactEnvironment are final.
 */
class Exact extends ExactEnvironment
{
    /**
     * Alias to loadAdministrationData, as exact only uses 'division'.
     * Which, imo, is a dumb way to describe it.
     * @param bool $cache
     * @return int
     */
    public function getDivision(bool $cache = true): int
    {
        return $this->loadAdministrationData($cache);
    }

    /**
     * Only finding by keyColumn is currently supported.
     * For anything more complex, please use the matching() method.
     *
     * @psalm-template T $entry
     * @param DataSource|DataSourceMeta|string $source
     * @param string $id
     * @param Criteria|null $criteria
     * @param bool $cached
     * @return T|null
     * @template T
     */
    public function find(
        DataSource|DataSourceMeta|string $source,
        string                           $id,
        ?Criteria                        $criteria = null,
        bool                             $cached = true
    ): ?DataSource
    {
        $meta = ExactMetaDataLoader::meta($source);
        $generator = $this->matching(
            $meta,
            ($criteria ?? Criteria::create())->andWhere(
                Criteria::expr()->eq($meta->keyColumn, $id)
            ),
            $cached
        );
        return $generator->current() ?: null;
    }

    /**
     * Short-cut to find(...,Criteria...->select('ID'), ...) ? true : false
     *
     * @param DataSource|DataSourceMeta|string $source
     * @param string $id
     * @param Criteria|null $criteria
     * @param bool $cached
     * @return bool
     */
    public function exists(
        DataSource|DataSourceMeta|string $source,
        string                           $id,
        ?Criteria                        $criteria = null,
        bool                             $cached = true
    ): bool
    {
        $source = ExactMetaDataLoader::meta($source);

        $criteria ??= Criteria::create();
        $criteria->select([$source->keyColumn]);
        return null !== $this->find($source, $id, $criteria, $cached);
    }

    /**
     * @psalm-template T $entry
     * @param DataSource|DataSourceMeta|string $source
     * @param string|Criteria|null $criteria If criteria is a string, it will be treated as THE ENTIRE QUERY PARAM (Aka: Raw mode)
     * @param bool $cache Note: Cache is on the data layer, hydration is still performed normally.
     *                     While less performant, this does allow library updates without destroying existing caches.
     *
     *
     * @return \Generator<T>
     * @template T
     */
    public function matching(
        DataSource|DataSourceMeta|string $source,
        null|string|Criteria $criteria = null,
        bool                 $cache = true
    ): \Generator
    {
        $source = ExactMetaDataLoader::meta($source);

        if ($criteria instanceof Criteria && !$criteria->isFrom($source)) {
            throw new \LogicException(
                "Not a valid criteria for {$source->name}, either use source-less criteria, or ensure the right meta is attached to the criterium."
            );
        }

        if ($criteria instanceof Criteria) {
            $uri = $this->criteriaToUri($source, $criteria);
        } else if (null === $criteria) {
            $uri = $this->criteriaToUri($source);
        } else {
            $uri = $this->getUri($source)->withQuery($criteria);
        }

        do {
            $cKey = 'matching::' . $this->language . '::' . $this->getDivision() . '::' . sha1($source->name . "::" . $uri);
            try {
                $cacheItem = $this->manager->cache->getItem($cKey);
            } catch (InvalidArgumentException $e) {
                // No idea why this exception is even a thing, throw the base \InvalidArgumentException ffs.
                // "We want to know where it came from!" ...
                // Then THROW THE BASE CACHE EXCEPTION FFS! Or call it Cache(Invalid)ArgumentException.
                throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
            }
            if ($cache && $cacheItem->isHit()) {
                $content = $cacheItem->get();
                $data = $this->getDataFromRawData($content);
            } else {
                $request = $this->createRequest($uri);
                $response = $this->sendAuthenticatedRequest($request);

                $content = $this->decodeResponseToJson($request, $response);
                $data = $this->getDataFromRawData($content);

                $cacheItem->set($content);
                $this->manager->cache->save($cacheItem);
            }

            $next = $content['d']['__next'] ?? null;

            foreach ($data as $item) {
                yield $source->hydrate($item);
            }

            if ($next) {
                $uri = $this->manager->uriFactory->createUri($next);
            } else {
                $uri = null;
            }
        } while ($uri);
    }

    /**
     * @template T
     * @psalm-param DataSource|class-string<T> $source
     * @psalm-return T|DataSource
     */
    public function findOneBy(
        DataSource|DataSourceMeta|string $source,
        array|Criteria                   $criteria,
        bool                             $orOperation = false,
        bool                             $cached = true
    )
    {
        $meta = ExactMetaDataLoader::meta($source);
        if (is_array($criteria)) {

            if (array_is_list($criteria)) {
                throw new \LogicException(
                    "Array must be an associative array."
                );
            }

            $crit = Criteria::create($meta);

            $x = [];
            foreach ($criteria as $key => $value) {
                $x[] = Criteria::expr()->eq($key, $value);
            }

            if ($orOperation) {
                $crit->where(Criteria::expr()->orX(...$x));
            } else {
                $crit->where(Criteria::expr()->andX(...$x));
            }
            $criteria = $crit;
            unset($crit);
        }

        $criteria->setMaxResults(1);

        if (!$criteria->isFrom($meta)) {
            throw new \LogicException(
                "Not a valid criteria for {$meta->name}, either use source-less criteria, or ensure the right meta is attached to the criterium."
            );
        }

        $return = null;
        foreach ($this->matching($meta, $criteria, $cached) as $result) {
            if ($return) {
                throw new \RuntimeException(
                    "Expected only one result to be returned from matching()"
                );
            }
            $return = $result;
        }

        return $return;
    }

    public function count(
        DataSource|DataSourceMeta|string $source
    ): int
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
     * @return string|null
     */
    public function create(DataSource $object): null|string
    {
        $meta = $object::meta();
        if (!$meta->supports(HttpMethod::CREATE)) {
            throw new MethodNotSupported($this, $object::class, HttpMethod::POST);
        }

        $data = $meta->deflate($object, HttpMethod::CREATE, skipNull: true);
        $uri = $this->getUri($meta);
        $request = $this->createRequest($uri, HttpMethod::CREATE, new JsonDataStream($data));
        $response = $this->sendAuthenticatedRequest($request);

        $content = $this->decodeResponseToJson($request, $response);
        $data = null;
        if (!empty($content)) {
            // Triggers error messages
            $data = $this->getDataFromRawData($content);
        }
        if ($response->getStatusCode() !== 201) {
            throw new ExactResponseError('Unable to create object in Exact', $request, $response);
        }

        if ($data === null) {
            return null;
        }

        return $data[$meta->keyColumn] ?? null;
    }

    /**
     * Warning: Updates the PASSED object, this does NOT return a new object.
     *          Return value is the same as the one passed.
     *
     * @param DataSource $object
     * @param array|null $fields Update only these fields (Warning: Not supported on every endpoint!)
     * @return bool
     */
    public function update(DataSource $object, ?array $fields = null): bool
    {
        $meta = $object::meta();
        if (!$meta->supports(HttpMethod::UPDATE)) {
            throw new MethodNotSupported($this, $object::class, HttpMethod::PUT);
        }

        $key = $meta->getPrimaryKeyValue($object);

        if (!$key) {
            throw new \LogicException('Unable to delete object, no primary key found.');
        }

        $data = $meta->deflate($object, HttpMethod::UPDATE, $fields);
        $uri = $this->getUri($meta);
        $uri = $uri->withPath(
            sprintf(
                "%s(guid'%s')",
                $uri->getPath(),
                $key
            )
        );

        $request = $this->createRequest($uri, HttpMethod::UPDATE, new JsonDataStream($data));
        $response = $this->sendAuthenticatedRequest($request);
        $code = $response->getStatusCode();

        return $code >= 200 && $code < 300;
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

        $uri = $this->getUri($meta);
        $uri = $uri->withPath(
            sprintf(
                "%s(guid'%s')",
                $uri->getPath(),
                $key
            )
        );

        $request = $this->createRequest($uri, 'DELETE');
        $response = $this->sendAuthenticatedRequest($request);
        $code = $response->getStatusCode();

        return in_array(
            $code,
            // Intentionally catching 410, we want it deleted, the fact it already was is just w/e at this point.
            [200, 204, 410]
        );
    }

}