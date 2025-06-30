<?php

namespace PISystems\ExactOnline\Model\Traits;

use PISystems\ExactOnline\Model\DataSourceMeta;
use PISystems\ExactOnline\Model\ExactMetaDataFactory;

trait ExactEntityMetaDataTrait
{
    public static function meta() : DataSourceMeta
    {
        return ExactMetaDataFactory::meta(static::class);
    }
}
