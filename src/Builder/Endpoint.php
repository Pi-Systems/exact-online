<?php

namespace PISystems\ExactOnline\Builder;


#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Endpoint extends ExactAttribute
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