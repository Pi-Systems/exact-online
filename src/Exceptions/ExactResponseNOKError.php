<?php

namespace PISystems\ExactOnline\Exceptions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ExactResponseNOKError extends ExactResponseError
{
    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        $message = sprintf('[%d] %s', $response->getStatusCode(), $response->getReasonPhrase());

        parent::__construct($message, $request, $response);
    }

}