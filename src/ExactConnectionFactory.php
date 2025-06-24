<?php

namespace PISystems\ExactOnline;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Events\BeforeCreate;
use PISystems\ExactOnline\Events\Created;
use PISystems\ExactOnline\Model\ExactWrappedEventDispatcher;
use PISystems\ExactOnline\Polyfill\ExactEventDispatcher;
use Psr\Cache\CacheItemPoolInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;

class ExactConnectionFactory
{
    /** @var array If in singleAdministration mode, this is a key-value pair, otherwise a list. */
    private array $instances = [];
    private readonly ExactEventDispatcher|ExactWrappedEventDispatcher $dispatcher;

    public function __construct(
        private readonly CacheItemPoolInterface    $cache,
        private readonly RequestFactoryInterface   $requestFactory,
        private readonly ClientInterface           $client,
        private readonly LoggerInterface           $logger,
        private readonly CredentialStoreInterface  $credentialStore,
        /**
         * If set, only 1 instance will be permitted per administration.
         * Create will attempt to load the existing before creating a new one.
         * If one exists, neither BeforeCreate nor Created events are called.
         */
        public readonly bool $singleAdministration = true,
        ?EventDispatcherInterface $dispatcher = null
    )
    {
        if ($dispatcher) {
            $this->dispatcher = new ExactWrappedEventDispatcher($dispatcher);
        } else {
            $this->dispatcher = new ExactEventDispatcher();
        }
    }

    /**
     * @param int|null $administration A call to `/me?currentDivision` is made.
     * @return Exact
     */
    public function create(?int $administration = null) : Exact
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
            $beforeCreateEvent->client,
            $beforeCreateEvent->logger,
            $this->dispatcher
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