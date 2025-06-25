<?php

namespace PISystems\ExactOnline;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Events\BeforeCreate;
use PISystems\ExactOnline\Events\Created;
use PISystems\ExactOnline\Model\ExactRuntimeConfiguration;
use PISystems\ExactOnline\Model\ExactWrappedEventDispatcher;
use PISystems\ExactOnline\Polyfill\ExactEventDispatcher;
use PISystems\ExactOnline\Polyfill\Validation;
use Psr\Cache\CacheItemPoolInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

class ExactConnectionFactory
{
    public const string CONN_API_PROTOCOL = 'https';
    public const string CONN_API_DOMAIN = 'start.exactonline.nl';

    public const string CONN_API_PATH = '/api/v1';

    public const string CONN_API_OAUTH_PATH ='/api/oauth2/auth';

    public const string CONN_API_TOKEN_PATH = '/api/oauth2/token';


    /** @var array If in singleAdministration mode, this is a key-value pair, otherwise a list. */
    private array $instances = [];
    private readonly ExactEventDispatcher|ExactWrappedEventDispatcher $dispatcher;

    public function __construct(
        private readonly CacheItemPoolInterface    $cache,
        private readonly RequestFactoryInterface   $requestFactory,
        private readonly UriFactoryInterface      $uriFactory,
        private readonly ClientInterface           $client,
        private readonly LoggerInterface           $logger,
        /**
         * If set, only 1 instance will be permitted per administration.
         * Create will attempt to load the existing before creating a new one.
         * If one exists, neither BeforeCreate nor Created events are called.
         */
        public readonly bool $singleAdministration = true,
        /**
         * Warning: Using the base EventDispatcherInterface will allow trivial `->lock()` bypassing.
         */
        null|EventDispatcherInterface|ExactEventDispatcher $dispatcher = null,
    )
    {
        $dispatcher ??= new ExactEventDispatcher();

        if ($dispatcher instanceof ExactEventDispatcher) {
            $this->dispatcher = $dispatcher;
        } else {
            $this->dispatcher = new ExactWrappedEventDispatcher($dispatcher);
        }
    }

    public function generateOAuthUri(
        string $clientId,
        string $redirectPath,
        bool $force = false,
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
    ) : UriInterface
    {
        if (!Validation::is_guid($clientId)) {
            throw new \InvalidArgumentException("Client id must be a valid guid");       
        }

        if (!filter_var($redirectPath, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Redirect path must be a valid url");
        }

        $state ??= uniqid();

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
     * @param ExactRuntimeConfiguration $configuration
     * @param int|null $administration
     * @return Exact
     */
    public function create(
        #[\SensitiveParameter]
        ExactRuntimeConfiguration $configuration,
        ?int                      $administration = null
    ) : Exact
    {
        if ($this->singleAdministration) {
            $client = $this->findOneByAdministration($administration);
            if ($client)
            {
                return $client;
            }
            unset($client);
        }

        $beforeCreateEvent = new BeforeCreate(
            $administration,
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
            $beforeCreateEvent->administration,
            $beforeCreateEvent->cache,
            $beforeCreateEvent->requestFactory,
            $beforeCreateEvent->uriFactory,
            $beforeCreateEvent->client,
            $beforeCreateEvent->logger,
            $this->dispatcher,
            $configuration
        );

        $createdEvent = new Created($exact);

        $this->dispatcher->dispatch($createdEvent);

        if ($createdEvent->isPropagationStopped()) {
            throw new \RuntimeException("Created event was stopped.");
        }

        if ($this->singleAdministration) {
            $this->instances[$exact->administration] = $exact;
        } else {
            $this->instances[] = $exact;
        }

        return $exact;
    }

    public function instances() : \Generator
    {
        yield from $this->instances;
    }

    /**
     * If SingleAdministration is set to false, this method will return THE FIRST MATCH.
     * Thus while it will work, it can be unreliable
     *
     * @param int|null $administration
     * @return Exact|null
     */
    public function findOneByAdministration(?int $administration = null) : ?Exact
    {
        if ($this->singleAdministration) {
            return $this->instances[$administration??-1] ?? null;
        }

        $administration ??= -1;
        foreach ($this->instances() as $instance) {
            if ($instance->administration === $administration) {
                return $instance;
            }
        }
        return null;
    }

}