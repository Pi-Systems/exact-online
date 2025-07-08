<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\EdmEncodableDataStructure;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class Binary extends EdmEncodableDataStructure
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

    function encode(mixed $value): bool|string|int|float|null
    {
        if (null === $value) {
            return null;
        }

        return 'X\''.pack('H*', $value).'\'';
    }

    public function decode(mixed $value): mixed
    {
        $result = unpack('H*', substr($value, 2, -1));

        if ($result === false) {
            return null;
        }

        return $result[1];

    }
}
