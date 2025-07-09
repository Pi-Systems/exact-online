<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\EdmDataStructure;
use PISystems\ExactOnline\Model\FilterEncodableDataStructure;
use PISystems\ExactOnline\Model\TypedValue;

// This is not a real EDM Type
// This is the logical result of Accounts::$Code description statement:
//  `- Unique key, fixed length numeric string with leading spaces, length 18.
//   - IMPORTANT: When you use OData $filter on this field you have to make sure the filter parameter contains the leading spaces
#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class UTF8CodeString extends EdmDataStructure implements FilterEncodableDataStructure
{
    public function __construct(public readonly int $length = 18)
    {

    }

    public static function getEdmType(): string
    {
        return 'Edm.CodeString';
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

    public function encodeForFilter(mixed $value): ?TypedValue
    {
        if (null === $value) {
            return null;
        }

        return new TypedValue(null, sprintf('%' . $this->length . '.' . $this->length . 's',
            ltrim(mb_convert_encoding($value, 'UTF-8', mb_detect_encoding($value))) // trim before sprintf, weird stuff happens if we you don't.
        ));
    }
}