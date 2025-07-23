<?php

namespace PISystems\ExactOnline;

use PISystems\ExactOnline\Events\BeforeCreate;
use PISystems\ExactOnline\Events\Created;
use PISystems\ExactOnline\Model\ExactAppConfigurationInterface;
use PISystems\ExactOnline\Model\RateLimits;
use PISystems\ExactOnline\Model\RuntimeConfiguration;
use PISystems\ExactOnline\Model\UuidProviderInterface;
use PISystems\ExactOnline\Polyfill\ExactEventDispatcher;
use PISystems\ExactOnline\Polyfill\Validation;
use PISystems\ExactOnline\Util\UuidProvider;
use PISystems\ExactOnline\Util\WrappedEventDispatcher;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

class ExactConnectionManager
{
    public const string CONN_API_PROTOCOL = 'https';
    public const string CONN_API_DOMAIN = 'start.exactonline.nl';

    public const string CONN_API_PATH = '/api/v1';

    public const string CONN_API_OAUTH_PATH = '/api/oauth2/auth';

    public const string CONN_API_TOKEN_PATH = '/api/oauth2/token';

    public const string USER_AGENT = 'PISystems/ExactOnline';

    private \WeakMap $instances;
    private ?UriInterface $tokenUri = null;

    public function __construct(
        public readonly ExactAppConfigurationInterface              $appConfiguration,
        public readonly CacheItemPoolInterface                      $cache,
        public readonly RequestFactoryInterface                     $requestFactory,
        public readonly UriFactoryInterface                         $uriFactory,
        public readonly ClientInterface                             $client,
        public readonly LoggerInterface                             $logger,
        public readonly WrappedEventDispatcher|ExactEventDispatcher $dispatcher = new ExactEventDispatcher(),
        public readonly UuidProviderInterface $uuidProvider = new UuidProvider(),
    )
    {
        $this->instances = new \WeakMap();
    }


    public function createRunTimeConfiguration(
        ?string             $authorizationCode,
        ?string             $accessToken,
        ?\DateTimeInterface $accessTokenExpiry,
        ?string             $refreshToken,
        ?int                $division = null,
        ?RateLimits $limits = null,
    ): RuntimeConfiguration
    {
        return new RuntimeConfiguration(
            exactAppConfiguration: $this->appConfiguration,
            division: $division,
            organizationAuthorizationCode: $authorizationCode,
            organizationAccessToken: $accessToken,
            organizationAccessTokenExpires: $accessTokenExpiry,
            organizationRefreshToken: $refreshToken,
            limits: $limits
        );
    }

    public function apiBaseUri(
        ?UriInterface $base = null,
    ): UriInterface
    {
        $base ??= $this->uriFactory->createUri();

        return $base
            ->withScheme(self::CONN_API_PROTOCOL)
            ->withHost(self::CONN_API_DOMAIN);
    }

    public function generateTokenAccessUrl(): UriInterface
    {
        // Cannot use `pageUri` the base path does not include CONN_API_PATH
        return $this->tokenUri ??= $this->uriFactory->createUri(sprintf(
            "%s://%s%s",
            self::CONN_API_PROTOCOL,
            self::CONN_API_DOMAIN,
            self::CONN_API_TOKEN_PATH,
        ));
    }

    public function generateOAuthUri(
        string  $clientId,
        string  $redirectPath,
        bool    $force = false,
        /**
         * If left blank, uniqid() will be used for some csrf protection.
         *
         * This param SHOULD be filled with something more useful.
         * Example: Adding a user id / origin.
         *
         * Note: While not specified, it is recommended to keep this param below 5000 characters.
         * This in an attempt to prevent proxies from dropping the request (General rule, max 2000 char uri's)
         * For exact specifically, this limit is 6000.
         * Most of the time, a simple guid is more than sufficient to both block CSRF but also add useful state info.
         * You don't need to add your entire user row data to this.
         *
         * @see https://support.exactonline.com/community/s/knowledge-base#All-All-DNO-Content-rest-restrictions
         */
        ?string &$state = null
    ): UriInterface
    {
        if (!Validation::is_guid($clientId)) {
            throw new \InvalidArgumentException("Client id must be a valid guid");
        }

        if (!filter_var($redirectPath, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Redirect path must be a valid url");
        }

        $state ??= uniqid();

        // Cannot use `pageUri` the base path does not include CONN_API_PATH
        return $this->uriFactory->createUri(sprintf(
            "%s://%s%s?%s",
            self::CONN_API_PROTOCOL,
            self::CONN_API_DOMAIN,
            self::CONN_API_OAUTH_PATH,
            http_build_query([
                'client_id' => $clientId,
                'redirect_uri' => $redirectPath,
                'response_type' => 'code',
                'state' => $state ?? uniqid(),
                'force_login' => $force ? 1 : 0
            ])
        ));
    }

    /**
     * @param RuntimeConfiguration $configuration
     * @param string $language
     * @return Exact
     */
    public function create(
        #[\SensitiveParameter]
        RuntimeConfiguration $configuration,
        string               $language = 'nl-NL,en;q=0.9'
    ): Exact
    {
        // There should be no more mutations in the dispatcher.
        // (Though mutations may happen in the wrapped, this is not something preventable.)
        $this->dispatcher->lock();
        return $this->instances[$configuration] ?? (function () use ($configuration, $language) {
            $beforeCreateEvent = new BeforeCreate(
                $configuration->division,
                $this->cache,
                $this->requestFactory,
                $this->uriFactory,
                $this->client,
                $this->logger
            );

            $this->dispatcher->dispatch($beforeCreateEvent);

            if ($beforeCreateEvent->isPropagationStopped()) {
                throw new \RuntimeException("BeforeCreate event was stopped.");
            }

            $exact = new Exact(
                configuration: $configuration,
                manager: $this,
                language: $language
            );

            $createdEvent = new Created($exact);

            $this->dispatcher->dispatch($createdEvent);

            if ($createdEvent->isPropagationStopped()) {
                throw new \RuntimeException("Created event was stopped.");
            }

            return $exact;
        })();
    }

    public function instances(): iterable
    {
        return new \ArrayIterator($this->instances);
    }
}