<?php

namespace PISystems\ExactOnline\Model;

use GuzzleHttp\Exception\RequestException;
use PISystems\ExactOnline\Polyfill\FormStream;
use Psr\Http\Message\RequestInterface;

class ExactRuntimeConfiguration
{
    /**
     * @var int This is 30 seconds by Exacts api standards
     *          Setting this to exactly 30 seconds runs into situation where our or their clocks are slightly out of sync.
     *          (Or any other silly scenario that might create a time difference)
     *          At 15 seconds, it is still well within bounds, and has practically no chance of getting a timing error.
     */
    public const int TOKEN_EXPIRE_SLUSH = 15;

    /**
     * @param ExactAppConfigurationInterface $exactAppConfiguration
     * @param int|null $division
     * @param string|null $organizationAuthorizationCode
     * @param string|null $organizationAccessToken
     * @param \DateTimeInterface|null $organizationAccessTokenExpires
     * @param string|null $organizationRefreshToken
     *
     * @internal It is not recommended to initialize this yourself.
     *           Please use ExactConnectionManager->createRuntimeConfiguration
     */
    public function __construct(
        #[\SensitiveParameter]
        private readonly ExactAppConfigurationInterface $exactAppConfiguration,
        public ?int                                     $division = null,
        #[\SensitiveParameter]
        public ?string                                 $organizationAuthorizationCode = null,
        #[\SensitiveParameter]
        public ?string                                 $organizationAccessToken = null,
        #[\SensitiveParameter]
        public ?\DateTimeInterface                     $organizationAccessTokenExpires = null,
        #[\SensitiveParameter]
        public ?string                                 $organizationRefreshToken = null,
    )
    {
        if (str_contains($this->organizationAuthorizationCode, '&')) {
            throw new \RuntimeException(
                'Error between chair and monitor, developer did not extract the code from the return url properly. (Likely forgot redirect_uri returns with &state= at the end)'
            );
        }

        if (str_contains($this->organizationAuthorizationCode, '%')) {
            throw new \RuntimeException(
                'Error between chair and monitor, developer did not extract the code from the return url properly. (There are still encoded elements present in the string, url_decode these.)'
            );
        }
    }

    /**
     * Creates a readonly copy that is 'safe' to use in events.
     * @return ExactOrganizationalConfigurationalData
     */
    public function toOrganizationData() : ExactOrganizationalConfigurationalData
    {
        return new ExactOrganizationalConfigurationalData(
            $this->division,
            $this->organizationAuthorizationCode,
            $this->organizationAccessToken,
            $this->organizationAccessTokenExpires,
            $this->organizationRefreshToken,
        );
    }

    public function clientId(): string
    {
        return $this->exactAppConfiguration->clientId();
    }

    public function redirectUri(): string
    {
        return $this->exactAppConfiguration->redirectUri();
    }


    /**
     * @see https://support.exactonline.com/community/s/knowledge-base#All-All-DNO-Content-oauth-eol-oauth-devstep3
     *
     * @param RequestInterface $request
     * @return RequestInterface
     */
    public function addRequestTokenData(RequestInterface $request): RequestInterface
    {
        if (!empty($this->organizationRefreshToken)) {
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

        return $request;
    }

    public function addAuthorizationData(RequestInterface $request): RequestInterface
    {
        if (empty($this->organizationAccessToken)) {
            throw new RequestException('No access token set', $request);
        }

        return $request->withHeader('Authorization', 'Bearer ' . $this->organizationAccessToken);
    }

    public function hasAuthorizationData(): bool
    {
        return !empty($this->organizationAuthorizationCode);
    }

    /**
     * Does not check validity, only that it actually exists.
     * @return bool
     */
    public function hasAccessToken(): bool {
        return !empty($this->organizationAccessToken);
    }

    /**
     * Checks if it exists, and checks if it is still valid.
     * @return bool
     */
    public function hasValidAccessToken(): bool
    {
        if (!$this->hasAccessToken()) {
            return false;
        }

        // Despite possibly still being self::TOKEN_EXPIRE_FLUSH seconds left on the token
        // We want to refresh the token asap at this point.
        // This is allowed, and even expected by Exact. (Exact expects refresh <30 seconds before expire.)
        if (($this->organizationAccessTokenExpires->getTimestamp() - self::TOKEN_EXPIRE_SLUSH) < time()) {
            return false;
        }

        return true;
    }

    public function hasRefreshToken(): bool
    {
        return null !== $this->organizationRefreshToken;
    }

}
