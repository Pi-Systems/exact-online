<?php

namespace PISystems\ExactOnline\Model;

use Psr\Http\Message\RequestInterface;

interface SleepHandlerInterface
{
    public function sleep(
        int              $timeout,
        int              $attempts,
        RequestInterface $request,
        RateLimits       $limits
    ): ?int;
}