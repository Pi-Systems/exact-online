<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\EdmDataStructure;
use PISystems\ExactOnline\Model\FilterEncodableDataStructure;
use PISystems\ExactOnline\Model\TypedValue;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class DateTimeOffset extends EdmDataStructure implements FilterEncodableDataStructure
{
    const string ODATA_DATE_FORMAT = \DateTimeInterface::ATOM;

    public static function getEdmType(): string
    {
        return 'Edm.DateTimeOffset';
    }

    public static function getLocalType(): string
    {
        return '\DateTimeInterface';
    }

    public static function description(): ?string
    {
        return '';
    }

    public function validate(mixed $value): bool
    {
        if (!($value instanceof \DateTimeInterface)) {
            if (is_string($value)) {
                return true;
            }
            throw new \InvalidArgumentException("Expect input type to be a ISO8601 string or an object implementing \DateTimeInterface. (Note: Validate does not validate the contents of the string itself)");
        }
        return true;
    }

    function encodeForFilter(mixed $value): ?TypedValue
    {
        if (null === $value) {
            return null;
        }

        if (!($value instanceof \DateTimeInterface)) {
            if (!is_string($value)) {
                throw new \InvalidArgumentException("Expect input type to be a ISO8601 string or an object implementing \DateTimeInterface");
            }
            $value = \DateTimeImmutable::createFromFormat(self::ODATA_DATE_FORMAT, $value);
        }
        if (false === $value) {
            return null;
        }

        if (!$value instanceof \DateTimeInterface) {
            throw new \InvalidArgumentException("Expect input type to be a DateTimeInterface or an object implementing \DateTimeInterface");
        }

        return new TypedValue('time', \DateTimeImmutable::createFromInterface($value)->format(self::ODATA_DATE_FORMAT));
    }
}