<?php

namespace PISystems\ExactOnline\Events;

use JetBrains\PhpStorm\Pure;
use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Model\ExactEvent;
use PISystems\ExactOnline\Model\ExactRateLimits;
use Psr\EventDispatcher\StoppableEventInterface;

class RateLimitReached extends ExactEvent implements StoppableEventInterface
{
    private bool $propagationStopped = false;

    /**
     * Calling this means "we've dealt with it, shut up and continue".
     *
     * Any exceptions due to limit further down the pipeline are your own problem.
     *
     * @return bool
     */
    public function ignore() : bool
    {
        $this->propagationStopped = true;
    }

    public function __construct(
        Exact                           $exact,
        public readonly ExactRateLimits $dailyLimits,
    )
    {
    }

    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }
}
