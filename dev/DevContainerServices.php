<?php

namespace PISystems;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use PISystems\ExactOnline\Command\BuildCommand;
use PISystems\ExactOnline\Events\ConfigurationChange;
use PISystems\ExactOnline\Events\DivisionChange;
use PISystems\ExactOnline\Events\EventDispatcherUnloading;
use PISystems\ExactOnline\Exact;
use PISystems\ExactOnline\ExactConnectionManager;
use PISystems\ExactOnline\Model\ExactAppConfigurationInterface;
use PISystems\ExactOnline\Model\RateLimits;
use PISystems\ExactOnline\Polyfill\ExactEventDispatcher;
use PISystems\ExactOnline\Polyfill\SimpleAbstractLogger;
use PISystems\ExactOnline\Polyfill\SimpleClosureLogger;
use PISystems\ExactOnline\Polyfill\SimpleFileCache;
use PISystems\ExactOnline\Util\DirectExactAppConfiguration;
use PISystems\ExactOnline\Util\OnDemandAppConfigurationLoader;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;

/**
 * This is a really dumbed-down version of a service configuration loader.
 * [
 *      service => [
 *          priority,
 *          WakeUpClosure,
 *          Unload/ShutDownClosure,
 *          options => [
 *              'tags'=>string[],
 *              'args' => serviceId[]
 *          ]
 * ]
 * No named keys on service level.
 * Named keys within options.
 */
