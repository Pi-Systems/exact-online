<?php

namespace PISystems\ExactOnline\Model;

// This was created to ensure Accounts::$Code works without having to think about it.
abstract class FilterEncodableDataStructure extends EdmEncodableDataStructure
{
    /**
     * Before encoding the output, run through this first.
     *
     * @param mixed $value
     * @return bool|string|int|float|null
     */
    public function encodeForFilter(mixed $value): bool|string|int|float|null
    {
        return (string)$value;
    }
}
