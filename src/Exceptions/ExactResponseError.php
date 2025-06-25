<?php

namespace PISystems\ExactOnline\Exceptions;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ExactResponseError extends ExactRequestError
{

    public function __construct(
        string $message,
        RequestInterface $request,
        private readonly ResponseInterface $response
    )
    {
        parent::__construct($message, $request);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}