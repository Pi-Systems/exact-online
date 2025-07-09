<?php

namespace PISystems\ExactOnline\Model;

// This was created to ensure Accounts::$Code works without having to think about it.
interface FilterEncodableDataStructure
{
    /**
     * Before encoding the output, run through this first.
     *
     * @param mixed $value
     * @return TypedValue|null
     */
    public function encodeForFilter(mixed $value): ?TypedValue;
}