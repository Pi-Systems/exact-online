<?php

namespace PISystems\ExactOnline\Polyfill;

class JsonDataStream extends SimpleStream
{
    public function __construct(array $elements, bool $readonly = false)
    {
        if (array_is_list($elements)) {
            throw new \InvalidArgumentException('FormStream expects an associative array');
        }

        parent::__construct(json_encode($elements), $readonly);
    }

    public const string CONTENT_TYPE = 'application/json';
}