<?php

namespace PISystems\ExactOnline\Model;

interface EdmEncodableDataStructure
{
    /**
     * Before encoding the output, run through this first.
     *
     * @param mixed $value
     * @return array|bool|string|int|float|null
     */
    public function encode(mixed $value): array|bool|string|int|float|null;
    /**
     * Before encoding the output, run through this first.
     *
     * @param mixed $value
     * @return bool|string|int|float
     */
    public function decode(array|bool|string|int|float|null $value): mixed;
}
