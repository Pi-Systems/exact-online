<?php

namespace PISystems\ExactOnline\Exceptions;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Events\RateLimitReached;

class RateLimitReachedException extends ExactException
{
    public function __construct(
        Exact $exact,
        public readonly RateLimitReached $event
    )
    {

        $message = 'Rate limits reached.';
        $types = [];
        if ($this->event->dailyLimits->isDailyLimited()) {
            $types[] = 'daily';
        }
        if ($this->event->dailyLimits->isMinutelyLimited()) {
            $types[] = 'minutely';
        }

        $message = $message . ' (' . implode(', ', $types) . ')';
        parent::__construct($exact, $message);
    }

}
