<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\EdmDataStructure;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class Single extends EdmDataStructure
{
    const float UPPER_BOUND = 1.18E38;
    const float LOWER_BOUND = -3.40E38;

    public function __construct(
        public int $precision = 10,
        public int $scale = 10,
    )
    {
        parent::__construct($this->precision, $this->scale);
    }

    public static function getEdmType(): string
    {
        return 'Edm.Single';
    }

    public static function getLocalType(): string
    {
        return 'int';
    }

    public static function description(): ?string
    {
        return '1 Byte';
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