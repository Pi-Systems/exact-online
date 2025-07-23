<?php

namespace PISystems\ExactOnline\Builder;

#[\Attribute(\Attribute::TARGET_CLASS)]
class PageSize extends ExactAttribute
{
    public function __construct(
        public int $value = 60
    )
    {
        parent::__construct(...func_get_args());
    }
}