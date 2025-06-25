<?php

namespace PISystems\ExactOnline\Exceptions;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

class ExactRequestError extends \Error implements RequestExceptionInterface
{

    public function __construct(
        string $message,
        private RequestInterface $request
    )
    {
        parent::__construct($message);
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}