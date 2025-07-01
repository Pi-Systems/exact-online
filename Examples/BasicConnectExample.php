<?php

namespace PISystems;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use PISystems\ExactOnline\Events\CredentialsChange;
use PISystems\ExactOnline\Events\DivisionChange;
use PISystems\ExactOnline\ExactConnectionManager;
use PISystems\ExactOnline\Model\DirectExactAppConfiguration;
use PISystems\ExactOnline\Model\ExactAppConfigurationInterface;
use PISystems\ExactOnline\Model\OnDemandAppConfigurationLoader;
use PISystems\ExactOnline\Polyfill\ExactEventDispatcher;
use PISystems\ExactOnline\Polyfill\SimpleAbstractLogger;
use PISystems\ExactOnline\Polyfill\SimpleClosureLogger;
use PISystems\ExactOnline\Polyfill\SimpleFileCache;
use Psr\Cache\InvalidArgumentException;

/**
 * PSR Autoloading, DotEnv loading and basic sanity checking
 */
include "SetupExample.php";

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
$appConfiguration = fn() => new DirectExactAppConfiguration(
    clientId: $_ENV['EXACT_CLIENT_ID'],
    redirectUri: $_ENV['EXACT_REDIRECT_URI'],
    clientSecret: $_ENV['EXACT_CLIENT_SECRET'],
    webhookSecret: $_ENV['EXACT_WEBHOOK_SECRET'],
);

/**
 * One can pass in the result of the above closure directly,
 * Or one may choose to delay loading the sensitive stuff until it is necessary. (Recommended)
 *
 * PS: Loading .env is used in this example, and while this is a lot safer than having it be part of the call chain.
 * The $_ENV is available everywhere, and while it should never be part of a error dump, it is still globally accessible.
 * At some point the details have to be known, and as long as they are not part of any possible error dump / stack dump, I personally
 * consider it 'safe enough.
 * But perhaps one may wish to load from a database at this point.
 * (Which leads to having to have that information somewhere... credentials are amusing, ain't they.)
 *
 * @see ExactAppConfigurationInterface
 */
$appConfiguration = new OnDemandAppConfigurationLoader($appConfiguration);
$cache = new SimpleFileCache(sys_get_temp_dir() . '/exact-online-cache');
$dispatcher = new ExactEventDispatcher();
$consoleLogger = new SimpleClosureLogger(
    fn(int $level, string $message) => $level > SimpleAbstractLogger::DEBUG &&
        printf("[%s] %s\n", SimpleClosureLogger::toLogLevel($level), $message)
);
$httpFactory = new HttpFactory();
$client = new Client();

/**
 * Initialize the connection manager
 * It is recommended to use a container instead of manual initializing.
 * The above construction is about as basic as it gets.
 */
$exactConnectionFactory = new ExactConnectionManager(
    appConfiguration: $appConfiguration,
    cache: $cache,
    requestFactory: $httpFactory,
    uriFactory: $httpFactory,
    client: $client,
    logger: $consoleLogger,
    dispatcher: $dispatcher,
);

/**
 * Setup loading credentials
 */
$data = [
    // This is acquired through the oAuth loop, it is a string that usually formatted as such:
    // stamp{country}{country_code).{encrypted_token}
    // Example: stampNL0001.Hoc3ETC
    'organizationAuthorizationCode' => $_ENV['EXACT_ORGANIZATION_AUTHORIZATION_CODE'],
    // These should be retrieved using exact internal operations to get them using the above AuthorizationCode.
    // See ExactConnectionOAuth for an example.
    'organizationAccessToken' => null,
    'organizationAccessTokenExpires' => null,
    'organizationRefreshToken' => null,
    // If not initially set (Which is entirely expected, you don't know your customers division on first call).
    // Exact will figure this one out for you.
    //
    // Persisting the current division can be achieved by listening to the DivisionChange event.
    // It is not passed during a CredentialsChange event (Credentials do not change when loading a different division)
    'division' => null
];
try {
    $item = $cache->getItem('organization_authorization_data');

} catch (InvalidArgumentException $e) {
    throw new \RuntimeException('Could not read from cache ('.$e->getMessage().')');
}
if ($item->isHit()) {
    $data = array_merge($data, unserialize($item->get(), ['allowed_classes' => [\DateTimeImmutable::class]]));
}

