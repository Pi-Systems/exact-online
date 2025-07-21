<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Exact;
use PISystems\ExactOnline\Model\Event;
use PISystems\ExactOnline\Model\ExactOrganizationalConfigurationalData;

/**
 * Fired if no accessToken is available.
 */
class ConfigurationChange extends Event
{
    private bool $isSuccess = false;

    public function __construct(
        public readonly Exact $exact,
        #[\SensitiveParameter]
        public readonly ExactOrganizationalConfigurationalData $configuration,
    )
    {
    }

    public function saveSuccess(): static
    {
        $this->isSuccess = true;
        return $this;
    }

    public function saveFailed(): static
    {
        $this->isSuccess = false;
        return $this;
    }

    public function isSaveSuccess(): bool
    {
        return $this->isSuccess;
    }
}