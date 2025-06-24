<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\Integer;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class Byte extends Integer
{
    public static function getBitCount(): int
    {
        return 1;
    }


    public static function getEdmType(): string
    {
        return 'Edm.Byte';
    }

    public static function getLocalType(): string
    {
        return 'int';
    }


    public static function description(): ?string
    {
        return 'A single byte of data.';
    }

    public function validate(mixed $value): bool {
        return is_int($value) && $value >= 0 && $value <= 255;
    }
}