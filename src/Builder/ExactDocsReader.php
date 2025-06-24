<?php

namespace PISystems\ExactOnline\Builder;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;

class ExactDocsReader
{
    public bool $localOnly = false;
    public ?int $timeout = 1; // in seconds, between calls.
    public int $downloads = 0;

    public const string HOST = 'start.exactonline.nl';
    public const string RESOURCE_ENDPOINT = 'https://'.self::HOST.'/docs/';
    public const string REFERENCE_RESOURCES = 'HlpRestAPIResources.aspx';

    private ?string $dataTemplateContent = null;
    private ?string $methodTemplateContent = null;
    private array $entityParseQueue = [];

    public array $requestHeaders = [
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Encoding' => 'gzip, deflate',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Connection' => 'keep-alive',
        'User-Agent' => 'PISystems/ExactDocsReader/1.0 (+https://github.com/pisystems/exact-php-client)',
        'DNT' => 1,
        'Cache-Control' => 'no-cache',
        'Pragma' => 'no-cache',
        'Sec-Fetch-Dest' => 'document',
        'Sec-Fetch-Mode' => 'navigate',
        'Sec-Fetch-Site' => 'same-origin',
        'Sec-Fetch-User' => '?1',
        'Sec-GPC' => 1,
    ];

    public function __construct(
        protected CacheItemPoolInterface $cache,
        protected RequestFactoryInterface $requestFactory,
        protected ClientInterface $client,
        protected LoggerInterface $logger,
        // The contents of this will not expire that quickly.
        public int $expirationTime = 5*24*60*60,
        public string $targetDirectory = __DIR__ . '/../Model/Exact' {
            set => str_ends_with($value, '/') ? $value : $value . '/';
        },
        public string $dataTemplate = __DIR__.'/../Resources/DataTemplate.phps' {
            set => !file_exists($value) || is_readable($value) ? $value : throw new \RuntimeException("File {$value} is not readable.");
        },
        public string $methodTemplate = __DIR__.'/../Resources/MethodTemplate.phps' {
            set => !file_exists($value) || is_readable($value) ? $value : throw new \RuntimeException("File {$value} is not readable.");
        },
    ) {
        if (!is_writable($this->targetDirectory)) {
            throw new \LogicException("Target directory {$this->targetDirectory} is not writable.");
        }

    }


