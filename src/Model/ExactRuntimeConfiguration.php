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
        public readonly string            $clientId,
        public readonly string            $redirectUri,
        #[\SensitiveParameter]
        private readonly string           $clientSecret,
        #[\SensitiveParameter]
        private readonly string           $authorizationCode,
        #[\SensitiveParameter]
        private ?string                   $accessToken = null,
        #[\SensitiveParameter]
        private ?int                      $accessTokenExpires = null,
        #[\SensitiveParameter]
        private ?string                   $refreshToken = null,
        public readonly bool $allowSave = true,
        public readonly string $userAgent = 'PISystems/ExactOnline',
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

        if ($this->refreshToken) {
            $request = $request->withBody(
                new FormStream([
                'refresh_token' => $this->refreshToken,
                'grant_type' => 'refresh_token',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]));
        } else {
            $request = $request->withBody(
                new FormStream([
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'redirect_uri' => $this->redirectUri,
                    'grant_type' => 'authorization_code',
                    'code' => $this->authorizationCode,
                ])
            );
        }

        return $request->withHeader('Content-Type', FormStream::CONTENT_TYPE);

    }

    public function addAuthorizationData(RequestInterface $request): RequestInterface {
        if (!$this->accessToken) {
            throw new UnauthenticatedError($this->exact,
                new RequestException('No access token set', $request)
            );
        }

        return $request->withHeader('Authorization', 'Bearer ' . $this->accessToken);
    }

    public function hasValidAccessToken() : bool
    {
        if (!$this->accessToken) {
            return false;
        }

        if (($this->accessTokenExpires - self::TOKEN_EXPIRE_SLUSH) < time()) {
            return false;
        }

        return true;
    }

    public function hasRefreshToken() : bool
    {
        return null !== $this->refreshToken;
    }

    public function setAccessToken(string $accessToken, int $expires): static
    {
        $this->assertInActiveState();

        $e = new RefreshCredentials($this, CredentialsType::AccessToken);
        $this->dispatcher?->dispatch($e);

        if ($e->isPropagationStopped()) {
            return $this;
        }

        $this->accessToken = $accessToken;
        $this->accessTokenExpires = $expires;

        return $this;
    }

    public function setRefreshToken(string $refreshToken): static
    {
        $this->assertInActiveState();

        if (!$this->accessToken) {
            throw new \LogicException('Cannot set refresh token without an access token');
        }

        $e = new RefreshCredentials($this, CredentialsType::RefreshToken);
        $this->dispatcher->dispatch($e);

        if ($e->isPropagationStopped()) {
            return $this;
            }

        $this->refreshToken = $refreshToken;

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
            $this->clientId,
            $this->redirectUri,
            $this->clientSecret,
            $this->authorizationCode
        );

        $this->dispatcher->dispatch($e);

        if ($e->isPropagationStopped()) {
            return false;
        }

        return $e->isSaveSuccess();

    }
}