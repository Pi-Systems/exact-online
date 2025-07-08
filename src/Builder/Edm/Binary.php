<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\EdmDataStructure;
use PISystems\ExactOnline\Model\FilterEncodableDataStructure;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class Binary extends EdmDataStructure implements FilterEncodableDataStructure
{
    public static function getEdmType(): string
    {
        return 'Edm.Binary';
    }

    public static function getLocalType(): string
    {
        return 'string';
    }


    public static function description(): ?string
    {
        return 'Binary data, warning, do not pre-encode this data.';
    }

    public function validate(mixed $value): bool {
        return is_scalar($value);
    }

    function encodeForFilter(mixed $value): bool|string|int|float|null
    {
        if (null === $value) {
            return null;
        }

        return 'X\''.pack('H*', $value).'\'';
    }
}
