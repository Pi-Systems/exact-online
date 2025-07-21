<?php

namespace PISystems\ExactOnline\Exceptions;

use JetBrains\PhpStorm\Pure;
use PISystems\ExactOnline\Model\ExactEnvironment;

abstract class ExactException extends \RuntimeException
{
    #[Pure] public function __construct(
        public readonly ExactEnvironment $exact,
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}