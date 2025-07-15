<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\EdmDataStructure;
use PISystems\ExactOnline\Model\FilterEncodableDataStructure;
use PISystems\ExactOnline\Model\TypedValue;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class UTF8String extends EdmDataStructure implements FilterEncodableDataStructure
{

    public static function getEdmType(): string
    {
        return 'Edm.String';
    }

    public static function description(): ?string
    {
        return '';
    }

    public static function getLocalType(): string
    {
        return 'string';
    }

    function validate(mixed $value): bool
    {
        return is_string($value);
    }

    public function encode(mixed $value): string
    {
        return mb_convert_encoding($value, 'UTF-8', mb_detect_encoding($value));
    }


    public function encodeForFilter(mixed $value): ?TypedValue
    {
        return new TypedValue('', $this->encode($value));
    }
}