    /**
     * @throws ClientExceptionInterface
     */
    protected function getPage(string $uri) : ?\DOMDocument
    {
        if (empty($uri)) {
            throw new \LogicException('URI cannot be empty');
        }

        $item = null;
        $contents = null;
        $headers = null;
        try {
            $item = $this->cache->getItem($uri);
            if ($item->isHit()) {
                $c = $item->get();
                $decode = json_decode($c, true);
                $contents = $decode['content'];
                $headers = $decode['headers'];
            }
        } catch (InvalidArgumentException $e) {
            trigger_error($e->getMessage());
        }

        if (!$contents) {

            if ($this->localOnly) {
                $this->logger->alert("[FETCH] Fetching {$uri} is not allowed, local data only mode enabled.");
                return null;
            }

            if ($this->timeout && $this->downloads > 1) {
                sleep($this->timeout);
            }

            // Faking this shit is so unbelievably annoying.
            $request = $this->requestFactory->createRequest('GET', $uri);
            foreach ($this->requestHeaders as $key => $value) {
                $request = $request->withHeader($key, $value);
            }

            if ('#' !== $uri) {
                $request = $request->withHeader('Referer', self::REFERENCE_RESOURCES);
            }

            $response = $this->client->sendRequest($request);
            $c = $response->getStatusCode();

            // This should not happen too often, but beta routes tend to redirect you around.
            if ($c === 302) {
                $location = $response->getHeaderLine('Location');
                $this->logger->debug("Redirecting from {$uri}");
                $this->logger->debug("Redirecting to   {$location}");
                return $this->getPage($location);
            }

            if ($c < 200|| $c >= 300) {
                $this->logger->critical("Failed to fetch ExactDocs page {$uri} (HTTP {$c})");
                return null;
            }

            $contents = $response->getBody()->getContents();
            $headers = $response->getHeaders();
            $item->set(json_encode(['headers'=>$headers, 'content'=>$contents]));
            $item->expiresAfter($this->expirationTime);
            $this->cache->save($item);
        }

        if (!$headers) {
            $this->logger->critical("Failed to fetch ExactDocs page {$uri}");

        }

        $dom = new \DOMDocument($contents, 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->encoding = 'UTF-8';
        $dom->strictErrorChecking = false;
        $prev = libxml_use_internal_errors(true);
        $dom->loadHTML($contents);

        // Restore
        if (!$prev) {
            libxml_use_internal_errors(false);
        }
        
        return $dom;
    }

    /**
     * @var null|string $package If supplied, it is used to filter which pages to build. (Regex, filter is based on path)
     * @throws ClientExceptionInterface
     */
    public function build(?string $package = null) : int {

        $count = 0;
        $main = $this->getPage('#');

        if (!$main) {
            $this->logger->emergency("Resource {#} not found. ". ($this->localOnly ? '(Local data only mode enabled, cache available? Cache set to ignore expire time?)' : '(Please check your internet connection.)'));
            return 0;
        }

        $xpath = new \DOMXPath($main);

        $items = $xpath->query('//html/body/form/table/tr[position()>1]');

        $this->logger->info("We found {$items->length} items");
        $departments = [];
        foreach ($items as $item) {
            $cells = $item->getElementsByTagName('td');

            $department = $cells[0]->textContent;
            $departments[$department] ??= 0;
            $departments[$department]++;

            $endpoint = trim($cells[1]->textContent);
            $resource = trim($xpath->query('a', $cells[1])?->item(0)?->attributes?->getNamedItem('href')?->nodeValue);
            $path = trim($cells[2]->textContent);
            $methods = trim($cells[3]->textContent);
            $scope = trim($cells[5]->textContent);
            // Note: Endpoint is not reliable
            // See example in: BudgetScenarios (BETA)

            // Note: Path may also not be reliable
            // See example in Sync/SyncTimestamp - Function Details
            // However, it is trivial to find out if this is the case
            // Correct paths always start with ^/api/ and contain no spaces.

            if ($resource) {
                $resource = self::RESOURCE_ENDPOINT . $resource;
            } else {
                $this->logger->warning("Resource for {$endpoint} not found.");
            }

            if ($package && !preg_match($package, $path)) {
                $this->logger->debug("Skipping {$path} ({$endpoint})");
                continue;
            }
            $this->logger->debug("Generating {$endpoint}");
            $this->generateClassPage($department, $endpoint, $resource, $path, $methods, $scope);
        }

        $this->writeEntityData();

        return $count;
    }

    private function generateClassPage(
        string $department,
        string $endpoint,
        ?string $resource,
        string $path,
        string $methods,
        string $scope
    ): void
    {
        if (str_starts_with($path, '/api/')) {
            // Dealing with a data endpoint
            $matches = [];
            preg_match('/^\/api\/v1\/(?:beta\/|)\{division}\/(?<endpoint>.+)$/', $path, $matches);
            $link = $matches['endpoint'] ?? null;

            if (!$link) {
                // @me? Check this later
                return;
            }


            $folders = array_map('ucfirst', explode('/', $link));
            // remove the first entry, it is a repeat of the service
            $class = array_pop($folders);
            $namespace = 'PISystems\\ExactOnline\\Model\Exact\\'. implode('\\', $folders);
            $folder = $this->targetDirectory . '/' . implode('/', $folders);
            $file = $folder.'/'.$class . '.php';

            if (!is_dir($folder) && mkdir($folder, 0777, true) === false) {
                $this->logger->critical("Failed to create directory {$folder}, skipping.");
                return;
            }

            $this->logger->info("Registering {$link} \n `- {$methods} \n `- {$namespace}\\{$class}\n `- {$file}");
            $this->writeDataEndpoint($file, $class, $namespace, $endpoint, $resource, $path, $methods, $scope);
        }

//        if (str_contains($path, 'Function Details')) {
            // todo NYI
//        }

    }

    private function writeDataEndpoint(string $file,  string $class, string $namespace, string $endpoint, ?string $resource, string $path, string $methods, string $scope): void
    {
        $this->dataTemplateContent ??= file_get_contents($this->dataTemplate);

        $content = str_replace(
            [
                '{{namespace}}',
                '{{class}}',
                '{{endpoint}}',
                '{{resource}}',
                '{{path}}',
                '{{methods}}',
                '{{scope}}',
                '{{properties}}'
            ],
            [
                $namespace,
                $class,
                $endpoint,
                $resource ?? self::REFERENCE_RESOURCES,
                $path,
                implode(',', array_map(fn($m) => 'HttpMethod::'.strtoupper(trim($m)), explode(',',$methods))),
                $scope,
                '#{{properties}}' // Is filled on the next pass, as we retrieve the page describing this.
            ],
            $this->dataTemplateContent
        );

        $this->logger->debug("Writing to {$file}...");
        if (!is_dir(dirname($file))) {
            $this->logger->debug("Creating directory...");
            @mkdir(dirname($file), 0777, true);
        }

        file_put_contents($file, $content);
        $this->logger->debug("OK");
        $this->logger->debug("Scheduling property writing");
        if ($resource) {
            $this->entityParseQueue[] = [$file, $resource];
        }

    }

    private function writeEntityData(): void
    {
        static $edmMap = EdmRegistry::map();
        $methodTemplateContent = $this->methodTemplateContent ??= file_get_contents($this->methodTemplate);

        $total = count($this->entityParseQueue);
        $current = 0;
        $prev = 0;
        foreach ($this->entityParseQueue as [$file, $uri]) {
            $current++;

            $percent = round(($current / $total) * 100);
            if ($percent !== $prev) {
                $this->logger->info("{$percent}% ({$current}/{$total})");
            }
            $prev = $percent;

            $this->logger->debug("Retrieving {$uri}.");

            try {
                $page = $this->getPage($uri);
            } catch (ClientExceptionInterface $e) {
                $this->logger->debug("Failed to retrieve {$uri}.");
                continue;
            }
            $this->logger->debug("parsing {$uri}.");


            $xpath = new \DOMXPath($page);

            $items = $xpath->query('//html/body/form/table/tr[position()>2]');

            $variables = [];
            foreach ($items as $item) {
                $cells = $item->getElementsByTagName('td');
                $required = strtolower(trim($cells[2]->textContent)) !== 'false';
                $name = trim($cells[1]->textContent);
                $edm = trim($cells[5]->textContent);
                $description = trim($cells[6]->textContent);

                if (!empty($description)) {
                    $lines = explode(PHP_EOL, $description);
                    $description = trim(implode("     * ", array_map('trim', $lines))) . PHP_EOL ."     * ";
                }

                [$type, $local, $typeDescription] = $edmMap[$edm] ?? [null, 'mixed', ''];


                $attribute = null;
                if (!empty($type)) {
                    $attribute = $type;
                    if (str_starts_with($attribute, 'PISystems\\ExactOnline\\Builder\\Edm')) {
                        $attribute = substr($attribute, strlen('PISystems\\ExactOnline\\Builder\\Edm\\'));
                    }

                    if (!$required && $local !== 'mixed') {
                        $local = 'null|' . $local;
                    }
                    $attribute = PHP_EOL.'    #[EDM\\' . $attribute . ']';
                    if ($required) {
                        $attribute .= PHP_EOL.'    #[EDM\\Required]';
                    }
                }

                $default = $required ? '' : ' = null';

                $variables[] = str_replace(
                    [
                       '{{description}}',
                       '{{uri}}',
                       '{{localType}}',
                       '{{typeDescription}}',
                       '{{name}}',
                       '{{attributes}}',
                       '{{default}}'
                    ],[
                        $description,
                        $uri,
                        $local,
                        $typeDescription,
                        $name,
                        $attribute,
                        $default,
                    ],
                    $methodTemplateContent
                );

            }

            $content = file_get_contents($file);
            $content = str_replace('#{{properties}}', implode(PHP_EOL, $variables), $content);
            file_put_contents($file, $content);
            $this->logger->debug("Wrote {$uri}.");
        }
    }
}