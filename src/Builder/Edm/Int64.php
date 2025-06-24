<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\Integer;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class Int64 extends Integer
{
    public static function getBitCount(): int
    {
        return 64;
    }

}