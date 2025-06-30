<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Model\Traits\ExactEntityMetaDataTrait;
use PISystems\ExactOnline\Model\Traits\PublicPropertySerializeTrait;

class DataSource implements \Serializable, \JsonSerializable
{
    use PublicPropertySerializeTrait,
        ExactEntityMetaDataTrait;
}
