<?php

namespace PISystems\ExactOnline\Exceptions;

use PISystems\ExactOnline\Builder\Exact;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface;

class OfflineException extends ExactException implements NetworkExceptionInterface
{
    public function __construct(Exact $exact, public readonly RequestInterface $request)
    {
        parent::__construct($exact, "Exact is in offline mode");
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
