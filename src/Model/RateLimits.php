<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Events\RateLimitReached;
use PISystems\ExactOnline\Exact;
use PISystems\ExactOnline\Exceptions\RateLimitReachedException;
use Psr\Http\Message\ResponseInterface;

class RateLimits
{
    public \DateTimeImmutable $lastRefresh;
    public \DateTimeImmutable $dailyResetTime;
    public \DateTimeImmutable $minuteResetTime;

    public function __construct(
        public readonly Exact  $exact,
        public int    $dailyRateLimit,
        public int             $dailyRemaining,
        public int             $minuteRateLimit,
        public int             $minuteRemaining,
        int|\DateTimeImmutable $dailyResetTime = new \DateTimeImmutable(),
        int|\DateTimeImmutable $minuteResetTime = new \DateTimeImmutable(),
    )
    {
        $this->lastRefresh = new \DateTimeImmutable();

        if (is_int($dailyResetTime)) {
            $this->dailyResetTime = \DateTimeImmutable::createFromTimestamp($dailyResetTime);
        }

        if (is_int($minuteResetTime)) {
            $this->minuteResetTime = \DateTimeImmutable::createFromTimestamp($minuteResetTime);
        }
    }

    public static function createFromLimits(
        Exact $exact,
        int   $dailyRateLimit = 5000,
        int   $minuteRateLimit = 60,
        int   $dailyResetRate = 1000,
    ): RateLimits
    {
        return new self(
            $exact,
            $dailyRateLimit,
            $dailyRateLimit,
            $dailyResetRate,
            $minuteRateLimit,
            $minuteRateLimit,
        );
    }

    public static function createFromResponse(
        Exact $exact,
        ResponseInterface $response
    ): static
    {
        $new = self::createFromLimits($exact);
        return $new->updateFromResponse($response);
    }

    public function updateFromResponse(
        ResponseInterface $response,
    ) : self
    {
        $required = [
            'X-RateLimit-Limit' => 'dailyRateLimit',
            'X-RateLimit-Remaining' => 'dailyRemaining',
            'X-RateLimit-Reset' => 'dailyResetTime',
            'X-RateLimit-Minutely-Limit' => 'minuteRateLimit',
            'X-RateLimit-Minutely-Remaining' => 'minuteRemaining',
            'X-RateLimit-Minutely-Reset"' => 'minuteResetTime',
        ];

        foreach ($required as $header => $property) {
            $val = $response->getHeaderLine($header);

            if(empty($val)) {
                continue;
            }
            if (
                $property === 'dailyResetTime' ||
                $property === 'minuteResetTime'
            ) {
                $this->{$property} = \DateTimeImmutable::createFromTimestamp((int)$val);
            } else {
                $this->{$property} = (int)$val;
            }
        }

        return $this;
    }

    protected function doRefreshCalculations(): void
    {
        if ($this->lastRefresh->getTimestamp() > $this->dailyResetTime->getTimestamp()) {
            // Reset daily
            $this->dailyRemaining = $this->dailyRateLimit;
            $this->dailyResetTime = $this->dailyResetTime->add(new \DateInterval('P1D'));
        }

        $now = new \DateTimeImmutable();
        if (($this->lastRefresh->getTimestamp()+60) >= $now->getTimestamp()) {
            $this->minuteRemaining = $this->minuteRateLimit;
            $this->lastRefresh = $now;
        }
    }

    public function isRateLimited(): bool
    {
        return $this->dailyRemaining <= 0 || $this->minuteRemaining <= 0;
    }

    public function isDailyLimited(): bool
    {
        return $this->dailyRemaining <= 0;
    }

    public function isMinutelyLimited(): bool
    {
        return $this->minuteRemaining <= 0;
    }

    public function getDailyRemaining() : int
    {
        $this->doRefreshCalculations();
        return $this->dailyRemaining;
    }

    public function getRemainingMinutely() : int
    {
        $this->doRefreshCalculations();
        return $this->minuteRemaining;
    }

    /**
     * @return array{dailyRemaining: int, minutelyRemaining: int}
     */
    public function consume(): array
    {
        $this->doRefreshCalculations();

        if ($this->dailyRemaining) {
            throw new RateLimitReachedException(
                $this->exact,
                new RateLimitReached($this->exact, $this)
            );
        }

        $this->minuteRateLimit--;
        $this->dailyRemaining--;

        return [
            'dailyRemaining' => $this->dailyRemaining,
            'minutelyRemaining' => $this->minuteRemaining
        ];
    }
}