<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Builder\Edm\Collection;
use PISystems\ExactOnline\Builder\Edm\ExactWeb;
use PISystems\ExactOnline\Builder\Edm\Required;
use PISystems\ExactOnline\Builder\EdmRegistry;
use PISystems\ExactOnline\Builder\ExactDocsReader;

class ExactMetaDataLoader
{
    private static ?array $metaCache = null;
    private static array $objectMetaCache = [];

    final static public function meta(DataSourceMeta|DataSource|string $source): DataSourceMeta
    {
        // :/
        if ($source instanceof DataSourceMeta) {
            return $source;
        }

        if ($source instanceof DataSource) {
            $source = $source::class;
        }

        self::$metaCache ??= json_decode(file_get_contents(ExactDocsReader::EXACT_META_CACHE), true);

        if (self::$metaCache && array_key_exists($source, self::$metaCache)) {
            return self::$objectMetaCache[$source] ??=
                unserialize(self::$metaCache[$source], ['allowed_classes' => [
                    DataSourceMeta::class,
                    Required::class,
                    Collection::class,
                    ExactWeb::class,
                    ... EdmRegistry::EDM_CLASSES
                ]]);
        }

        return self::$metaCache[$source] ??= DataSourceMeta::createFromClass($source);
    }


}
