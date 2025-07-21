<?php

namespace PISystems\ExactOnline\Exceptions;

use PISystems\ExactOnline\Model\ExactEnvironment;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Client\RequestExceptionInterface;

class ExactCommunicationError extends ExactException
{
    public function __construct(
        ExactEnvironment $exact,
        public readonly string|ClientExceptionInterface|RequestExceptionInterface|NetworkExceptionInterface $exception,
    )
    {
        if (is_string($exception)) {
            parent::__construct($exact, $exception);
            return;
        }

        parent::__construct(
            $exact,
            $this->exception->getMessage(),
            $this->exception->getCode(),
            $this->exception
        );
    }
}