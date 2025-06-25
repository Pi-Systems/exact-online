<?php

namespace PISystems\ExactOnline\Exceptions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ExactEmptyResponseError extends ExactResponseNOKError
{
    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        parent::__construct($request, $response);
    }

}