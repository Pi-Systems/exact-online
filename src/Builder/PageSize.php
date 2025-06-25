<?php

namespace PISystems\ExactOnline\Builder;

#[\Attribute(\Attribute::TARGET_CLASS)]
class PageSize
{
    public int $value = 60;
}