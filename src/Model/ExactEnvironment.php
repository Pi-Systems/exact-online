<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Enum\CredentialsType;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Events\CredentialsChange;
use PISystems\ExactOnline\Events\DivisionChange;
use PISystems\ExactOnline\Events\RateLimitReached;
use PISystems\ExactOnline\Events\RefreshCredentials;
use PISystems\ExactOnline\ExactConnectionManager;
use PISystems\ExactOnline\Exceptions\ExactCommunicationError;
use PISystems\ExactOnline\Exceptions\ExactEmptyResponseError;
use PISystems\ExactOnline\Exceptions\ExactResponseError;
use PISystems\ExactOnline\Exceptions\ExactResponseNOKError;
use PISystems\ExactOnline\Exceptions\RateLimitReachedException;
use PISystems\ExactOnline\Model\Exact as Model;
use PISystems\ExactOnline\Polyfill\FormStream;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

/*sealed*/

abstract class ExactEnvironment /*permits Exact*/
{

    public bool $treatEmptyAsError = true;
    private ?ExactRateLimits $rateLimits = null;
    public readonly CacheItemPoolInterface $cache;
    public readonly LoggerInterface $logger;

    public ?int $division = null {
        get => $this->configuration->division;
        set {
            if ($value !== $this->configuration->division) {
                $administrationSwitchEvent = new DivisionChange($this, $this->division, $value);
                $this->manager->dispatcher->dispatch($administrationSwitchEvent);
                if ($administrationSwitchEvent->isPropagationStopped()) {
                    return;
                }
            }
            $this->configuration->division = $value;
        }
    }

    final public function __construct(
        #[\SensitiveParameter]
        private readonly ExactRuntimeConfiguration $configuration,
        protected ExactConnectionManager           $manager,
    )
    {
    }

    final protected function loadAdministrationData(): int
    {
        if ($this->configuration->division) {
            return $this->configuration->division;
        }

        $meta = Model\System\Me::meta();
        $uri = $this->manager->uriFactory->createUri(
            $this->manager->apiBaseUri()
                ->withPath($meta->endpoint)
                ->withQuery('$select=CurrentDivision')
        );

        $request = $this->createRequest($uri);
        $response = $this->sendAuthenticatedRequest($request);
        $data = $this->decodeJsonRequestResponse($request, $response);

        $me = new Model\System\Me();
        $meta->hydrate($me, reset($data));

        if (!$me->CurrentDivision) {
            throw new ExactResponseError('Unable to determine current division.', $request, $response);
        }

        return $this->division = (int)$me->CurrentDivision;
    }

    final protected function createRequest(
        UriInterface      $uri,
        string|HttpMethod $method = HttpMethod::GET,
        ?StreamInterface  $body = null,
        array             $headers = [],
    ): RequestInterface
    {

        if (!$method instanceof HttpMethod) {
            $method = HttpMethod::tryFrom($method);

            if (!$method) {
                throw new \InvalidArgumentException('Invalid HTTP method supplied.');
            }
        }

        // Ensure we update the division/administration path.
        if (str_contains($uri->getPath(), '{division}')) {
            $uri = $uri->withPath(str_replace('{division}', $this->division, $uri->getPath()));
        }


        if ($body && $method === HttpMethod::GET) {
            // Technically they can, but this is generally not accepted by any sane server.
            throw new \InvalidArgumentException('GET requests cannot have a body.');
        }


        if (!empty($headers) && array_is_list($headers)) {
            throw new \InvalidArgumentException('Headers must be an associative array');
        }

        $request = $this->manager->requestFactory->createRequest(
            $method->value,
            $uri
        );

        $body ??= $request->getBody();
        if ($body) {
            if ($body instanceof RequestAwareStreamInterface) {
                $request = $body->configureRequest($request);
            }
            $request = $request->withBody($body);
        }

        $headers = array_merge([
            'Accept' => 'application/json',
            'User-Agent' => ExactConnectionManager::USER_AGENT,
            'X-ExactOnline-Client' => $this->configuration->clientId(),
            'Prefer' => 'return=representation',
        ], $headers);


        foreach ($headers as $key => $value) {
            $request = $request->withHeader($key, $value);
        }

        return $request;
    }

    final protected function sendAuthenticatedRequest(
        RequestInterface $request,
    ): ResponseInterface
    {
        $this->configureAccessToken();

        return $this->sendRequest(
            $this->configuration->addAuthorizationData(
                $request
            )
        );
    }

