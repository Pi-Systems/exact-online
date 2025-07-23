<?php

namespace PISystems\ExactOnline\Builder;

class ExactAttribute
{
    public readonly array $arguments;

    public function __construct()
    {
        $this->arguments = func_get_args();
    }
}