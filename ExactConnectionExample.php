<?php

namespace PISystems;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use PISystems\ExactOnline\Events\AdministrationChange;
use PISystems\ExactOnline\Events\CredentialsSaveEvent;
use PISystems\ExactOnline\ExactConnectionFactory;
use PISystems\ExactOnline\Model\DirectExactAppConfiguration;
use PISystems\ExactOnline\Model\ExactAppConfigurationInterface;
use PISystems\ExactOnline\Model\ExactRuntimeConfiguration;
use PISystems\ExactOnline\Model\OnDemandAppConfigurationLoader;
use PISystems\ExactOnline\Polyfill\ExactEventDispatcher;
use PISystems\ExactOnline\Polyfill\SimpleAbstractLogger;
use PISystems\ExactOnline\Polyfill\SimpleClosureLogger;
use PISystems\ExactOnline\Polyfill\SimpleFileCache;
use Psr\Cache\InvalidArgumentException;

/**
 * This library is intended to be integratable into nearly anything that needs it.
 * Thus is follows PSR specifications everywhere.
 * It does not force the use of any one particular other library.
 * As long as the PSR standards are followed, one can use any library they want.
 * The sole exceptions would be the development entity builder, and these example/test files.
 * As during examples/test files, we kind of have to have the psr entries implemented before doing anything.
 *
 * Feel free to implement this any way you want.
 * And feel free to curse me on how 'god-damn annoying' authentication persistence was made.
 * You try explaining to a customer 'well, someone screwed up your entire administration because a crash dump exposed our credentials'.
 * #[\SensitiveParameter] is a wonderful tool, but it still won't prevent everything. (Rogue var_dump() says hi)
 * We can't prevent everything either, one can always use \Reflection to access the data.
 * But by simply not blindly passing the raw authentication data around, we at least (severely) limit the scope.
 *
 * You should not have to go through this to realize the insanity of this happening.
 * Servers can and often are mis-configured.
 * Crash traces are exposed a lot more often than you give it credit for.
 * Or someone forgot that 'just turn on env=dev for a second' could mean thousands of pages will be loading/crashing with full dev stack values.
 *
 *
 * TL;DR: Be careful with where the authorization data is pulled from, we take no responsibility for your own horrible choices.
 */

/**
 * These are only required during a dev build, or when developing using this, and you want, for some reason, actually want to run
 * this example instead of just reading it.
 */
if (!class_exists(HttpFactory::class)) {
    throw new \RuntimeException(
        "This example requires the 'http-factory' library (guzzlehttp/guzzle) to be present, please install the dev env libraries."
    );
}
if (!class_exists(Client::class)) {
    throw new \RuntimeException(
        "This example requires the 'http-client' library (guzzlehttp/guzzle) to be present, please install the dev env libraries."
    );
}

/**
 * The configuration is never exposed through straight getters or setters.
 * All properties are marked as \SensitiveParameter.
 *
 * So don't be dumb and expose them yourself.
 * Load them in with .env variables or something similarly secure.
 * Don't have them somewhere in the call stack where an error dump exposes them.
 *
 * The runtime configuration requires the app configuration to be present.
 * This library was built in such a way that, unless communication is required.
 * Nothing should require you to have this loaded.
 *
 * The entities, caching, and hydration/deflation should all be accessible without configuration.
 * Do not have this configuration on machines which only purpose is to ready data.
 * This configuration should only ever be on the final 'and now send it' machine step.
 *
 * Remember this: Exact HIGHLY DISCOURAGES multi-connection/multi-thread handling of data.
 * However, That only applies to the sending/receiving information part.
 * PROCESSING should be (And is encouraged to be) done on (a) separate machine(s).
 */
$appConfiguration = new DirectExactAppConfiguration(
    clientId: $_ENV['exact_client_id'] ?? 'c1528f1d-c566-41db-4200-000000000069',
    redirectUri: $_ENV['exact_redirect_uri'] ?? 'https://dev.mydomain.tld/oath/reply/exact-online',
    clientSecret: $_ENV['exact_client_secret'] ?? 'ObviouslyFakeSecret',
    webhookSecret: $_ENV['exact_webhook_secret'] ?? 'ObviouslyFakeWebhookSecret',
);

/**
 * One can pass in the configuration above directly, or one may choose to delay loading the sensitive day until it is necessary.
 * Such as wrapping it around the OnDemandAppConfigurationLoader.
 * As long as it implements the interface
 * @see ExactAppConfigurationInterface
 */
$appConfiguration = new OnDemandAppConfigurationLoader(fn()=> $appConfiguration);

/**
 * Setup caching.
 *
 * Note: It should not have to be said, but you can store the credentials where ever the hell you want.
 * This example is merely leveraging the already existing cache layer.
 */
