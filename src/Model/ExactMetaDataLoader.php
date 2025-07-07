<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Builder\ExactDocsReader;

class ExactMetaDataLoader
{
    private static ?array $metaCache = null;
    private static array $objectMetaCache = [];

    final static public function meta(DataSourceMeta|DataSource|string $source) : DataSourceMeta
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
            return self::$objectMetaCache[$source] ??= DataSourceMeta::createFromArray(self::$metaCache[$source]);
        }

        return self::$metaCache[$source] ??= DataSourceMeta::createFromClass($source);
    }


}
