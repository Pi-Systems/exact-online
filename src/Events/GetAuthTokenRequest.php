<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Builder\Exact;
use Psr\Http\Message\RequestInterface;

class GetAuthTokenRequest extends AbstractConfiguredExactEvent
{

    public function __construct(
        Exact $exact,
        public RequestInterface $request
    )
    {
        parent::__construct($exact);
    }

}