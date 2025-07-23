<?php

namespace PISystems\ExactOnline\Model;

use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use GuzzleHttp\Psr7\Uri;
use PISystems\ExactOnline\Entity\System\Me;
use PISystems\ExactOnline\Enum\CredentialsType;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Events\ConfigurationChange;
use PISystems\ExactOnline\Events\DivisionChange;
use PISystems\ExactOnline\Events\FileUpload;
use PISystems\ExactOnline\Events\RateLimitReached;
use PISystems\ExactOnline\Events\RefreshCredentials;
use PISystems\ExactOnline\ExactConnectionManager;
use PISystems\ExactOnline\Exceptions\ExactCommunicationError;
use PISystems\ExactOnline\Exceptions\ExactResponseError;
use PISystems\ExactOnline\Exceptions\MethodNotSupported;
use PISystems\ExactOnline\Exceptions\OfflineException;
use PISystems\ExactOnline\Exceptions\RateLimitReachedException;
use PISystems\ExactOnline\Model\Expr\Criteria;
use PISystems\ExactOnline\Model\Expr\ExactVisitor;
use PISystems\ExactOnline\Polyfill\FormStream;
use PISystems\ExactOnline\Util\MetaDataLoader;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/*sealed*/// All methods in this class are final, the class itself is not.
abstract class ExactEnvironment /*permits Exact*/
{
    public bool $guardRateLimits;
    public bool $offline;

    public ?int $division = null {
        get => $this->configuration->division;
        set {
            if ($value !== $this->configuration->division) {

                $administrationSwitchEvent =
                    new DivisionChange($this, $this->division, $value);
                $this->manager->dispatcher->dispatch(
                    $administrationSwitchEvent
                );
                if ($administrationSwitchEvent->isPropagationStopped()) {
                    return;
                }
            }
            $this->configuration->division = $value;
        }
    }

    final public function __construct(
        #[\SensitiveParameter]
        private readonly RuntimeConfiguration $configuration,
        protected ExactConnectionManager      $manager,
        public string                 $language = 'nl-NL,en;q=0.9',
        /**
         * The default sleep handler to call whenever $guardRateLimits is active and a sleep event is requested.
         * This is only used if the call itself has no sleep handler.
         */
        public ?SleepHandlerInterface $sleepHandler = null,
        /**
         * Guard against the rate limit exception (Only the minute one, the daily limit will still throw)
         *
         * WARNING: This *WILL* lock up your application for several minutes and even up-to 83-minutes at most.
         * At which point, no matter what, the error will be thrown that the daily rate limit has been reached.
         *
         * This method works only if using sendRequest.
         * Note: The application will still run the RateLimitReached event.
         * If that events propagation is halted, the application will not figure out it should interrupt before sending.
         * Thus, likely triggering a ClientException during the call.
         *
         * This is purposely not done through the event system, as we want this to be available per call.
         * Making this an event trigger would introduce nasty 'is this the request I just made?' shenanigans.
         * The sleep handler for a call that syncs the crm and a call that sends an invoice may have very different handlers.
         *
         * If left null, this option will be enabled automatically should php be running in cli mode.
         */
        ?bool                         $guardRateLimits = null,
        /**
         * Should exact start in offline mode?
         * (Any request to sendRequest/_sendRequest will fail)
         */
        bool                          $startInOffline = false
    )
    {
        $this->guardRateLimits ??= $guardRateLimits ?? (php_sapi_name() == 'cli');
        $this->offline = $startInOffline;
    }

    final public function isAuthorized(): bool
    {
        $configuration = $this->configuration;

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
        return (
            $configuration->hasValidAccessToken() ||
            /**
             * The valid access token does not check for the availability of a refresh token.
             * So the token may be invalid, but with a refresh token the library can easily get a new access code.
             */
            ($configuration->hasAccessToken() ||
                $configuration->hasRefreshToken()) ||
            /**
             * No valid access token, no refresh code.
             * Do we have an authorizationCode?
             *
             * If so, it likely means the below uri was followed and the code was extracted.
             * If not, exit out while supplying the link needed to fix this.
             */
            $configuration->hasAuthorizationData()
        );
    }

    final public function hasTokens(): bool
    {
        return $this->configuration->hasAccessToken() ||
            $this->configuration->hasRefreshToken();
    }

