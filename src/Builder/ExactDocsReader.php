<?php

namespace PISystems\ExactOnline\Builder;

use PISystems\ExactOnline\Model\DataSource;
use PISystems\ExactOnline\Model\DataSourceMeta;
use PISystems\ExactOnline\Model\EdmDataStructure;
use PISystems\ExactOnline\Model\ExactAttributeOverridesInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;

class ExactDocsReader
{
    const string EXACT_META_CACHE = __DIR__.'/../Model/Exact/ExactMeta.json';

    const int PAGE_SIZE_DEFAULT = 60;
    const int PAGE_SIZE_SYNC_AND_BULK = 1000;

    public bool $localOnly = false;
    public ?int $timeout = 1; // in seconds, between calls.
    public int $downloads = 0;

    public const string HOST = 'start.exactonline.nl';
    public const string RESOURCE_ENDPOINT = 'https://' . self::HOST . '/docs/';
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
        protected readonly CacheItemPoolInterface  $cache,
        protected readonly RequestFactoryInterface $requestFactory,
        protected readonly ClientInterface         $client,
        protected readonly LoggerInterface         $logger,
        // The contents of this will not expire that quickly.
        public int                        $expirationTime = 5 * 24 * 60 * 60,
        public string                     $targetDirectory = __DIR__ . '/../Model/Exact' {
            set => str_ends_with($value, '/') ? $value : $value . '/';
        },
        public string                     $dataTemplate = __DIR__ . '/../Resources/DataTemplate.phps' {
            set => !file_exists($value) || is_readable($value) ? $value : throw new \RuntimeException("File {$value} is not readable.");
        },
        public string                     $methodTemplate = __DIR__ . '/../Resources/MethodTemplate.phps' {
            set => !file_exists($value) || is_readable($value) ? $value : throw new \RuntimeException("File {$value} is not readable.");
        },
        public readonly ?ExactAttributeOverridesInterface $attributeOverrides = null
    )
    {
        if (!is_writable($this->targetDirectory)) {
            throw new \LogicException("Target directory {$this->targetDirectory} is not writable.");
        }

    }


    /**
     * @throws ClientExceptionInterface
     */
    protected function getPage(string $uri): ?\DOMDocument
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

            if ($c < 200 || $c >= 300) {
                $this->logger->critical("Failed to fetch ExactDocs page {$uri} (HTTP {$c})");
                return null;
            }

            $contents = $response->getBody()->getContents();
            $headers = $response->getHeaders();
            $item->set(json_encode(['headers' => $headers, 'content' => $contents]));
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
     * @throws ClientExceptionInterface
     * @var null|string $package If supplied, it is used to filter which pages to build. (Regex, filter is based on path)
     */
    public function build(?string $package = null): int
    {

        $count = 0;
        $main = $this->getPage('#');

        if (!$main) {
            $this->logger->emergency("Resource {#} not found. " . ($this->localOnly ? '(Local data only mode enabled, cache available? Cache set to ignore expire time?)' : '(Please check your internet connection.)'));
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
            $this->generateClassPage($endpoint, $resource, $path, $methods, $scope);
        }

        $this->writeEntityData();
        $metas = $this->buildMeta();
        file_put_contents(self::EXACT_META_CACHE, json_encode($metas), JSON_PRETTY_PRINT);
        $this->cache->commit();
        return $count;
    }

    private function generateClassPage(
        string  $endpoint,
        ?string $resource,
        string  $path,
        string  $methods,
        string  $scope
    ): void
    {
        if (str_starts_with($path, '/api/')) {
            // Dealing with a data endpoint
            $matches = [];
            preg_match('/^\/api\/v1\/(?:current|(?:beta\/|)\{division})\/(?<endpoint>.+)$/', $path, $matches);
            $link = $matches['endpoint'] ?? null;

            if (!$link) {
                // @me? Check this later
                return;
            }


            $folders = array_map('ucfirst', explode('/', $link));
            // remove the first entry, it is a repeat of the service
            $class = array_pop($folders);
            if (empty($folders)) {
                // Kind of don't have a namespace for these.
                // /me is the only one that matches this odd case at the time of writing.
                $folders[] = 'System';
            }
            $namespace = 'PISystems\\ExactOnline\\Model\Exact\\' . implode('\\', $folders);
            $folder = $this->targetDirectory . '/' . implode('/', $folders);
            $file = $folder . '/' . $class . '.php';

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

    private function writeDataEndpoint(string $file, string $class, string $namespace, string $endpoint, ?string $resource, string $path, string $methods, string $scope): void
    {
        $this->dataTemplateContent ??= file_get_contents($this->dataTemplate);

        $pageSize = self::PAGE_SIZE_DEFAULT;

        if (preg_match('#/Sync|Bulk/#', $namespace)) {
            $pageSize = self::PAGE_SIZE_SYNC_AND_BULK;
        }

        $attributes = [
            'pagesize' => 'Exact\\PageSize(' . $pageSize . ')',
            'endpoint' => 'Exact\\Endpoint("' . $path . '", "'.$endpoint.'")',
        ];


        $aMethods = array_map('trim', explode(',', $methods));
        foreach ($aMethods as $method) {
            $attributes['method::'.$method] = 'Exact\\Method(HttpMethod::' . $method . ')';
        }

        if ($this->attributeOverrides?->hasOverrides($class)) {
            $attributes = $this->attributeOverrides->override($class, $attributes);
        }

        $attribute = '';
        foreach ($attributes as $attrib) {
            $attribute .= sprintf('#[' . $attrib . ']' . PHP_EOL, $attribute);
        }


        $content = str_replace(
            [
                '{{namespace}}',
                '{{attributes}}',
                '{{class}}',
                '{{endpoint}}',
                '{{resource}}',
                '{{path}}',
                '{{methods}}',
                '{{scope}}',
                '{{properties}}',
            ],
            [
                $namespace,
                rtrim($attribute),
                $class,
                $endpoint,
                $resource ?? self::REFERENCE_RESOURCES,
                $path,
                implode(',', array_map(fn($m) => 'HttpMethod::' . strtoupper(trim($m)), explode(',', $methods))),
                $scope,
                '#{{properties}}', // Is filled on the next pass, as we retrieve the page describing this.
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
            $this->entityParseQueue[] = [$file, $resource, $aMethods, $namespace . '\\' . $class, $endpoint];
        }

    }

    private function writeEntityData(): void
    {
        static $edmMap = EdmRegistry::map();
        $methodTemplateContent = $this->methodTemplateContent ??= file_get_contents($this->methodTemplate);

        $total = count($this->entityParseQueue);
        $current = 0;
        $prev = 0;
        $toWrite = [];
        // Collect everything first
        foreach ($this->entityParseQueue as [$file, $uri, $availableMethods, $class, $endpoint]) {
            $current++;

            $percent = round(($current / $total) * 100);
            if ($percent !== $prev) {
                $this->logger->info("{$percent}% ({$current}/{$total})");
            }
            $prev = $percent;

            $this->logger->debug("Retrieving {$uri}.");

            try {
                $page = $this->getPage($uri);
            } catch (ClientExceptionInterface) {
                $this->logger->debug("Failed to retrieve {$uri}.");
                continue;
            }
            $this->logger->debug("parsing {$uri}.");


            $xpath = new \DOMXPath($page);
            $gtk = $xpath->query('//p[@id="goodToKnow"]')->item(0);
            $wh = $xpath->query('//p[@id="webHook"]')->item(0);
            $docs = array_filter([$gtk, $wh]);

            $endpointDescriptions = implode(
                PHP_EOL.PHP_EOL,
                array_map(fn(\DOMElement $element) => $element->ownerDocument->saveHTML($element), $docs)
            );

            $endpointDescriptions = strip_tags(str_replace(['<br>', '<br/>', '<br />'], PHP_EOL, $endpointDescriptions));

            if (!empty($endpointDescriptions)) {
                $lines = explode(PHP_EOL, $endpointDescriptions);
                $endpointDescriptions = PHP_EOL. " * ". trim(implode(PHP_EOL." * ", array_map('trim', $lines))) . PHP_EOL . " * ";
            }

            $table = $xpath->query('//table[@id="referencetable"]')->item(0);
            $header = $xpath->query('tr[position()=1]/th', $table);
            $columns = [];

            // The pages were not created equally
            // On quite a few pages 'junk' columns are added, such as 'Value' added twice, for 'post' and 'put'.
            // Thankfully, we don't give a shit about 'value'
            foreach ($header as $k => $col) {
                $text = preg_replace('/\W/', '', $col->textContent);
                switch ($text) {
                    // Annoying, but I get it... we don't label columns like this either.
                    // Just wish they added a data-tag or something.
                    case '':
                        $columns['checkmark'] = $k;
                        break;
                    case 'Name':
                        $columns['name'] = $k;
                        break;
                    case 'Mandatory':
                        $columns['mandatory'] = $k;
                        break;
                    case 'Type':
                        $columns['type'] = $k;
                        break;
                    case 'Description':
                        $columns['description'] = $k;
                        break;
                }
            }

            $items = $xpath->query('tr[position()>1]', $table);
            $variables = [];
            /** @var \DOMElement $item */
            foreach ($items as $item) {
                $classesString = trim($item->getAttribute('class'));
                $classes = explode(' ', $classesString);
                $cells = $item->getElementsByTagName('td');

                $required = strtolower(trim($cells[$columns['mandatory']]->textContent)) !== 'false';
                $name = trim($cells[$columns['name']]->textContent);

                $attributes = [];
                $typeAnnotation = trim($cells[$columns['type']]->textContent);
                // Slightly more tricky than I initially thought.
                // The document is slightly broken at the time of writing.
                // If there is an anchor, it can be either an EDM structure, or a navigation item.
                // If there is no anchor, it is a malformed navigation item, however the name can still be looked up.
                // If there is an anchor, and it does not start with Edm. then we have a minor issue.
                // https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=ManufacturingShopOrderMaterialPlanDetails # Calculator as an example
                // This has Exact.Web.Api.Models.Manufacturing.MaterialPlanCalculator as a linked type (Which links to... oData, which it is clearly not, it's a Guid ffs)
                //
                // We 'solve' this for now using 3 checks.
                // 1) Edm.
                // 2) Exact.Web,
                // 3) Class
                [$type, $local, $typeDescription] = null;

                if (str_starts_with($typeAnnotation, 'Edm.')) {
                    $edm = $typeAnnotation;
                    [$type, $local, $typeDescription] = $edmMap[$edm] ?? [null, 'mixed', ''];
                } elseif (str_contains($typeAnnotation, 'Exact.Web.')) {
                    // We have no damn clue, probably a string.
                    $local = 'mixed';
                    $attributes['exact_web'] = "EDM\\ExactWeb('{$typeAnnotation}')";
                    $typeDescription = "Unknown ExactWeb type {$typeAnnotation}";
                } else {
                    $ns = substr($class, strrpos($class, '\\') + 1);
                    // This will be caught during writing and turned into a proper annotation
                    $attributes['collection'] = $typeAnnotation;
                    $typeDescription = "A collection of {$ns}\\{$typeAnnotation}";
                    $local = '?array';
                }

                $input = $xpath->query('td/input', $item)->item($columns['checkmark']);
                $description = trim($cells[$columns['description']]->textContent);

                if (!empty($description)) {
                    $lines = explode(PHP_EOL, $description);
                    $description = trim(implode("     * ", array_map('trim', $lines))) . PHP_EOL . "     * ";
                }

                $isPrimaryKey = $input?->attributes->getNamedItem('data-key')?->nodeValue === 'True';

                if (!empty($type)) {
                    // Required is based on the method used, so just treat everything as optional until validation.
                    if ($local !== 'mixed') {
                        $local = 'null|' . $local;
                    }

                    if (str_starts_with($type, 'PISystems\\ExactOnline\\Builder\\Edm')) {
                        $attributes['edm'] = 'EDM\\' .
                            substr($type, strlen('PISystems\\ExactOnline\\Builder\\Edm\\'));
                    }
                }

                if ($isPrimaryKey) {
                    $required = true;
                    $attributes['key'] = 'Exact\\Key';
                }

                if ($required) {
                    $attributes['required'] = 'Exact\\Required';
                }

                // Availability calculation is a joke.
                // It's all based on the input fields.
                // Where in the browser you would have js to ... stupidly ... calculate the values into silly classes.
                // We don't have that luxury and have to pry the values from annoying attributes.
                $methods = ['GET']; // Get is a given
//
                $showGet = in_array('showget', $classes);

                if (
                    !$showGet &&
                    !in_array('hidepost', $methods, true) &&
                    in_array('POST', $availableMethods, true)
                ) {
                    $methods[] = 'POST';
                }

                if (
                    !$showGet &&
                    !in_array('hideput', $methods, true) &&
                    in_array('PUT', $availableMethods, true)
                ) {
                    $methods[] = 'PUT';
                }

                if (
                    $isPrimaryKey &&
                    in_array('DELETE', $availableMethods, true)
                ) {
                    $methods[] = 'DELETE';
                }

                foreach ($methods as $method) {
                    $attributes['method::'.$method] = 'Exact\\Method(HttpMethod::' . $method . ')';
                }

                $prop = $class. '::$'.$name;
                if ($this->attributeOverrides->hasOverrides($prop)) {
                    $attributes = $this->attributeOverrides->override($prop, $attributes);
                }

                $toWrite[$endpoint] ??= [
                    'file' => $file,
                    'class' => $class,
                    'properties' =>[],
                    'endpointDescriptions' => $endpointDescriptions
                ];

                $toWrite[$endpoint]['properties'][$name] = [
                    'local' => $local,
                    'description' => $description,
                    'typeDescription' => $typeDescription,
                    'attributes' => $attributes,
                ];
            }
        }

        foreach ($toWrite as $endpoint => $value) {
            $file = $value['file'];
            $properties = $value['properties'];

            $variables = [];
            foreach ($properties as $name => &$property) {

                if (array_key_exists('collection', $property['attributes'])) {
                    $type = $property['attributes']['collection'];
                    $fullClass =
                        $toWrite[$type]['class']
                        ?? $toWrite[$endpoint.$type]['class'] ?? null;

                    if (!$fullClass) {
                        // One more attempt, using CamelCased Namespace of the class... **sigh**
                        $ns = str_replace("PISystems\\ExactOnline\\Model\\Exact\\", '', substr($value['class'], strrpos($value['class'], '\\') + 1));
                        $ns = explode('\\', $ns);
                        $ns = array_map('ucfirst', $ns);
                        $ns = implode('\\', $ns);
                        $test = $ns.$type;
                        $fullClass = $toWrite[$test]['class'] ?? null;
                    }

                    $fullClass ??= 'DataSource';

                    $property['attributes']['collection'] = 'EDM\\Collection(\\'.$fullClass.'::class, \''.$type.'\')';
                }


                $attributeString = PHP_EOL;

                foreach ($property['attributes'] as $entry) {
                    $attributeString .= sprintf('    #[%s]' . PHP_EOL, $entry);
                }

                $variables[] = str_replace(
                    [
                        '{{name}}',
                        '{{localType}}',
                        '{{description}}',
                        '{{typeDescription}}',
                        '{{attributes}}',
                        '{{default}}',
                    ],
                    [
                        $name,
                        $property['local'],
                        $property['description'],
                        $property['typeDescription'],
                        $attributeString,
                        ' = null'
                    ],
                    $methodTemplateContent
                );
            }

            $content = file_get_contents($file);
            $content = str_replace([
                '#{{properties}}',
                '{{endpointDescriptions}}'
            ], [
                implode(PHP_EOL, $variables),
                $value['endpointDescriptions']
            ], $content);
            file_put_contents($file, $content);
            $this->logger->debug("Wrote {$uri}.");
        }
    }

    /**
     * Technically not needed to do, as the ExactEnvironment::getDataSourceMetaData does the same.
     * However. this does catch any real issues with all the above code if this goes wrong.
     * And speeds up initial runtime drastically.
     */

    protected function buildMeta() : array
    {
        $this->logger->info('Constructing meta data.');

        $metas = [];
        foreach ($this->entityParseQueue as [,,,$class]) {
            $meta = DataSourceMeta::createFromClass($class);
            $metas[$class] = serialize($meta);
        }
        return $metas;
    }
}
