<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Builder\Exact;
use Psr\Http\Message\RequestInterface;

/**
 * Fired if no accessToken is available.
 */
class RefreshCredentials extends AbstractConfiguredExactEvent
{
    public ?string $refreshToken = null;

    public function __construct(
        Exact $exact,
        public ?int $administration = null,
        public RequestInterface $request
    )
    {
        parent::__construct($exact);
    }

}