    final public function oAuthUri(): UriInterface
    {
        return $this->manager->generateOAuthUri(
            $this->configuration->clientId(),
            $this->configuration->redirectUri()
        );
    }

    /**
     * Set this with the code retrieved during the oAuth loop.
     * This *MUST* already be urldecoded.
     * It *MUST NOT* contain anything other than the code.
     *
     * @param string $code
     *
     * @return $this
     */
    final public function setOrganizationAuthorizationCode(
        string $code
    ): static
    {
        $this->configuration->organizationAuthorizationCode = $code;

        return $this;
    }

    /**
     * Determines with internal logic if the file is allowed to be uploaded.
     *
     * Note: This method's execution can be influenced by listening to the `FileUpload` event.
     */
    final public function fileUploadAllowed(
        string|\SplFileInfo $file,
        ?string             &$denyReason = null
    ): bool
    {
        if (is_string($file)) {
            if (!file_exists($file)) {
                $denyReason = "File {$file} does not exist.";

                return false;
            }
            if (!is_readable($file)) {
                $denyReason = "File {$file} is not readable.";

                return false;
            }

            $file = new \SplFileInfo($file);
        }

        $this->manager->dispatcher->dispatch(
            $event = new FileUpload($this, $file)
        );

        if ($event->isPropagationStopped()) {
            $denyReason = $event->denyReason;

            return false;
        }

        return true;
    }

    /**
     * Turns a Criteria into a usable URL for all methods.
     */
    final public function criteriaToUri(
        DataSourceMeta $meta,
        null|Criteria|DoctrineCriteria $criteria = null,
    ): Uri
    {
        if (!$criteria instanceof Criteria) {
            $criteria = Criteria::fromDoctrine($criteria);
        }

        if (!$meta->supports(HttpMethod::GET)) {
            throw new MethodNotSupported($this, $meta->name, HttpMethod::GET);
        }

        $uri = $this->getUri($meta);

        $criteria ??= Criteria::create();
        $visitor = new ExactVisitor($meta);
        $expression = $criteria->getWhereExpression();
        $filter = ($expression) ? $visitor->dispatch($expression) : null;

        if (!empty($filter)) {
            $query = ['$filter=' . $filter];
        }

        if (!empty($filter) && !empty($criteria->expansion)) {
            throw new \LogicException(
                "Cannot use a \$filter expression while also trying to expand a selection."
            );
        }

        $selection = $criteria->selection;
        if (!empty($selection)) {
            $selection = implode(',', $selection);
            $query[] = '$select=' . $selection;
        }

        $expand = $criteria->expansion;
        if (!empty($expand)) {
            $expand = implode(',', $expand);
            $query[] = '$expand=' . $expand;
        }

        $orderings = $criteria->orderings();
        if (!empty($orderings)) {
            if (count($orderings) > 1) {
                throw new \RuntimeException(
                    "Multiple orderings are only supported on oData4+",
                );
            }
            $query[] =
                sprintf('$orderby=%s %s', $orderings[0][0], $orderings[0][1]);
        }

        if ($criteria->inlineCount) {
            $query[] = '$inlineCount=allpages';
        }

        if ($criteria->skipToken &&
            $criteria->allowSkipVariable &&
            $criteria->getFirstResult()) {
            throw new \LogicException(
            // How would this even work?
            // Do you skip the amount first, then skip to token possibly missing?
            // Or do you skip to token, then offset the amount, making it volatile?
                "Setting both 'skipToken' and 'firstResult' is not supported."
            );
        }

        if ($max = $criteria->getMaxResults()) {
            if (empty($criteria->selection)) {
                throw new \LogicException(
                    "Cannot set a max without a selection present."
                );
            }

            if ($max < 1) {
                throw new \LogicException(
                    "Cannot have a negative number of selections."
                );
            }

            $query[] = '$top=' . $max;
        }

        if ($criteria->skipToken) {
            $query[] = '$skipToken=' . $criteria->skipToken;
        }

        if ($criteria->allowSkipVariable && $criteria->getFirstResult()) {
            $query[] = '$skip=' . $criteria->getFirstResult();
        }

        if (!empty($query)) {
            $uri = $uri->withQuery(implode('&', $query));
        }

        return $uri;
    }

