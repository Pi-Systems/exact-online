<?php

namespace PISystems\ExactOnline\Util;

use PISystems\ExactOnline\Builder\Compiler;
use PISystems\ExactOnline\Model\DataSource;
use PISystems\ExactOnline\Model\DataSourceMeta;

class MetaDataLoader
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

        self::$metaCache ??= json_decode(file_get_contents(Compiler::EXACT_META_CACHE), true);

        if (self::$metaCache && array_key_exists($source, self::$metaCache)) {
            $data = self::$metaCache[$source];

            if ($data instanceof DataSourceMeta) {
                return $data;
            }
            return self::$objectMetaCache[$source] ??= DataSourceMeta::createFromArray($data);
        }

        return self::$metaCache[$source] ??= DataSourceMeta::createFromClass($source);
    }


}