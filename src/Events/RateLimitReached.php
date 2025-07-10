<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Exact;
use PISystems\ExactOnline\Model\Event;
use PISystems\ExactOnline\Model\RateLimits;
use Psr\EventDispatcher\StoppableEventInterface;

class RateLimitReached extends Event implements StoppableEventInterface
{
    protected bool $propagationStopped = false;

    /**
     * Calling this means "we've dealt with it, shut up and continue".
     *
     * Any exceptions due to limit further down the pipeline are your own problem.
     *
     * @return static
     */
    public function ignore() : static
    {
        $this->propagationStopped = true;
        return $this;
    }

    public function __construct(
        Exact                      $exact,
        public readonly RateLimits $dailyLimits,
    )
    {
    }

    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }
}