<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Builder\EdmRegistry;
use Psr\Cache\CacheItemPoolInterface;

class ExactMetaDataFactory
{
    private static array $objectAttributeCache = [];
    public static CacheItemPoolInterface $cache;

    final static public function meta(DataSource|string $source) : DataSourceMeta
    {
        if (!self::$cache instanceof CacheItemPoolInterface) {
            throw new \RuntimeException("Cannot call meta() until a cache pool has been attached to the factory.");
        }

        if ($source instanceof DataSource) {
            $source = $source::class;
        }
        return self::$objectAttributeCache[$source] ??= (function() use ($source) {

            $item = self::$cache->getItem($source. '::datasourcemeta');
            if ($item->isHit()) {
                $s = $item->get();
                return unserialize($s, ['allowed_classes' => [
                    DataSourceMeta::class,
                    ... EdmRegistry::EDM_CLASSES
                ]]);
            }

            $meta = DataSourceMeta::createFromClass($source);
            $item->set(serialize($meta));
            $item->expiresAfter(60*60*24*7);
            self::$cache->save($item);
            return $meta;
        })();
    }
}