    /**
     * Removes accessToken, refreshToken and authorizationCode.
     *
     * This does _NOT_ delete client data (ClientID, ClientSecret and/or WebHookSecret)!
     *
     * @return bool
     */
    final public function logout(): bool
    {
        $this->configuration->organizationAccessToken = null;
        $this->configuration->organizationRefreshToken = null;
        $this->configuration->organizationAuthorizationCode = null;
        $this->configuration->organizationAccessTokenExpires = null;

        return $this->saveConfiguration();
    }

    final public function uuid(): string
    {
        return $this->manager->uuidProvider->uuid();
    }

    /**
     * Matching() is not part of the env class, so we can't do `->matching(Criteria...)`
     */
    final protected function loadAdministrationData(bool $cache = true): int
    {
        if ($cache && $this->division) {
            return $this->division;
        }

        if ($this->offline) {
            return 0;
        }

        $meta = Me::meta();
        $uri = $this
            ->getUri($meta)
            ->withQuery('$select=CurrentDivision');

        $request = $this->createRequest($uri);
        $response = $this->sendAuthenticatedRequest($request);
        $content = $this->decodeResponseToJson($request, $response);
        $data = $this->getDataFromRawData($content);

        /** @var Me $me */
        $me = $meta->hydrate(reset($data));

        if (!$me->CurrentDivision) {
            throw new ExactResponseError(
                'Unable to determine current division.', $request, $response
            );
        }

        return $this->division = (int)$me->CurrentDivision;
    }

    /**
     * Sends a request, while ensuring the `AccessToken` is available.
     */
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

