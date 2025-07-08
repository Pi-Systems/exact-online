<?php

namespace PISystems\ExactOnline\Model;

abstract class Integer extends EdmDataStructure implements EdmEncodableDataStructure
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
        return "Int".static::getBitCount();
    }

    function validate(mixed $value): bool
    {
        if (!is_int($value)) {
            throw new \InvalidArgumentException('Input must be an integer');
        }
        return pow(2, static::getBitCount()) > $value;
    }

    public function encode(mixed $value): array|bool|string|int|float|null
    {
        if (null === $value) { return null; }
        return (int)$value;
    }

    public function decode(float|array|bool|int|string|null $value): mixed
    {
        if (null === $value) { return null; }
        return (int)$value;
    }
}
