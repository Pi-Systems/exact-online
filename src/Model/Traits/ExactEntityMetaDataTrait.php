<?php

namespace PISystems\ExactOnline\Model\Traits;

use PISystems\ExactOnline\Model\DataSourceMeta;
use PISystems\ExactOnline\Util\MetaDataLoader;

trait ExactEntityMetaDataTrait
{
    public static function meta() : DataSourceMeta
    {
        return MetaDataLoader::meta(static::class);
    }
}