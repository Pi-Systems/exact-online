<?php

namespace PISystems\ExactOnline\Model;

use JetBrains\PhpStorm\Pure;
use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Exceptions\ExactException;
use Psr\EventDispatcher\StoppableEventInterface;

class RateLimitReached extends ExactException implements StoppableEventInterface
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

    #[Pure] public function __construct(
        Exact                           $exact,
        public readonly ExactRateLimits $dailyLimits,
    )
    {
        $message = 'Rate limits reached.';
        $types = [];
        if ($this->dailyLimits->isDailyLimited()) {
            $types[] = 'daily';
        }
        if ($this->dailyLimits->isMinutelyLimited()) {
            $types[] = 'minutely';
        }

        $message = $message . ' (' . implode(', ', $types) . ')';

        parent::__construct($exact, $message, -1);
    }

    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }
}