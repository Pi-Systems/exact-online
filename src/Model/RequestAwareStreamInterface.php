<?php

namespace PISystems\ExactOnline\Model;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

interface RequestAwareStreamInterface extends StreamInterface
{
    /**
     * While possible, one should avoid calling withBody here.
     * Try to limit to header changes.
     * @param RequestInterface $request
     * @return RequestInterface
     */
    public function configureRequest(RequestInterface $request): RequestInterface;
}