$data = [
    // This should be retrieved during the oAuth steps.
    // See ExactConnectionOAuth for an example.
    'organizationAuthorizationCode' => null,
    'organizationAccessToken' => null,
    'organizationAccessTokenExpires' => null,
    'organizationRefreshToken' => null,
    'administration' => null
];
$cache = new SimpleFileCache(sys_get_temp_dir() . '/exact-online-cache/');
try {
    $item = $cache->getItem('organization_authorization_data');
} catch (InvalidArgumentException $e) {
    throw new \RuntimeException("Could not read from cache, folder permission settings? ".$e->getMessage());
}
if ($item->isHit()) {
    $data = unserialize($item->get(), ['allowed_classes' => [\DateTimeImmutable::class]]);

    if ($data['organizationAccessTokenExpires']) {
        $data['organizationAccessTokenExpires'] = \DateTimeImmutable::createFromFormat(
            $data['organizationAccessTokenExpires'],
            \DateTimeInterface::ATOM
        );
    }
}

$configuration = new ExactRuntimeConfiguration(
    exactAppConfiguration: $appConfiguration,
    organizationAuthorizationCode: $data['organizationAuthorizationCode'],
    organizationAccessToken: $data['organizationAccessToken'],
    organizationAccessTokenExpires: $data['organizationAccessTokenExpires'],
    organizationRefreshToken: $data['organizationRefreshToken'],
);

/**
 * Just a simple wrapper around the earlier created cache object
 */
$save = function (array $config) use ($cache, $item, $data) {
    $data = array_merge($data, $config);
    $item->set(serialize($data));
    $cache->save($item);
};

/**
 * The dispatcher handles of events.
 * However, only one event is truly crucial for proper operation:
 *
 * @see CredentialsSaveEvent
 *
 * This event is the only event that will receive one (or multiple) of the three application entries.
 * Without listening to this event, there will be no way to persist the tokens. (Without using jank)
 * The application will still work until it exits, at which point the credential data is GONE.
 *
 */
$dispatcher = new ExactEventDispatcher();
// This method is not available in EventDispatcherInterface, it is part of the ListenerProviderInterface
$dispatcher->addEventListener(CredentialsSaveEvent::class, function (CredentialsSaveEvent $event) use (
    $save
) {
    $save([
        'organizationAuthorizationCode' => $event->authorizationCode,
        'organizationAccessToken' => $event->accessToken,
        'organizationAccessTokenExpires' => $event->accessTokenExpires,
        'organizationRefreshToken' => $event->refreshToken,
    ]);
});

/**
 * While not essential, it is highly recommended to also listen to the administration event.
 * Without this, every new construction would automatically default to calling `/system/me` before doing anything meaningful.
 * While this is not a terrible thing, it does just waste resources/time and consumes calls per minute/hour/day.
 */
// This method is not available in EventDispatcherInterface, it is part of the ListenerProviderInterface
$dispatcher->addEventListener(AdministrationChange::class, function (AdministrationChange $event) use (
    $save
) {
    if ($event->new === $event->current) {
        return;
    }
    $save(['administration' => $event->new]);
});

/**
 * Do not copy this horrendous excuse for a logger.
 * Use *ANY* other logger, either one symfony, laravel or monolog.
 * If you need console logging, attach the ConsoleLogger from symfony/laravel!
 * This works but offers no log rotation, cleaning, sensible storing or even alerts/events.
 * It just does what the closure tells it to do, which is, printf.
 */
$consoleLogger = new SimpleClosureLogger(
    fn(int $level, string $message) => $level > SimpleAbstractLogger::DEBUG &&
        printf("[%s] %s\n", SimpleClosureLogger::toLogLevel($level), $message)
);

$httpFactory = new HttpFactory();

$exactConnectionFactory = new ExactConnectionFactory(
    cache: $cache,
    requestFactory: $httpFactory,
    uriFactory: $httpFactory,
    client: new Client(),
    logger: $consoleLogger,
    dispatcher: $dispatcher,
);

/**
 * Ensure we actually have enough data to connect properly.
 */
if (!$configuration->hasValidAccessToken()) {
   /**
    * Do we have an authorization token?
    * If we do, we can just let the library deal with it.
    * If not, we have to let oAuth do its thing.
    */
   if (!$configuration->hasAuthorizationData()) {
       /**
        * This is outside-of the scope of this library.
        * All we can do is offer the landing url.
        */

       $oauth = $exactConnectionFactory->generateOAuthUri(
           $configuration->clientId(),
           $configuration->redirectUri()
       );
       header("Location: ".$oauth);
       print "(Out of scope of this library) Redirecting to ".$oauth;
       exit;
   }
}

$exact = $exactConnectionFactory->create(
    configuration: $configuration,
    administration: $data['administration'],
);

print "Exact loaded and read, we're using administration/organization/division ".$exact->getAdministration();
