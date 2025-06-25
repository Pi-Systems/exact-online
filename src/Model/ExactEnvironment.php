<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Events\AdministrationChange;
use PISystems\ExactOnline\Events\CredentialsSaveEvent;
use PISystems\ExactOnline\Exceptions\ExactCommunicationError;
use PISystems\ExactOnline\Exceptions\ExactEmptyResponseError;
use PISystems\ExactOnline\Exceptions\ExactRequestError;
use PISystems\ExactOnline\Exceptions\ExactResponseError;
use PISystems\ExactOnline\Exceptions\ExactResponseNOKError;
use PISystems\ExactOnline\Exceptions\MissingListenerException;
use PISystems\ExactOnline\Model\Exact\System\Me;
use PISystems\ExactOnline\Polyfill\ExactEventDispatcher;
use PISystems\ExactOnline\Polyfill\FormStream;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

/*sealed*/ abstract class ExactEnvironment /*permits Exact*/
{

    public bool $treatEmptyAsError = true;
    private ?ExactRateLimits $rateLimits = null;

    public function __construct(
        public ?int                                $administration = null {
            get => $this->administration;
            set {
                if ($value !== $this->administration) {
                    $administrationSwitchEvent = new AdministrationChange($this, $this->administration, $value);
                    $this->dispatcher->dispatch($administrationSwitchEvent);
                    if ($administrationSwitchEvent->isPropagationStopped()) {
                        return;
                    }
                }
                $this->administration = $value;
            }
        },
        protected CacheItemPoolInterface           $cache,
        protected RequestFactoryInterface          $requestFactory,
        protected UriFactoryInterface              $uriFactory,
        protected ClientInterface                  $client,
        protected LoggerInterface                  $logger,
        protected ExactEventDispatcher             $dispatcher,
        #[\SensitiveParameter]
        private readonly ExactRuntimeConfiguration $configuration
    )
    {
        // Not even sure how the second test could ever not be true...
        // Reflection shenanigans, probably.
        if (null !== $this->configuration->exact && $this->configuration->exact !== $this) {
            throw new \LogicException('Refusing to attach the supplied configuration to this Exact instance, it is already coupled.');
        }
        $this->configuration->exact = $this;
        $this->configuration->dispatcher = $this->dispatcher;
        $this->dispatcher->lock();

    }

    final protected function assertEventSanity(): void
    {
        if ($this->configuration->allowSave && !$this->dispatcher->hasListenersForEvent(CredentialsSaveEvent::class)) {
            throw new MissingListenerException(CredentialsSaveEvent::class, $this);
        }
    }

    final protected function loadAdministrationData(): int
    {
        if ($this->administration) {
            return $this->administration;
        }

        $uri = $this->uriFactory->createUri(Me::ENDPOINT . '?$select=CurrentDivision');
        $request = $this->createRequest($uri);
        $response = $this->sendAuthenticatedRequest($request);
        $data = $this->decodeJsonRequestResponse($request, $response);

        $me = new Me();
        $this->hydrate($me, $data);

        if (!$me->CurrentDivision) {
            throw new ExactResponseError('Unable to determine current division.', $request, $response);
        }

        return $this->administration = (int)$me->CurrentDivision;
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
            $uri = $uri->withPath(str_replace('{division}', $this->administration, $uri->getPath()));
        }


        if ($body && $method === HttpMethod::GET) {
            // Technically they can, but this is generally not accepted by any sane server.
            throw new \InvalidArgumentException('GET requests cannot have a body.');
        }

        if (array_is_list($headers)) {
            throw new \InvalidArgumentException('Headers must be an associative array');
        }

        $request = $this->requestFactory->createRequest(
            $method->value,
            $uri
        );

        if ($body) {
            $request = $request->withBody($body);
            if ($body instanceof FormStream) {
                $request = $request
                    ->withHeader('Content-Type', 'application/x-www-form-urlencoded');
            }
        }

        $headers = array_merge([
            'Accept' => 'application/json',
            'User-Agent' => $this->configuration->userAgent,
            'X-ExactOnline-Client' => $this->configuration->clientId,
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

    final protected function configureAccessToken(): void
    {
        if ($this->configuration->hasValidAccessToken()) {
            // Nothing to configure, available token is already valid.
            return;
        }

        if (!$this->configuration->allowSave) {
            throw new \LogicException(
                'Obtaining a new access token is not allowed when allowSave is false.'
            );
        }

        $uri = $this->uriFactory->createUri($this->generateTokenAccessUrl());
        $request =
            $this->configuration->addRequestTokenData(
                $this->createRequest($uri, HttpMethod::POST)
            );

        $response = $this->sendRequest($request);

        $content = $this->decodeJsonRequestResponse($request, $response);

        $requirements = ['access_token', 'expires_in', 'refresh_token'];

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
        $expires = time() + (int)$content['expires_in'];
        $refresh = $content['refresh_token'];

        $this->configuration->setAccessToken($token, $expires);
        $this->configuration->setRefreshToken($refresh);

        $this->configuration->save();

    }

    final protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        // Do not use the accessor, as it would instantiate an empty one.
        // We really do want to check if we know our limits already.
        if (null !== $this->rateLimits) {
            if ($this->rateLimits->isRateLimited()) {

                $limitEvent = new RateLimitReached($this, $this->rateLimits);

                $this->dispatcher->dispatch($limitEvent);

                // If the event is stopped, we stop caring.
                // User dealt with it, so...
                if (!$limitEvent->isPropagationStopped()) {
                    throw new RateLimitReached(
                        $this,
                        $this->rateLimits
                    );
                }
            }
        }

        try {
            $response = $this->client->sendRequest($request);
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
        $this->getRateLimits();
        $this->rateLimits->updateFromResponse($response);
    }

    public function getRateLimits() : ExactRateLimits
    {
        return $this->rateLimits ??= ExactRateLimits::createFromLimits($this, 0, 0);
    }

    final protected function decodeJsonRequestResponse(
        RequestInterface  $request,
        ResponseInterface $response,
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
            $content = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ExactCommunicationError(
                    $this,
                    new ExactResponseError(
                        'Unable to decode response body',
                        $request,
                        $response
                    )
                );
            }

            return $content;
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

    final public function getPrimaryKey(DataSource $source) : ?string {
        return null;
    }

    final public function getPrimaryKeyName(DataSource $source) : ?string {
        return null;
    }
}