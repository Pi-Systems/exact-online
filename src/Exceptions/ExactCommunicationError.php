<?php

namespace PISystems\ExactOnline\Exceptions;

use PISystems\ExactOnline\Builder\Exact;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Client\RequestExceptionInterface;

class ExactCommunicationError extends ExactException
{
    public function __construct(
        Exact                                                                        $exact,
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