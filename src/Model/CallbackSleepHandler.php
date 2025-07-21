<?php

namespace PISystems\ExactOnline\Model;

use Psr\Http\Message\RequestInterface;

readonly class CallbackSleepHandler implements SleepHandlerInterface
{

    public function __construct(
        private \Closure $callback
    )
    {

    }

    public function sleep(int $timeout, int $attempts, RequestInterface $request, RateLimits $limits): ?int
    {
        $result = ($this->callback)($timeout, $attempts, $request, $limits);

        // Trigger error, this will cause a crash/error out in env='dev', but will not bring down production.
        // We don't know their original intention, but it's pretty safe to just return null in this case.
        if (null !== $result && !is_int($result)) {
            trigger_error("Callback sleep handler must return null or an integer (Error is ignored in prod, but the result will be converted to null.).");
            return null;
        }

        return $result;
    }
}