return [
    // Primary configuration for exact.
    // Could be loaded later (As long as it is before Exact::class)
    // Keeping it on top for clarity.
    ExactAppConfigurationInterface::class => [
        110,
        function () {
            $direct = new DirectExactAppConfiguration(
                clientId: $_ENV['EXACT_CLIENT_ID'] ?? throw new \RuntimeException("Cannot load EXACT_CLIENT_ID from \$_END, is \DotEnv loaded?"),
                redirectUri: $_ENV['EXACT_REDIRECT_URI'] ?? throw new \RuntimeException("Cannot load EXACT_REDIRECT_URI from \$_END, is \DotEnv loaded?"),
                clientSecret: $_ENV['EXACT_CLIENT_SECRET'] ?? throw new \RuntimeException("Cannot load EXACT_CLIENT_SECRET from \$_END, is \DotEnv loaded?"),
                webhookSecret: $_ENV['EXACT_WEBHOOK_SECRET'] ?? throw new \RuntimeException("Cannot load EXACT_WEBHOOK_SECRET from \$_END, is \DotEnv loaded?"),
            );

            return new OnDemandAppConfigurationLoader(fn() => $direct);
        },
    ],
    /**
     * HttpFactory satisfies both RequestFactoryInterface and UriFactoryInterface
     * Don't want two of these in memory, so we just register it here.
     */
    HttpFactory::class => [100, fn() => new HttpFactory(), null, []],
    /**
     * Just use a simple file-based cache structure.
     * Ensure we commit at the end to close out any cache writings.
     */
    CacheItemPoolInterface::class => [
        100,
        fn() => new SimpleFileCache(sys_get_temp_dir() . '/exact-online-cache'),
        fn(SimpleFileCache $cache) => $cache->commit(),
    ],
    /**
     * PSR Client served by Guzzle client
     */
    ClientInterface::class => [
        90,
        fn() => new Client(),
    ],
    /**
     * Event dispatcher served by internal event dispatcher.
     */
    EventDispatcherInterface::class => [
        90,
        fn() => new ExactEventDispatcher(),
        fn(ExactEventDispatcher $dispatcher) => $dispatcher->dispatch(new EventDispatcherUnloading())
    ],
    /**
     * Logger interface served by callback logic.
     */
    LoggerInterface::class => [
        90,
        fn() => new SimpleClosureLogger(
            function (int $level, string $message) {
                $code = function (int $level) {

                    if ($level >= SimpleAbstractLogger::ERROR) {
                        return "\033[1;31m";
                    }
                    if ($level >= SimpleAbstractLogger::WARNING) {
                        return "\033[4;33m";
                    }
                    if (
                        $level >= SimpleAbstractLogger::INFO &&
                        $level <= SimpleAbstractLogger::NOTICE
                    ) {
                        return "\033[0;0m";
                    }

                    if ($level <= SimpleAbstractLogger::DEBUG) {
                        return "\033[0;36m";
                    }

                    return "\033[0m";
                };

                $doColour =
                    !isset($_ENV['COLOR_CODES']) ||
                    $_ENV['COLOR_CODES'];
                $prefix = $doColour ? $code($level) : '';
                $suffix = $doColour ? "\033[0\n" : "\n";
                printf("{$prefix}[%s] %s {$suffix}", SimpleClosureLogger::toLogLevel($level), $message);
            }),
        // Easily solvable using __destruct, but a lot of loggers require this to be called... don't ask why, I got no clue.
        fn(LoggerInterface $logger) => method_exists($logger, 'flush') && $logger->flush(),
    ],
    /**
     * RequestFactory served by HttpFactory
     */
    RequestFactoryInterface::class => [
        90,
        fn(HttpFactory $factory) => $factory,
        null,
        ['args' => [HttpFactory::class]]],
    /**
     * UriFactory served by HttpFactory
     */
    UriFactoryInterface::class => [
        90,
        fn(HttpFactory $factory) => $factory,
        null,
        [
            'args' => [HttpFactory::class]
        ]
    ],
    /**
     * Exact connection manager
     * (This is where autowire would have been nice, o well)
     */
    ExactConnectionManager::class => [
        80,
        fn(
            ExactAppConfigurationInterface $appConfiguration,
            CacheItemPoolInterface         $cache,
            RequestFactoryInterface        $requestFactory,
            UriFactoryInterface            $uriFactory,
            ClientInterface                $client,
            LoggerInterface                $logger,
            EventDispatcherInterface       $dispatcher,
        ) => new ExactConnectionManager(
            $appConfiguration,
            $cache,
            $requestFactory,
            $uriFactory,
            $client,
            $logger,
            ExactEventDispatcher::fromEventDispatcher($dispatcher),
        ),
        null, [
            'args' => [
                ExactAppConfigurationInterface::class,
                CacheItemPoolInterface::class,
                RequestFactoryInterface::class,
                UriFactoryInterface::class,
                ClientInterface::class,
                LoggerInterface::class,
                EventDispatcherInterface::class,
            ]
        ]
    ],
    /**
     * And the whole reason behind everything above.
     * This could be sliced down more (Such as moving the save handler to a service).
     * However, during dev/example running, we don't need/want a more complex structure.
     * Going any further defeats the purpose.
     */
    Exact::class => [
        20,
        function (
            ExactConnectionManager $manager,
        ) {
            /**
             * Setup credential persistence.
             * The example will leverage the above-created cache component to store the details.
             */
            $data = [
                // These are automatically retrieved once the above organizationAuthorizationCode has been retrieved and this example code is re-run.
                //
                'organizationAccessToken' => null,
                'organizationAccessTokenExpires' => null,
                'organizationRefreshToken' => null,
                // If left null, then this will be automatically populated the first time it is needed, or when `Exact(Environment)->getAdministration()` is called.
                'division' => null,
                'limits' => []
            ];
            try {
                $item = $manager->cache->getItem('organization_authorization_data');

            } catch (InvalidArgumentException $e) {
                throw new \RuntimeException('Could not read from cache (' . $e->getMessage() . ')');
            }
            if ($item->isHit()) {
                $data = unserialize($item->get(), ['allowed_classes' => [\DateTimeImmutable::class]]);
            }

            /**
             * Create the function to persist any configuration data changes (Called in the below listeners)
             */
            $save = function (array $config) use ($manager, $item, &$data) {
                $data = array_merge($data, $config);
                $item->expiresAfter(new \DateInterval('P1D'));
                $item->set(serialize($data));
                $manager->cache->save($item);
                $manager->cache->commit();
            };

            /**
             * To persist any changes to the credentials, one must collect this information from the library.
             * Unfortunately, the library privates the RuntimeConfiguration and locks `saveConfiguration` behind a protected entry.
             * If desired, as the Exact class itself is not 'final' one may extend it and call it manually, but that that point you're on your own.
             *
             * This means one cannot collect this information normally (Not without abusing reflection or similar shenanigans).
             * Instead, the library expects one to listen to 2 events that relate to the configuration of the environment.
             *
             * These events will allow one to set up persistence.
             *
             * @see ConfigurationChange Note: The credentials given are a read-only copy of the data at the time of the event.
             * @see DivisionChange Changing the division *WILL* influence everything in the app, do not ignore this event!
             */
            // This method is not available in EventDispatcherInterface, it is part of the ListenerProviderInterface
            $manager->dispatcher->addEventListener(ConfigurationChange::class, function (ConfigurationChange $event) use (
                $save
            ) {
                $save([
                    'organizationAccessToken' => $event->configuration->organizationAccessToken,
                    'organizationAccessTokenExpires' => $event->configuration->organizationAccessTokenExpires,
                    'organizationRefreshToken' => $event->configuration->organizationRefreshToken,
                    'limits' => $event->configuration->limits?->toArray() ?? null
                ]);
            });
            $manager->dispatcher->addEventListener(DivisionChange::class, function (DivisionChange $event) use (
                $save
            ) {
                if ($event->new === $event->current) {
                    return;
                }
                $save(['division' => $event->new]);
            });

            $configuration = $manager->createRunTimeConfiguration(
                authorizationCode: null,
                accessToken: $data['organizationAccessToken'],
                accessTokenExpiry: $data['organizationAccessTokenExpires'],
                refreshToken: $data['organizationRefreshToken'],
                division: $data['division'],
                limits: RateLimits::createFromArray($data['limits'])
            );

            return $manager->create(
                configuration: $configuration,

            );
        },
        null,
        [
            'args' => [
                ExactConnectionManager::class
            ]
        ]
    ],
    // We're only registering commands ending with ExactCommand.php
    // These will always only take ExactConnectionManager as their second option
    // Their name should be part of their own constructor
    ...(function () {
        // Poor men auto-registry, no need for anything complex
        $folder = __DIR__ . '/../src/Command';

        $commands = [];
        foreach (glob($folder . '/*Command.php') as $file) {
            $name = basename($file);
            $class = 'PISystems\\ExactOnline\\Command\\' . substr($name, 0, -4); // - .php

            $commands[$class] = [10, fn(Exact $manager) => new $class($manager), null, [
                'args' => [Exact::class],
                'tags' => ['console.command']
            ]];
        }
        return $commands;
    })(),
    BuildCommand::class => [
        10,
        fn(LoggerInterface $logger) => new BuildCommand($logger),
        null,
        [
            'args' => [
                LoggerInterface::class
            ],
            'tags' => ['console.command']
        ],
    ],
];