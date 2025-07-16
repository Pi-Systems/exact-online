<?php

namespace PISystems\ExactOnline\Builder;


#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Endpoint extends ExactAttribute
{
    public function __construct(
        public string $uri,
        public string $globalName
    )
    {

    }
}