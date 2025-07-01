<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Builder\EdmRegistry;
use Psr\Cache\CacheItemPoolInterface;

class ExactMetaDataLoader
{
    private static array $objectAttributeCache = [];
    public static ?CacheItemPoolInterface $cache = null;

    final static public function meta(DataSource|string $source) : DataSourceMeta
    {
        if (!self::$cache instanceof CacheItemPoolInterface) {
            trigger_error("No cache attached to ExactMetaDataFactory, this will severely impact performance.", E_USER_NOTICE);
        }

        if ($source instanceof DataSource) {
            $source = $source::class;
        }

        return self::$objectAttributeCache[$source] ??= (function() use ($source) {
            if (self::$cache instanceof CacheItemPoolInterface) {
                $item = self::$cache->getItem($source . '::datasourcemeta');
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
            }
            return DataSourceMeta::createFromClass($source);
        })();
    }
}
