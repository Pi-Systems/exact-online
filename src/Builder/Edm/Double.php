<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\EdmDataStructure;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class Double extends EdmDataStructure
{
    const float UPPER_BOUND = 2.23E308;
    const float LOWER_BOUND = -3.40E308;

    public function __construct(
        public int $precision = 10,
        public int $scale = 10,
    )
    {

    }

    public static function getEdmType(): string
    {
        return 'Edm.Double';
    }

    public static function getLocalType(): string
    {
        return 'float';
    }

    public static function description(): ?string
    {
        return 'Double';
    }

    function validate(mixed $value): bool
    {
        if (is_int($value)) {
            $value = (float)$value;
        }

        if (is_int($value)) {

            if ($value > self::UPPER_BOUND || $value < self::LOWER_BOUND) {
                return false;
            }

            return true;
        }


        return false;
    }

}
