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
        public readonly ClientExceptionInterface|RequestExceptionInterface|NetworkExceptionInterface $exception,
    )
    {
        parent::__construct(
            $exact,
            $this->exception->getMessage(),
            $this->exception->getCode(),
            $this->exception
        );
    }
}