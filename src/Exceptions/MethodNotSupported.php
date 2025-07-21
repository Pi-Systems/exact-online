<?php

namespace PISystems\ExactOnline\Exceptions;

use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\ExactEnvironment;

class MethodNotSupported extends ExactException
{
    public function __construct(
        ExactEnvironment $exact,
        string $source,
        HttpMethod $method,
    )
    {
        $message = sprintf(
            '[%s] Is not supported on class %s.',
            $method->value,
            $source
        );

        parent::__construct($exact, $message);
    }

}