/**
 * Setup saving credentials
 */
$save = function (array $config) use ($cache, $item, &$data) {
    $data = array_merge($data, $config);
    $item->expiresAfter(new \DateInterval('P1D'));
    $item->set(serialize($data));
    $cache->save($item);
    $cache->commit();
};

/**
 * The dispatcher handles of events.
 * However, only one event is truly crucial for proper operation:
 *
 * @see CredentialsChange
 *
 * This event is the only event that will receive one (or multiple) of the three application entries.
 * Without listening to this event, there will be no way to persist the tokens. (Without using jank)
 * The application will still work until it exits, at which point the credential data is GONE.
 *
 */
// This method is not available in EventDispatcherInterface, it is part of the ListenerProviderInterface
$dispatcher->addEventListener(CredentialsChange::class, function (CredentialsChange $event) use (
    $save
) {
    $save([
        'organizationAuthorizationCode' => $event->configuration->organizationAuthorizationCode,
        'organizationAccessToken' => $event->configuration->organizationAccessToken,
        'organizationAccessTokenExpires' => $event->configuration->organizationAccessTokenExpires,
        'organizationRefreshToken' => $event->configuration->organizationRefreshToken,
    ]);
});

/**
 * While not essential, it is highly recommended to also listen to the DivisionChange event.
 * Without this, every new construction would automatically default to calling `/system/me` before doing anything meaningful.
 * While this is not a terrible thing, it does just waste resources/time and consumes calls per minute/hour/day.
 */
// This method is not available in EventDispatcherInterface, it is part of the ListenerProviderInterface
$dispatcher->addEventListener(DivisionChange::class, function (DivisionChange $event) use (
    $save
) {
    if ($event->new === $event->current) {
        return;
    }
    $save(['division' => $event->new]);
});

$configuration = $exactConnectionFactory->createRunTimeConfiguration(
    authorizationCode: $data['organizationAuthorizationCode'],
    accessToken: $data['organizationAccessToken'],
    accessTokenExpiry: $data['organizationAccessTokenExpires'],
    refreshToken: $data['organizationRefreshToken'],
    division: $data['division']
);

/**
 * Ensure we actually have enough data to connect properly.
 * Note: This is not 100% required, one may supply the data using the
 * @see BeforeCreate event.
 *
 * This example, however, does not assume this is the case.
 * So we do a sanity/oAuth check before creating the env.
 * If we do not, the create() method below will trigger as \RuntimeException
 */
if (!$configuration->hasValidAccessToken()) {
   /**
    * Do we have an authorization token?
    * If so, good! We're done! We don't even care about the authorization code at this point.
    * The access token is all we need for communication after all.
    */
   if (!$configuration->hasAccessToken()) {
       /**
        * No valid access token, no authorization data.
        * At this point, we're not even going to bother checking for a refreshToken.
        *
        * Unfortunately, this example does not handle the oAuth dance.
        * Follow the generated link, and supply the resulting code in .env.local
        */
       $oauth = $exactConnectionFactory->generateOAuthUri(
           $configuration->clientId(),
           $configuration->redirectUri()
       );
       print  'Authorization Code Requested, please visit the below link and supply the result code in .env.local'
             . PHP_EOL
             .'WARNING: This token expires FAST, use it or lose it, there is at max, a 1-2 minute deadline once oAuth has been completed to obtain an access_token, or this token will be useless.'
             . PHP_EOL
             . PHP_EOL
             . $oauth
             . PHP_EOL
             . PHP_EOL;
       exit;
   }
}

$exact = $exactConnectionFactory->create(
    configuration: $configuration
);

print "Exact loaded and read, we're using administration/organization/division ".$exact->getAdministration();
