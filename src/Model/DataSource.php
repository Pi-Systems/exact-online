<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Model\Traits\ExactEntityMetaDataTrait;
use PISystems\ExactOnline\Model\Traits\PublicPropertySerializeTrait;

abstract class DataSource implements \Serializable, \JsonSerializable
{
    use PublicPropertySerializeTrait,
        ExactEntityMetaDataTrait;
}