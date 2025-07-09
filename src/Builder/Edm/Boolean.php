<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\EdmDataStructure;
use PISystems\ExactOnline\Model\FilterEncodableDataStructure;
use PISystems\ExactOnline\Model\TypedValue;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class Boolean extends EdmDataStructure implements FilterEncodableDataStructure
{

    public static function getEdmType(): string
    {
        return 'Edm.Boolean';
    }

    public static function getLocalType(): string
    {
        return 'bool';
    }


    public static function description(): ?string
    {
        return '';
    }

    function validate(mixed $value): bool
    {
        return is_bool($value);
    }

    public function encodeForFilter(mixed $value): TypedValue
    {
        return new TypedValue(null, $value ? '1' : '0');
    }
}