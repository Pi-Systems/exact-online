<?php

namespace PISystems\ExactOnline\Model;

abstract class Integer extends EdmDataStructure
{
    public static function getBitCount(): int {
        return 1;
    }

    public static function getEdmType(): string
    {
        return 'Edm.Int'.(static::getBitCount());
    }

    public static function getLocalType(): string
    {
        return 'int';
    }

    public static function description(): ?string
    {
        return "A ".static::getBitCount()." bit integer";
    }

    function validate(mixed $value): bool
    {
        if (!is_int($value)) {
            throw new \InvalidArgumentException('Input must be an integer');
        }
        return pow(2, static::getBitCount()) > $value;
    }

}