<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\EdmDataStructure;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class Time extends EdmDataStructure
{

    public static function getEdmType(): string
    {
        return 'Edm.Time';
    }

    public static function description(): ?string
    {
        return 'A time';
    }

    public static function getLocalType(): string
    {
        return 'int|float|\DateInterval';
    }

    function validate(mixed $value): bool
    {
        if ($value instanceof \DateTimeInterface) {
            return true;
        }

        if ($value instanceof \DateInterval) {
            if ($value->days<0) {
                return true;
            }
            throw new \InvalidArgumentException('Time segment out of bounds. (Only supports hour/minute/seconds)');
        }

        if (is_int($value) || is_float($value)) {
            if ($value >= 0 && $value < 86400) {
                return true;
            }
            throw new \InvalidArgumentException('Time segment out of bounds. (Only supports hour/minute/seconds)');
        }

        return false;
    }
}