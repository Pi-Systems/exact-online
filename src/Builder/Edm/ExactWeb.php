<?php

namespace PISystems\ExactOnline\Builder\Edm;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
readonly class ExactWeb
{
    public function __construct(public string $type)
    {

    }
}
