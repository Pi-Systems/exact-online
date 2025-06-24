<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Enum\HttpMethod;
use Psr\Http\Message\RequestInterface;

class InitRequestRequest extends AbstractRequestEvent
{
    public function __construct(
        Exact $exact,
        RequestInterface $request,
        public HttpMethod $method
    )
    {
        parent::__construct($exact, $request);
    }

}