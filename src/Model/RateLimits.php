<?php

namespace PISystems\ExactOnline\Model;

use Psr\Http\Message\ResponseInterface;

class RateLimits implements \JsonSerializable
{
    public \DateTimeImmutable $lastRefresh;
    public \DateTimeImmutable $dailyResetTime;
    public \DateTimeImmutable $minuteResetTime;

    public function __construct(
        public int $dailyRateLimit,
        public int             $dailyRemaining,
        public int             $minuteRateLimit,
        public int             $minuteRemaining,
        int|\DateTimeImmutable $dailyResetTime = new \DateTimeImmutable(),
        int|\DateTimeImmutable $minuteResetTime = new \DateTimeImmutable(),
    )
    {
        $this->lastRefresh = new \DateTimeImmutable();

        $this->dailyResetTime =
            is_int($dailyResetTime)
                ? \DateTimeImmutable::createFromTimestamp($dailyResetTime)
                : $dailyResetTime;

        $this->minuteResetTime =
            is_int($minuteResetTime)
                ? \DateTimeImmutable::createFromTimestamp($minuteResetTime)
                : $minuteResetTime;
    }

    public static function createFromDefaults(
        int $dailyRateLimit = 5000,
        int $minuteRateLimit = 60,
    ): RateLimits
    {
        return new self(
            $dailyRateLimit,
            $dailyRateLimit,
            $minuteRateLimit,
            $minuteRateLimit
        );
    }

    public function toArray(): array
    {
        return [
            'dailyRateLimit' => $this->dailyRateLimit,
            'dailyRemaining' => $this->dailyRemaining,
            'dailyResetTime' => $this->dailyResetTime,
            'minuteRateLimit' => $this->minuteRateLimit,
            'minuteRemaining' => $this->minuteRemaining,
            'minuteResetTime' => $this->minuteResetTime,
        ];
    }

    public static function createFromArray(array $config): static
    {
        return new self(
            $config['dailyRateLimit'],
            $config['dailyRemaining'],
            $config['minuteRateLimit'],
            $config['minuteRemaining'],
            $config['dailyResetTime'],
            $config['minuteResetTime'],
        );
    }

    public function updateFromResponse(
        ResponseInterface $response,
    ): self
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

            if (empty($val)) {
                continue;
            }
            if (
                $property === 'dailyResetTime' ||
                $property === 'minuteResetTime'
            ) {
                $this->{$property} =
                    \DateTimeImmutable::createFromTimestamp((int)$val);
            } else {
                $this->{$property} = (int)$val;
            }
        }

        return $this;
    }

    protected function doRefreshCalculations(): void
    {
        if ($this->lastRefresh->getTimestamp() >
            $this->dailyResetTime->getTimestamp()) {
            // Reset daily
            $this->dailyRemaining = $this->dailyRateLimit;
            $this->dailyResetTime =
                $this->dailyResetTime->add(new \DateInterval('P1D'));
        }

        $now = new \DateTimeImmutable();
        if (($this->lastRefresh->getTimestamp() + 60) >= $now->getTimestamp()) {
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

    public function getDailyRemaining(): int
    {
        $this->doRefreshCalculations();

        return $this->dailyRemaining;
    }

    public function getRemainingMinutely(): int
    {
        $this->doRefreshCalculations();

        return $this->minuteRemaining;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}