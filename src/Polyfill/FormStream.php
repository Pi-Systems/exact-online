<?php

namespace PISystems\ExactOnline\Polyfill;

class FormStream extends SimpleStream
{
    public function __construct(array $elements, bool $readonly = false)
    {
        if (array_is_list($elements)) {
            throw new \InvalidArgumentException('FormStream expects an associative array');
        }

        parent::__construct(http_build_query($elements), $readonly);
    }

    public const string CONTENT_TYPE = 'application/x-www-form-urlencoded';
}