<?php

namespace PISystems\ExactOnline\Exceptions;

use JetBrains\PhpStorm\Pure;
use PISystems\ExactOnline\Model\ExactEnvironment;

class MissingListenerException extends ExactException
{
    #[Pure] public function __construct(
        public readonly string $eventName,
        ExactEnvironment $exact,
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null)
    {
        parent::__construct($exact, $message, $code, $previous);
    }

}