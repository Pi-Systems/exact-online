<?php

namespace PISystems\ExactOnline\Polyfill;

class FormStream extends SimpleStream
{
    public const string CONTENT_TYPE = 'application/x-www-form-urlencoded';

    private array $formElements = [];

    public function __construct(array $elements, bool $readonly = false)
    {
        if (array_is_list($elements)) {
            throw new \InvalidArgumentException('FormStream expects an associative array');
        }

        $this->formElements = $elements;

        parent::__construct(http_build_query($elements), $readonly);
    }

    public function add(array $elements): int
    {
        return $this->write($elements);
    }

    public function write(array|string $string): int
    {
        if (is_string($string)) {
            parent::write($string);
        }

        $this->formElements += $string;

        $this->reset();
        return parent::write( http_build_query($this->formElements) );
    }
}
