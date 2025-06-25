<?php

namespace PISystems\ExactOnline\Exceptions;

use JetBrains\PhpStorm\Pure;
use PISystems\ExactOnline\Builder\Exact;

class MissingListenerException extends ExactException
{
    #[Pure] public function __construct(
        public readonly string $eventName,
        Exact $exact,
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null)
    {
        parent::__construct($exact, $message, $code, $previous);
    }

}