<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Exact;
use Psr\Http\Message\RequestInterface;

abstract class AbstractRequestEvent extends AbstractConfiguredEvent
{
    public function __construct(
        Exact $exact,
        /** Reminder: $request->withX does not update the internal state, remember to set the new request back into the event  */
        public RequestInterface $request
    ) {
        parent::__construct($exact);
    }
}