<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Enum\CredentialsType;
use PISystems\ExactOnline\Model\Event;
use PISystems\ExactOnline\Model\ExactEnvironment;

/**
 * Fired if no accessToken is available.
 *
 * Note: The actual value is not passed for security reasons.
 * Only the ExactRuntimeConfiguration itself has access to this.
 * The configuration has no accessors for this information.
 */
class RefreshCredentials extends Event
{
    public function __construct(
        public readonly ExactEnvironment $environment,
        public readonly CredentialsType           $type,
    )
    {
    }

}