    /**
     * Deals with ensuring the `AccessToken` is available.
     * If a token is present and is valid, use it.
     * If the token is present but no longer valid, attempt refreshing with `RefreshToken`.
     * If neither the `AccessToken` nor the `RefreshToken` are present,
     * attempt to create the token with the `AuthorizationData`.
     *
     * If NONE of the options are present, throw `ExactCommunication` and give up.
     *
     * For transparency, This method also calls `saveConfiguration`.
     */
    final public function configureAccessToken(): void
    {
        if ($this->configuration->hasValidAccessToken()) {
            // Nothing to configure, available token is already valid.
            return;
        }

        $uri = $this->manager->uriFactory->createUri(
            $this->manager->generateTokenAccessUrl()
        );

        $request =
            $this->configuration->addRequestTokenData(
            // Note to self: new FormStream is not optional.
            // CreateRequest checks for instanceof RequestAwareStreamInterface
                $this->createRequest($uri, HttpMethod::POST, new FormStream())
            );

        $now = time();
        $response = $this->sendRequest($request);
        $data = $this->decodeResponseToJson($request, $response);
        $content = $this->getDataFromRawData($data);

        $requirements = ['access_token', 'expires_in', 'refresh_token'];

        if (isset($content['error'])) {
            if (isset($content['error_description'])) {
                throw new ExactCommunicationError(
                    $this,
                    new ExactResponseError(
                        sprintf(
                            '[%s] %s',
                            $content['error'],
                            $content['error_description']
                        ),
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
        $expires =
            \DateTimeImmutable::createFromTimestamp(
                $now + (int)$content['expires_in']
            );
        $refresh = $content['refresh_token'];

        $this->setOrganizationAccessToken($token, $expires);
        $this->setOrganizationRefreshToken($refresh);

        $this->saveConfiguration();
    }

    final protected function createRequest(
        UriInterface      &$uri,
        string|HttpMethod $method = HttpMethod::GET,
        ?StreamInterface  $body = null,
        array             $headers = [],
    ): RequestInterface
    {

        if (!$method instanceof HttpMethod) {
            $method = HttpMethod::tryFrom($method);

            if (!$method) {
                throw new \InvalidArgumentException(
                    'Invalid HTTP method supplied.'
                );
            }
        }

        // Ensure we update the division/administration path.
        if (str_contains($uri->getPath(), '{division}')) {
            $uri =
                $uri->withPath(
                    str_replace('{division}', $this->division, $uri->getPath())
                );
        }

        if ($body && $method === HttpMethod::GET) {
            // Technically they can, but this is generally not accepted by any sane server.
            throw new \InvalidArgumentException(
                'GET requests cannot have a body.'
            );
        }

        if (!empty($headers) && array_is_list($headers)) {
            throw new \InvalidArgumentException(
                'Headers must be an associative array'
            );
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
            'Accept-Language' => $this->language,
        ], $headers);

        foreach ($headers as $key => $value) {
            $request = $request->withHeader($key, $value);
        }

        return $request;
    }

    public static function createRateLimitGuardedCallback(
        self     $exact,
        \Closure $callback,
        /**
         * A handler one may register to handle the sleep timer.
         * Or to just do things when we want to call sleep.
         * The return value is used for the timeout (if set, recalculated if return is null)
         *
         * Note: Once this returns, the sleep() call will be executed (After timeout recalculation).
         */
        null|\Closure|SleepHandlerInterface $sleepHandler = null,
        /**
         * How many times are we allowed to sleep?
         * This is only really useful if previous warnings are ignored and multiple instances are running simultaneously.
         * This should otherwise never be required.
         *
         * Warning: Every attempt is 1 minute is 1 minute
         * Warning: Only numbers above 0 make sense, 0 is treated as 1.
         */
        int $limit = 5000,

    ): \Closure
    {
        return function () use ($callback, $sleepHandler, $limit, $exact) {
            $limit = max(abs($limit), 1);

            $response = null;
            $attempts = 0;
            do {
                try {
                    $response = $callback() ?? null; // Linters sometimes...
                } catch (RateLimitReachedException $exception) {
                    // Do not catch if it's the daily limit.
                    $limits = $exception->event->dailyLimits;
                    if ($limits->isDailyLimited()) {
                        throw $exception;
                    }

                    $timeout =
                        time() - $limits->minuteResetTime->getTimestamp();

                    if ($sleepHandler) {
                        if ($sleepHandler instanceof \Closure) {
                            $sleepHandler =
                                new CallbackSleepHandler($sleepHandler);
                        }

                        $timeout =
                            $sleepHandler->sleep(
                                $timeout,
                                $attempts,
                                $request,
                                $limits
                            ) ??
                            time() - $limits->minuteResetTime->getTimestamp();
                    }

                    if ($timeout > 0) {
                        try {
                            do {
                                $timeout = sleep($timeout);

                                if ($timeout === 192 &&
                                    PHP_OS_FAMILY === 'Windows') {
                                    // Sigh
                                    $timeout = 0;
                                }
                            } while ($timeout > 0);
                        } catch (\ValueError) {
                        }
                    }
                }
            } while (null === $response && $attempts++ < $limit);

            if ($attempts > $limit) {
                throw new ExactCommunicationError(
                    $exact,
                    "Exceeded configured (await) rate limit of {$limit} attempts."
                );
            }

            return $response;
        };
    }

    /**
     * This only guards against the minute rate limit error.
     * The daily will still throw an error.
     *
     * Note: Ensure the {division} tags are already resolved before using this.
     * @throws ExactCommunicationError|RateLimitReachedException
     */
    public function sendRateLimitGuardedRequest(
        RequestInterface                    $request,
        /**
         * A handler one may register to handle the sleep timer.
         * Or to just do things when we want to call sleep.
         * The return value is used for the timeout (if set, recalculated if return is null)
         *
         * Note: Once this returns, the sleep() call will be executed (After timeout recalculation).
         */
        null|\Closure|SleepHandlerInterface $sleepHandler = null,
        /**
         * How many times are we allowed to sleep?
         * This is only really useful if previous warnings are ignored and multiple instances are running simultaneously.
         * This should otherwise never be required.
         *
         * Warning: Every attempt is 1 minute is 1 minute
         * Warning: Only numbers above 0 make sense, 0 is treated as 1.
         */
        int $limit = 1,
    ): ResponseInterface
    {
        return self::createRateLimitGuardedCallback(
            $this,
            fn() => $this->_sendRequest($request),
            $sleepHandler,
            $limit
        )();
    }

    /**
     * @throws ExactCommunicationError|RateLimitReachedException
     */
    final protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        // In case something manages to sneak by, there shouldn't be any, but better to be safe.
        $path = $request->getUri()->getPath();
        if (
            str_contains($path, '{division}') ||
            str_contains($path, '%7Bdivision%7D')
        ) {
            throw new \RuntimeException(
                "URI Path still contains division parameters, please resolve before calling sendRequest."
            );
        }

        if ($this->offline) {
            throw new OfflineException($this, $request);
        }

        if ($this->guardRateLimits) {
            return $this->sendRateLimitGuardedRequest($request);
        }

        return $this->_sendRequest($request);
    }

    /**
     * @throws ExactCommunicationError|RateLimitReachedException
     */
    private function _sendRequest(RequestInterface $request): ResponseInterface
    {
        $limits = $this->configuration->limits ??= RateLimits::createFromDefaults();
        // Do not use the accessor, as it would instantiate an empty one.
        // We really do want to check if we know our limits already.
        if ($this->configuration->limits->isRateLimited()) {

            $limitEvent =
                new RateLimitReached($this, $this->configuration->limits);

            $this->manager->dispatcher->dispatch($limitEvent);

            // If the event is stopped, we stop caring.
            // User dealt with it, so...
            if (!$limitEvent->isPropagationStopped()) {
                // Not stopped, it was allowed to cascade, throw as exception
                throw new RateLimitReachedException($this, $limitEvent);
            }
        }

        try {
            $response = $this->manager->client->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new ExactCommunicationError($this, $e);
        }

        $this->updateRateLimits($response);

        return $response;
    }

    final public function getUri(
        DataSource|DataSourceMeta|string $source,
        /**
         * If disabled, {division} will NOT be hydrated.
         * This *SHOULD* cause the URI to contain '%7Bdivision%7D'.
         * as part of its url instead of the division number if it required it.
         */
        bool                             $hydrateDivision = true
    ): Uri
    {
        $source = MetaDataLoader::meta($source);

        $endpoint = $source->endpoint;
        if ($hydrateDivision && str_contains($endpoint, '{division}')) {
            $endpoint = str_replace('{division}', $this->division, $endpoint);
        }

        return $this->manager->apiBaseUri()->withPath($endpoint);
    }

    final protected function updateRateLimits(ResponseInterface $response): void
    {
        $this->configuration->limits ??= RateLimits::createFromDefaults();
        $this->configuration->limits->updateFromResponse($response);
    }

    final protected function decodeResponseToJson(
        RequestInterface  $request,
        ResponseInterface $response,
        bool $raw = false,
    ): array
    {
        $body = $response->getBody()->getContents();

        if (empty($body)) {
            return [];
        }

        try {
            $result = json_decode($body, !$raw);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException(
                    'Unable to decode response body',
                );
            }

            return $result;
        } catch (\Throwable $e) {
            throw new ExactCommunicationError(
                $this,
                new ExactResponseError(
                    $e->getMessage() . ' (' . $request->getUri() . ')',
                    $request,
                    $response
                )
            );
        }

    }

    final protected function getDataFromRawData(
        array $content
    ): array
    {
        if (isset($content['error'])) {
            if (isset($content['error_description'])) {
                throw new ExactCommunicationError(
                    $this,
                    sprintf(
                        '[%s] %s',
                        $content['error'],
                        $content['error_description']
                    ),
                );

            }
            $message = $content['error']['message'] ?? null;
            if (is_array($message)) {
                $message = $message['value'] ?? json_encode($message);
            }
            throw new ExactCommunicationError(
                $this,
                $message,
            );
        }

        if (isset($content['d'])) {
            $content = $content['d'];
        }

        if (isset($content['results'])) {
            $content = $content['results'];
        }

        return $content;
    }

    /**
     * Allows one to set the token manually, bypassing the initial configuration step during construction.
     * Not recommended to use, one should provide this data during construction.
     */
    final public function setOrganizationAccessToken(
        string             $accessToken,
        \DateTimeInterface $expires
    ): static
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

    /**
     * Allows one to set the token manually, bypassing the initial configuration step during construction.
     * Not recommended to use, one should provide this data during construction.
     */
    final public function setOrganizationRefreshToken(
        string $organizationRefreshToken
    ): static
    {
        if (!$this->configuration->hasAccessToken()) {
            // This is a complete lie, we could, but we're not enabling this stupid behavior.
            throw new \LogicException(
                'Cannot set refresh token without an access token present.'
            );
        }

        $e = new RefreshCredentials($this, CredentialsType::RefreshToken);
        $this->manager->dispatcher->dispatch($e);

        if ($e->isPropagationStopped()) {
            return $this;
        }

        $this->configuration->organizationRefreshToken =
            $organizationRefreshToken;

        return $this;
    }

    final public function saveConfiguration(): bool
    {
        $e = new ConfigurationChange(
            $this,
            $this->configuration->toOrganizationData(),
        );

        $this->manager->dispatcher->dispatch($e);

        if ($e->isPropagationStopped()) {
            return false;
        }

        return $e->isSaveSuccess();
    }

}