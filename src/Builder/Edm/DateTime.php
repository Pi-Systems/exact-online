<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\EdmEncodableDataStructure;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class DateTime extends EdmEncodableDataStructure
{

    const string ODATA_DATE_FORMAT = 'Y-m-d\TH:i:s';

    public static function getEdmType(): string
    {
        return 'Edm.DateTime';
    }

    public static function description(): ?string
    {
        return '';
    }

    public static function getLocalType(): string
    {
        return '\DateTimeInterface';
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

    function encode(mixed $value): ?string
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

        return sprintf('datetime\'%s\'',\DateTimeImmutable::createFromInterface($value)->format(self::ODATA_DATE_FORMAT));
    }

    function decode(mixed $value): null|\DateTimeImmutable
    {
        if (!is_string($value)) {
            return null;
        }

        try {
            $value = \DateTimeImmutable::createFromFormat(
                self::ODATA_DATE_FORMAT,
                substr($value, 9, -1) // - datetime'...'
            );
        }   catch (\Exception) {
            return null;
        }

        return $value ?: null;
    }
}
