<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\EdmDataStructure;
use PISystems\ExactOnline\Model\EdmEncodableDataStructure;
use PISystems\ExactOnline\Model\FilterEncodableDataStructure;
use PISystems\ExactOnline\Model\TypedValue;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class DateTime extends EdmDataStructure implements FilterEncodableDataStructure, EdmEncodableDataStructure
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

        return new TypedValue('datetime', \DateTimeImmutable::createFromInterface($value)->format(self::ODATA_DATE_FORMAT));
    }

    public function encode(mixed $value): array|bool|string|int|float|null
    {
        if (null === $value) {
            return null;
        }

        if (!($value instanceof \DateTimeInterface)) {
            throw new \InvalidArgumentException("Expect input type to be a DateTimeInterface or an object implementing \DateTimeInterface");
        }
        return $value->format(\DateTimeInterface::ATOM);
    }

    public function decode(float|array|bool|int|string|null $value): mixed
    {
        if (empty($value)) {
            return null;
        }

        // Seriously?! And o2 prides itself on being a standard... get real.
        // Just use null  & ATOM/ISO8601 ffs, like every other normal format on the planet.
        if (str_starts_with($value, '/Date(')) {
            $value = (int)substr($value, 6,-2);
            return \DateTimeImmutable::createFromTimestamp($value);
        }

        return new \DateTimeImmutable($value);
    }
}