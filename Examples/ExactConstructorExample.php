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
 * The initial configuration for the connection manager.
 * This information should be easily obtainable through registering an app (even a test app) in exact.
 *
 * This example uses DotEnv for storage of this data to make this example runable without requiring code modification.
 * See the `.env' file for information.
 */
$appConfiguration = fn() => new DirectExactAppConfiguration(
    clientId: $_ENV['EXACT_CLIENT_ID'],
    redirectUri: $_ENV['EXACT_REDIRECT_URI'],
    clientSecret: $_ENV['EXACT_CLIENT_SECRET'],
    webhookSecret: $_ENV['EXACT_WEBHOOK_SECRET'],
);

/**
 * The above configuration could be used directly instead of enclosing it into a closure.
 * By wrapping it in a closure, we can defer loading the data until we actually need to.
 * This is both more performant and 'safer' as the data should not appear in any crash dumps.
 *
 * @see ExactAppConfigurationInterface
 */
$appConfiguration = new OnDemandAppConfigurationLoader($appConfiguration);

/**
 * The following entries should ideally be pulled from a shared container.
 */
$cache = new SimpleFileCache(sys_get_temp_dir() . '/exact-online-cache');
$dispatcher = new ExactEventDispatcher();
$consoleLogger = new SimpleClosureLogger(
    fn(int $level, string $message) => $level > SimpleAbstractLogger::DEBUG &&
        printf("[%s] %s\n", SimpleClosureLogger::toLogLevel($level), $message)
);
$httpFactory = new HttpFactory();
$client = new Client();

/**
 * Initialize the connection manager.
 * Reminder: All options only require their PSR interface.
 * The above entries implement these in a simplistic way, they are usable but should be replaced
 * by proper packages if at all doable.
 * See the original README file in the Recommendations section.
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
 * Setup credential persistence.
 * The example will leverage the above-created cache component to store the details.
 */
$data = [
    // This is acquired through the oAuth loop, it is a string that usually formatted as such:
    // stamp{country}{country_code).{encrypted_token}
    // Example: stampNL0001.Hoc3ETC
    //
    // This entry is volatile, the code (message) it contains is only valid for upto 1-2 minutes at best.
    // This example cannot really help in managing this properly, one would need to implement a webserver that can accept
    // the callback from exact to make this automatic.
    // For now, just be faster than 1-2 minutes in extracting said code and putting it in the `.env.local` file.
    //
    // Remember to URLDECODE the reply (And watch the sneaky &state= at the end)
    'organizationAuthorizationCode' => $_ENV['EXACT_ORGANIZATION_AUTHORIZATION_CODE'],
    //
    // These are automatically retrieved once the above organizationAuthorizationCode has been retrieved and this example code is re-run.
    //
    'organizationAccessToken' => null,
    'organizationAccessTokenExpires' => null,
    'organizationRefreshToken' => null,
    // If left null, then this will be automatically populated the first time it is needed, or when `Exact(Environment)->getAdministration()` is called.
    'division' => null
];
try {
    $item = $cache->getItem('organization_authorization_data');

} catch (InvalidArgumentException $e) {
    throw new \RuntimeException('Could not read from cache ('.$e->getMessage().')');
}
if ($item->isHit()) {
    $data = unserialize($item->get(), ['allowed_classes' => [\DateTimeImmutable::class]]);
}

/**
 * Create the function to persist any configuration data changes (Called in the below listeners)
 */
$save = function (array $config) use ($cache, $item, &$data) {
    $data = array_merge($data, $config);
    $item->expiresAfter(new \DateInterval('P1D'));
    $item->set(serialize($data));
    $cache->save($item);
    $cache->commit();
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
 * @see CredentialsChange Note: The credentials given are a read-only copy of the data at the time of the event.
 * @see DivisionChange Changing the division *WILL* influence everything in the app, do not ignore this event!
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
 * Before creating an Exact instance, we can check if we are even capable of using it.
 * The configuration itself can figure this out on its own (Provided persisting was configured properly).
 *
 * Note: It is not required to have all the data ready at this point.
 *       One may leverage the BeforeCreate event and enter the data at that point.
 *       If the event is properly handled, one could just remove these if statements entirely.
 *
 * Check if we have an active access token (Exact is ready to go, no need to do anything)
 */
if (
    !$configuration->hasValidAccessToken() &&
    /**
     * The valid access token does not check for the availability of a refresh token.
     * So the token may be invalid, but with a refresh token the library can easily get a new access code.
     */
    (!$configuration->hasAccessToken() || !$configuration->hasRefreshToken()) &&
    /**
     * No valid access token, no refresh code.
     * Do we have an authorizationCode?
     *
     * If so, it likely means the below uri was followed and the code was extracted.
     * If not, exit out while supplying the link needed to fix this.
     */
    (!$configuration->hasAuthorizationData())
){
    $oauth = $exactConnectionFactory->generateOAuthUri(
        $configuration->clientId(),
        $configuration->redirectUri()
    );
    print  'Authorization Code Requested, please visit the below link and supply the result code in .env.local'
        . PHP_EOL
        . 'WARNING: This token expires FAST, use it or lose it, there is at max, a 1-2 minute deadline once oAuth has been completed to obtain an access_token, or this token will be useless.'
        . PHP_EOL
        . PHP_EOL
        . $oauth
        . PHP_EOL
        . PHP_EOL;
    exit;
}

return $exactConnectionFactory->create(
    configuration: $configuration
);


