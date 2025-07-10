<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\EdmDataStructure;
use PISystems\ExactOnline\Model\EdmEncodableDataStructure;

/**
 * This is just a float internally with a hard upper and lower bound.
 */
#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class Decimal extends EdmDataStructure implements EdmEncodableDataStructure
{
    const float UPPER_BOUND = 10E255;
    const float LOWER_BOUND = -10E255;

    public function __construct(
        public int $precision = 10,
        public int $scale = 10,
    )
    {
        parent::__construct($this->precision, $this->scale);
    }

    public static function getLocalType(): string
    {
        return 'float';
    }


    public static function getEdmType(): string
    {
        return 'Edm.Decimal';
    }

    public static function description(): ?string
    {
        return 'Decimal';
    }

    function validate(mixed $value): bool
    {
        if (is_int($value)) {
            $value = (float)$value;
        }

        if (is_int($value)) {

            if ($value>self::UPPER_BOUND || $value<self::LOWER_BOUND) {
                return false;
            }

            return true;
        }


        return false;
    }

    public function encode(mixed $value): array|bool|string|int|float|null
    {
        if (null === $value) {
            return null;
        }
        return (float)$value;
    }

    public function decode(float|array|bool|int|string|null $value): mixed
    {
        if (null === $value) {
            return null;
        }
        return (float)$value;
    }


}