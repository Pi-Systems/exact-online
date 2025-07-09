<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\EdmDataStructure;
use PISystems\ExactOnline\Model\FilterEncodableDataStructure;
use PISystems\ExactOnline\Model\TypedValue;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class Time extends EdmDataStructure implements FilterEncodableDataStructure
{

    public static function getEdmType(): string
    {
        return 'Edm.Time';
    }

    public static function description(): ?string
    {
        return '';
    }

    public static function getLocalType(): string
    {
        return 'int|float|\DateInterval';
    }

    function validate(mixed $value): bool
    {
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

    function encodeForFilter(mixed $value): ?TypedValue
    {
        if (null === $value) {
            return null;
        }

        if (is_int($value) || is_float($value)) {
            $value = abs($value);
            $hours = floor($value / 3600);
            $minutes = floor($value / 60 % 60);
            $seconds = floor($value % 60);
            $value = new \DateInterval("PT{$hours}H{$minutes}M{$seconds}S");
        }

        if (!($value instanceof \DateInterval)) {
            throw new \InvalidArgumentException('Time segment input not supported.');
        }

        return new TypedValue('time', $value->format('PT%hH%iM%sS'));
    }
}