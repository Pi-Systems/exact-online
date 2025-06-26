<?php

namespace PISystems\ExactOnline\Builder;

use PISystems\ExactOnline\Enum\HttpMethod;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Endpoint
{
    public function __construct(
        public string $uri {
            set {
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    throw new \InvalidArgumentException("Invalid URL");
                }
                $this->uri = $value;
            }
            get => $this->uri;
        }
    )
    {

    }
}
