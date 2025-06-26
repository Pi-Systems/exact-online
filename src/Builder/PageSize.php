<?php

namespace PISystems\ExactOnline\Builder;

#[\Attribute(\Attribute::TARGET_CLASS)]
class PageSize
{
    public function __construct(
        public int $value = 60
    )
    {

    }
}
