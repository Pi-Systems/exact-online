<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\Integer;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class SByte extends Integer
{
    public static function getBitCount(): int
    {
        return 4;
    }

    public static function getEdmType(): string
    {
        return 'Edm.SByte';
    }


}