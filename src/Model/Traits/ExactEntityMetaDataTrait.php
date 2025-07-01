<?php

namespace PISystems\ExactOnline\Model\Traits;

use PISystems\ExactOnline\Model\DataSourceMeta;
use PISystems\ExactOnline\Model\ExactMetaDataLoader;

trait ExactEntityMetaDataTrait
{
    public static function meta() : DataSourceMeta
    {
        return ExactMetaDataLoader::meta(static::class);
    }
}
