<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Model\ExactEvent;
use PISystems\ExactOnline\Model\ExactOrganizationalConfigurationalData;

/**
 * Fired if no accessToken is available.
 */
class CredentialsChange extends ExactEvent
{
    private bool $isSuccess = false;

    public function __construct(
        public readonly Exact $exact,
        #[\SensitiveParameter]
        public readonly ExactOrganizationalConfigurationalData $configuration
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
