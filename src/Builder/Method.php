<?php

namespace PISystems\ExactOnline\Builder;

use PISystems\ExactOnline\Enum\HttpMethod;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Method
{
    public function __construct(
        public HttpMethod $httpMethod,
        public bool $required = false
    )
    {

    }
}
