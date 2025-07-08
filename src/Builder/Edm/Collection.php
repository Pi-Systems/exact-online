<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\DataSource;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class Collection
{
    public function __construct(
        public string $target,
        public string $globalName
    )
    {

    }
}
