<?php

namespace PISystems\ExactOnline\Model;

abstract class EdmEncodableDataStructure extends EDMDataStructure
{
    /**
     * Before encoding the output, run through this first.
     *
     * @param mixed $value
     * @return bool|string|int|float|null
     */
    public function encode(mixed $value): bool|string|int|float|null
    {
        return (string)$value;
    }
    /**
     * Before encoding the output, run through this first.
     *
     * @param mixed $value
     * @return bool|string|int|float
     */
    #[\ReturnTypeWillChange]
    public function decode(bool|string|int|float|null $value): mixed
    {
        return $value;
    }
}