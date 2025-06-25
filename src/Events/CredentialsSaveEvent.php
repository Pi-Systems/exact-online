<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Model\ExactEvent;

/**
 * Fired if no accessToken is available.
 */
class CredentialsSaveEvent extends AbstractConfiguredExactEvent
{
    private bool $isSuccess = false;

    public function __construct(
        Exact $exact,
        public readonly string  $clientId,
        public readonly string  $redirectUri,
        #[\SensitiveParameter]
        public readonly string $clientSecret,
        #[\SensitiveParameter]
        public string          $authorizationCode,
        #[\SensitiveParameter]
        public ?string         $accessToken = null,
        #[\SensitiveParameter]
        public ?int            $accessTokenExpires = null,
        #[\SensitiveParameter]
        public ?string         $refreshToken = null,
    )
    {
        parent::__construct($exact);
    }

    public function saveSuccess() : static
    {
        $this->isSuccess = true;
        return $this;
    }

    public function saveFailed() : static
    {
        $this->isSuccess = false;
        return $this;
    }

    public function isSaveSuccess() : bool
    {
        return $this->isSuccess;
    }
}