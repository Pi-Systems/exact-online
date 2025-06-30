<?php

namespace PISystems\ExactOnline\Model;

use GuzzleHttp\Exception\RequestException;
use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Enum\CredentialsType;
use PISystems\ExactOnline\Events\CredentialsSaveEvent;
use PISystems\ExactOnline\Events\RefreshCredentials;
use PISystems\ExactOnline\Exceptions\UnauthenticatedError;
use PISystems\ExactOnline\Polyfill\ExactEventDispatcher;
use PISystems\ExactOnline\Polyfill\FormStream;
use Psr\Http\Message\RequestInterface;

class ExactRuntimeConfiguration
{
    public const int TOKEN_EXPIRE_SLUSH = 10;

    /**
     * Do not set manually, this is filled when constructing Exact
     * @var Exact|null
     */
    public ?Exact $exact = null {
        set {

            if (null === $value) {
                throw new \InvalidArgumentException('Exact may only be null during __construct.');
            }

            // Could throw 'accessed twice' or something, but that would require effort.
            if ($this->exact === $value) { return ;}

            if ($this->exact) {
                throw new \LogicException('Exact instance already set');
            }
            $this->exact = $value;
        }
        get => $this->exact;
    }

    public ?ExactEventDispatcher $dispatcher = null {
        set {

            if (null === $value) {
                throw new \InvalidArgumentException('Dispatcher may only be null during __construct.');
            }

            // Could throw 'accessed twice' or something, but that would require effort.
            if ($this->dispatcher !== $value) { return; }

            if ($this->dispatcher) {
                throw new \LogicException('Dispatcher already set');
            }

            $this->dispatcher = $value;
        }
        get => $this->dispatcher;
    }

    public function __construct(
        #[\SensitiveParameter]
        private readonly ExactAppConfigurationInterface $exactAppConfiguration,
        #[\SensitiveParameter]
        private ?string                        $organizationAuthorizationCode = null,
        #[\SensitiveParameter]
        private ?string                                 $organizationAccessToken = null,
        #[\SensitiveParameter]
        private ?int                                    $organizationAccessTokenExpires = null,
        #[\SensitiveParameter]
        private ?string                                 $organizationRefreshToken = null,
        // You're not turning this off once it's been constructing.
        public readonly bool                            $allowSave = true,
        // We're also not suddenly, magically going to be something else during runtime.
        public readonly string                          $userAgent = 'PISystems/ExactOnline',
        /**
         * If set, the library will fire off a 'SoftLimitReached' event when the minutely limit
         * has been hit.
         * If this event is canceled, the limit is 'ignored' (Aka: We've dealt with the timeout ourselves)
         * Otherwise, the library will wait for the next minutely reset + 1 second using usleep.
         *
         * @var bool
         */
        public bool $awaitMinutelyTimeout = true,
    )
    {

    }

    public function clientId() : string
    {
        return $this->exactAppConfiguration->clientId();
    }

    public function redirectUri() : string
    {
        return $this->exactAppConfiguration->redirectUri();
    }

    public function authorize(string $token) : void
    {
        $this->organizationAuthorizationCode = $token;
        $this->save();
    }

    protected function assertInActiveState(): void
    {
        if (!$this->exact) {
            throw new \LogicException('Exact instance not set');
        }

        if (!$this->dispatcher) {
            throw new \LogicException('Dispatcher not set');
        }
        if (!$this->dispatcher->isLocked()) {
            throw new \LogicException('Dispatcher must be locked');
        }
    }

    public function addRequestTokenData(RequestInterface $request): RequestInterface
    {
        $this->assertInActiveState();

        if ($this->organizationRefreshToken) {
            $request = $request->withBody(
                $this->exactAppConfiguration->addClientDetails(
                    new FormStream([
                        'refresh_token' => $this->organizationRefreshToken,
                        'grant_type' => 'refresh_token',
                    ]),
                    ExactAppConfigurationInterface::CLIENT_ID | ExactAppConfigurationInterface::CLIENT_SECRET
                ));
        } else {
            $request = $request->withBody(
                $this->exactAppConfiguration->addClientDetails(
                    new FormStream([
                        'grant_type' => 'authorization_code',
                        'code' => $this->organizationAuthorizationCode,
                    ]),
                    ExactAppConfigurationInterface::CLIENT_ID | ExactAppConfigurationInterface::CLIENT_SECRET | ExactAppConfigurationInterface::CLIENT_REDIRECT_URI

                )
            );
        }

        return $request->withHeader('Content-Type', FormStream::CONTENT_TYPE);
    }

    public function addAuthorizationData(RequestInterface $request): RequestInterface {
        if (!$this->organizationAccessToken) {
            throw new UnauthenticatedError($this->exact,
                new RequestException('No access token set', $request)
            );
        }

        return $request->withHeader('Authorization', 'Bearer ' . $this->organizationAccessToken);
    }

    public function hasAuthorizationData() : bool
    {
        return null !== $this->organizationAuthorizationCode;
    }

    public function hasValidAccessToken() : bool
    {
        if (!$this->organizationAccessToken) {
            return false;
        }

        if (($this->organizationAccessTokenExpires - self::TOKEN_EXPIRE_SLUSH) < time()) {
            return false;
        }

        return true;
    }

    public function hasRefreshToken() : bool
    {
        return null !== $this->organizationRefreshToken;
    }

    public function setOrganizationAccessToken(string $accessToken, int $expires): static
    {
        $this->assertInActiveState();

        $e = new RefreshCredentials($this, CredentialsType::AccessToken);
        $this->dispatcher?->dispatch($e);

        if ($e->isPropagationStopped()) {
            return $this;
        }

        $this->organizationAccessToken = $accessToken;
        $this->organizationAccessTokenExpires = $expires;

        return $this;
    }

    public function setOrganizationRefreshToken(string $organizationRefreshToken): static
    {
        $this->assertInActiveState();

        if (!$this->organizationAccessToken) {
            throw new \LogicException('Cannot set refresh token without an access token');
        }

        $e = new RefreshCredentials($this, CredentialsType::RefreshToken);
        $this->dispatcher->dispatch($e);

        if ($e->isPropagationStopped()) {
            return $this;
            }

        $this->organizationRefreshToken = $organizationRefreshToken;

        return $this;
    }

    public function save(): bool
    {
        if (!$this->allowSave) {
            return false;
        }

        $this->assertInActiveState();

        $e = new CredentialsSaveEvent(
            $this->exact,
            $this->organizationAuthorizationCode,
            $this->organizationAccessToken,
            $this->organizationAccessTokenExpires,
            $this->organizationRefreshToken
        );

        $this->dispatcher->dispatch($e);

        if ($e->isPropagationStopped()) {
            return false;
        }

        return $e->isSaveSuccess();

    }
}