    final public function configureAccessToken(): void
    {
        if ($this->configuration->hasValidAccessToken()) {
            // Nothing to configure, available token is already valid.
            return;
        }

        $uri = $this->manager->uriFactory->createUri($this->manager->generateTokenAccessUrl());
        $request =
            $this->configuration->addRequestTokenData(
            // Note to self: new FormStream is not optional.
            // CreateRequest checks for instanceof RequestAwareStreamInterface
                $this->createRequest($uri, HttpMethod::POST, new FormStream())
            );


        $now = time();
        $response = $this->sendRequest($request);
        $content = $this->decodeJsonRequestResponse($request, $response);

        $requirements = ['access_token', 'expires_in', 'refresh_token'];

        if (isset($content['error'])) {
            if (isset($content['error_description'])) {
                throw new ExactCommunicationError(
                    $this,
                    new ExactResponseError(
                        sprintf('[%s] %s', $content['error'], $content['error_description']),
                        $request,
                        $response
                    )
                );

            }
            throw new ExactCommunicationError(
                $this,
                new ExactResponseError(
                    $content['error'],
                    $request,
                    $response
                )
            );
        }

        foreach ($requirements as $requirement) {
            if (!isset($content[$requirement])) {
                throw new ExactCommunicationError(
                    $this,
                    new ExactResponseError(
                        'Unable to find ' . $requirement . ' in response',
                        $request,
                        $response
                    )
                );
            }
        }

        if (!ctype_digit($content['expires_in'])) {
            throw new ExactCommunicationError(
                $this,
                new ExactResponseError(
                    'Unable to parse expires_in from response',
                    $request,
                    $response
                )
            );
        }

        $token = $content['access_token'];
        $expires = \DateTimeImmutable::createFromTimestamp($now + (int)$content['expires_in']);
        $refresh = $content['refresh_token'];

        $this->setOrganizationAccessToken($token, $expires);
        $this->setOrganizationRefreshToken($refresh);

        $this->saveConfiguration();

    }

    final public function setOrganizationAccessToken(string $accessToken, \DateTimeInterface $expires): static
    {
        $e = new RefreshCredentials($this, CredentialsType::AccessToken);
        $this->manager->dispatcher->dispatch($e);

        if ($e->isPropagationStopped()) {
            return $this;
        }

        $this->configuration->organizationAccessToken = $accessToken;

        // Once an access token has been loaded, it cannot be used again (Expires after 1-2 minutes anyway)
        // Pointless to store at this point
        $this->configuration->organizationAuthorizationCode = null;
        $this->configuration->organizationAccessTokenExpires = $expires;

        return $this;
    }

    final public function setOrganizationRefreshToken(string $organizationRefreshToken): static
    {
        if (!$this->configuration->hasAccessToken()) {
            // This is a complete lie, we could, but we're not enabling this stupid behavior.
            throw new \LogicException('Cannot set refresh token without an access token present.');
        }

        $e = new RefreshCredentials($this, CredentialsType::RefreshToken);
        $this->manager->dispatcher->dispatch($e);

        if ($e->isPropagationStopped()) {
            return $this;
        }

        $this->configuration->organizationRefreshToken = $organizationRefreshToken;

        return $this;
    }

    final public function saveConfiguration(): bool
    {
        $e = new CredentialsChange(
            $this,
            $this->configuration
        );

        $this->manager->dispatcher->dispatch($e);

        if ($e->isPropagationStopped()) {
            return false;
        }

        return $e->isSaveSuccess();

    }

    final protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        // Do not use the accessor, as it would instantiate an empty one.
        // We really do want to check if we know our limits already.
        if (null !== $this->rateLimits) {
            if ($this->rateLimits->isRateLimited()) {

                $limitEvent = new RateLimitReached($this, $this->rateLimits);

                $this->manager->dispatcher->dispatch($limitEvent);

                // If the event is stopped, we stop caring.
                // User dealt with it, so...
                if (!$limitEvent->isPropagationStopped()) {
                    // Not stopped, it was allowed to cascade, throw as exception
                    throw new RateLimitReachedException($this, $limitEvent);
                }
            }
        }

        try {
            $response = $this->manager->client->sendRequest($request);
            var_dump($request, $response);
        } catch (ClientExceptionInterface $e) {
            throw new ExactCommunicationError($this, $e);
        }

        $this->updateRateLimits($response);

        if ($response->getStatusCode() < 200 && $response->getStatusCode() > 300) {
            throw new ExactCommunicationError(
                $this,
                new ExactResponseNOKError($request, $response)
            );
        }

        if ($response->getStatusCode() === 204) {
            if ($this->treatEmptyAsError) {
                throw new ExactCommunicationError(
                    $this,
                    new ExactEmptyResponseError($request, $response)
                );
            }
        }

        return $response;
    }

    final protected function updateRateLimits(ResponseInterface $response): void
    {
        $this->rateLimits??= ExactRateLimits::createFromResponse($this, $response);
    }

    final public function getRateLimits(): ExactRateLimits
    {
        return $this->rateLimits ??= ExactRateLimits::createFromLimits($this);
    }

    final protected function decodeJsonRequestResponse(
        RequestInterface  $request,
        ResponseInterface $response,
        bool $raw = false
    ): array
    {
        $body = $response->getBody();

        if (!$body->isReadable() || !$body->isSeekable()) {
            throw new ExactCommunicationError(
                $this,
                new ExactResponseError(
                    'Unable to read/rewind body from response',
                    $request,
                    $response
                )
            );
        }

        $body->rewind();

        $body = $body->getContents();
        try {
            return $this->decodeJson($body, $raw);
        } catch (\Throwable $e) {
            throw new ExactCommunicationError(
                $this,
                new ExactResponseError(
                    $e->getMessage(),
                    $request,
                    $response
                )
            );
        }

    }

    final protected function decodeJson(
        string $content,
        bool $raw = false
    ) : array {
        $content = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(
                'Unable to decode response body',
            );
        }

        if ($raw) {
            return $content;
        }

        if (isset($content['d'])) {
            $content = $content['d'];
        }

        if (isset($content['results'])) {
            $content = $content['results'];
        }

        return $content;
    }
}
