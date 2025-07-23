<?php

namespace PISystems\ExactOnline\Builder\Compiler;

use PISystems\ExactOnline\Builder\Compiler\Interfaces\DataSourceWriterInterface;
use PISystems\ExactOnline\Builder\Compiler\Interfaces\RemoteDocumentLoaderInterface;
use PISystems\ExactOnline\Builder\Edm\Collection;
use PISystems\ExactOnline\Builder\EdmRegistry;
use PISystems\ExactOnline\Builder\Endpoint;
use PISystems\ExactOnline\Builder\ExactWeb;
use PISystems\ExactOnline\Builder\Key;
use PISystems\ExactOnline\Builder\Method;
use PISystems\ExactOnline\Builder\PageSize;
use PISystems\ExactOnline\Builder\Required;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSourceMeta;
use PISystems\ExactOnline\Model\ExactAttributeOverridesInterface;
use PISystems\ExactOnline\Util\SanityCheck;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;

/**
 * Maybe migrate this to a more mana-able reader in the future.
 * This is currently only really relevant during dev.
 * May also consider using an engine to generate content instead of search_replace in a '''template''' file.
 */
class DataSourceCompiler
{
    const string EXACT_META_CACHE = __DIR__ . '/../../Entity/ExactMeta.json';

    const int PAGE_SIZE_DEFAULT = 60;
    const int PAGE_SIZE_SYNC_AND_BULK = 1000;

    public const string HOST = 'start.exactonline.nl';
    public const string RESOURCE_ENDPOINT = 'https://' . self::HOST . '/docs/';

    private array $departments = [];
    private array $globalLookup = [];

    public function __construct(
        protected readonly CacheItemPoolInterface         $cache,
        protected readonly LoggerInterface                $logger,
        protected readonly RemoteDocumentLoaderInterface  $documentLoader,
        protected readonly DataSourceWriterInterface      $writer,
        // The contents of this will not expire that quickly.
        public readonly ?ExactAttributeOverridesInterface $attributeOverrides = null,
        public string                                     $targetDirectory = __DIR__ . '/../../Entity' {
            set {
                $this->targetDirectory = str_ends_with($value, '/') ? $value : $value . '/';
                if (!is_writable($this->targetDirectory)) {
                    throw new \RuntimeException("Cannot write to destination folder.");
                }
            }
        },
    )
    {
        SanityCheck::checkEnv(static::class);
    }

    /**
     * @throws ClientExceptionInterface
     * @var null|string $service If supplied, it is used to filter which pages to build. (Regex, filter is based on path)
     *
     * This method could use some tlc.
     * However, unless the source changes, I don't really see a point in changing this.
     * This is run maybe once in a custom project, maybe during a composer install script or whenever.
     * This code can be slow(ish), it can be a bit clunky.
     * Considering it is not responsible for communication and no production code is part of this, it should be fine.
     *
     * AKA: This stuff MAKES the production code, it is not production code itself!
     */
    public function build(?string $service = null): int
    {

        $count = 0;
        $main = $this->documentLoader->getPage('#');

        if (!$main) {
            $this->logger->emergency("Resource {#} not found, loader returned empty document. (Please check your internet connection.)");

            return 0;
        }

        $xpath = new \DOMXPath($main);

        $items = $xpath->query('//html/body/form/table/tr[position()>1]');

        $this->logger->info("We found {$items->length} items");
        $fileMetas = [];
        foreach ($items as $item) {
            $cells = $item->getElementsByTagName('td');

            $department = $cells[0]->textContent;
            $this->departments[$department] ??= 0;
            $this->departments[$department]++;


            $pointRef = $xpath->query('a', $cells[1])?->item(0);
            $endpoint = trim($pointRef?->textContent);
            $resource = trim($pointRef?->attributes?->getNamedItem('href')?->nodeValue);
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

            if ($service && !preg_match($service, $department)) {
                continue;
            }
            $this->logger->debug("Building {$endpoint}\n");
            $fileMetas[] = $this->generateClassMeta($department, $endpoint, $resource, $path, $methods, $scope);
        }

        $metas = [];
        foreach ($fileMetas as $meta) {
            if (!$meta) {
                continue; // Skip empty metas, something went wrong there.
            }

            $this->writer->write($meta);
            $metas[$meta->fqcn] = DataSourceMeta::createFromClass($meta->fqcn)->toArray();
        }

        file_put_contents(self::EXACT_META_CACHE, json_encode($metas, JSON_PRETTY_PRINT));
        $this->cache->commit();
        return $count;
    }

    protected function generateClassMeta(
        string $department,
        string  $endpoint,
        ?string $resource,
        string  $path,
        string  $methods,
        string  $scope
    ): ?BuildFileMeta
    {
        if (!str_starts_with($path, '/api/')) {
            $this->logger->warning("Path {$path} does not start with /api/.");
        }

        if (!$resource) {
            throw new \RuntimeException("Resource for {$endpoint} not found.");
        }

        // Dealing with a data endpoint
        $matches = [];
        preg_match('/^\/api\/v1\/(?:current|(?:beta\/|)\{division})\/(?<endpoint>.+)$/', $path, $matches);
        $link = $matches['endpoint'] ?? null;

        if (!$link) {
            $this->logger->warning("Endpoint for {$path} not found.");
            // @me? Check this later
            return null;
        }

        $folders = [$department, ...array_map('ucfirst', explode('/', $endpoint))];
        // remove the first entry, it is a repeat of the service
        $class = array_pop($folders);
        if (empty($folders)) {
            // Kind of don't have a namespace for these.
            // /me is the only one that matches this odd case at the time of writing.
            $folders[] = 'System';
        }
        $namespace = 'PISystems\\ExactOnline\\Entity\\' . implode('\\', $folders);

        $pageSize = self::PAGE_SIZE_DEFAULT;

        if (preg_match('#/Sync|Bulk/#', $namespace)) {
            $pageSize = self::PAGE_SIZE_SYNC_AND_BULK;
        }

        $attributes = [
            'pagesize' => new PageSize($pageSize),
            'endpoint' => new Endpoint($path, $endpoint),
        ];


        $aMethods = array_map('trim', explode(',', $methods));
        foreach ($aMethods as $method) {
            $attributes['method::' . $method] = new Method(HttpMethod::tryFrom($method));
        }

        if ($this->attributeOverrides?->hasOverrides($class)) {
            $attributes = $this->attributeOverrides->override($class, $attributes);
        }

        $meta = new BuildFileMeta(
            $namespace,
            trim($department),
            $attributes,
            $namespace . '\\' . $class,
            $class,
            $endpoint,
            null,
            $resource,
            $path,
            $scope,
            array_map(fn($m) => HttpMethod::from(strtoupper(trim($m))), explode(',', $methods)),

        );

        [$items, $columns] = $this->extractClassMeta($meta);

        /** @var \DOMElement $item */
        foreach ($items as $item) {
            $this->extractPropertyMeta($meta, $item, $columns);
        }

        $this->walkPropertyAttributes($meta);

        return $meta;
    }

    protected function extractClassMeta(BuildFileMeta $meta): array
    {
        // Collect everything first

        $page = $this->documentLoader->getPage($meta->resource);
        if (!$page) {
            $this->logger->debug("Failed to retrieve {$meta->resource}.");
            throw new \RuntimeException("Failed to retrieve {$meta->resource}.");
        }

        $xpath = new \DOMXPath($page);
        $gtk = $xpath->query('//p[@id="goodToKnow"]')->item(0);
        $wh = $xpath->query('//p[@id="webHook"]')->item(0);
        $docs = array_filter([$gtk, $wh]);

        $endpointDescriptions = implode(
            PHP_EOL . PHP_EOL,
            array_map(fn(\DOMElement $element) => $element->ownerDocument->saveHTML($element), $docs)
        );

        $endpointDescriptions = strip_tags(str_replace(['<br>', '<br/>', '<br />'], PHP_EOL, $endpointDescriptions));

        if (!empty($endpointDescriptions)) {
            $lines = explode(PHP_EOL, $endpointDescriptions);
            $meta->description = implode(PHP_EOL, array_map('trim', $lines));
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

        $documentGlobalName = $meta->department . $meta->endpoint;

        $this->globalLookup[$documentGlobalName] ??= [
            'department' => $meta->department,
            'endpoint' => $meta->endpoint,
            'class' => $meta->class,
            'properties' => [],
            'endpointDescriptions' => $endpointDescriptions
        ];


        $items = $xpath->query('tr[position()>1]', $table);
        return [$items, $columns];
    }

    protected function extractPropertyMeta(BuildFileMeta $meta, \DOMElement $item, array $columns): void
    {
        static $edmMap = EdmRegistry::map();

        $xpath = new \DOMXPath($item->ownerDocument);
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
        $type = null;

        if (str_starts_with($typeAnnotation, 'Edm.')) {
            $edm = $typeAnnotation;
            [$type, $local, $typeDescription] = $edmMap[$edm] ?? [null, 'mixed', ''];
        } elseif (str_contains($typeAnnotation, 'Exact.Web.')) {
            // We have no damn clue, probably a string.
            $local = 'mixed';
            $attributes['exact_web'] = new ExactWeb($typeAnnotation);
            $typeDescription = "Unknown ExactWeb type {$typeAnnotation}";
        } else {
            $ns = substr($meta->fqcn, strrpos($meta->fqcn, '\\') + 1);
            $ts = $ns . '\\' . $typeAnnotation;
            $attributes['collection'] = $typeAnnotation;
            $typeDescription = "A collection of $ts";
            $local = '?array';
        }

        $input = $xpath->query('td/input', $item)->item($columns['checkmark']);
        $description = trim($cells[$columns['description']]->textContent);

        if (!empty($description)) {
            $lines = explode(PHP_EOL, $description);
            $description = trim(implode('', array_map('trim', $lines))) . PHP_EOL;
        }

        $isPrimaryKey = $input?->attributes->getNamedItem('data-key')?->nodeValue === 'True';

        if (!empty($type)) {
            // Required is based on the method used, so just treat everything as optional until validation.
            if ($local !== 'mixed') {
                $local = 'null|' . $local;
            }

            $attributes['edm'] = new $type();
        }

        if ($isPrimaryKey) {
            $required = true;
            $attributes['key'] = new Key();
        }

        if ($required) {
            $attributes['required'] = new Required();
        }

        // Availability calculation is a joke.
        // It's all based on the input fields.
        // Where in the browser you would have js to ... stupidly ... calculate the values into silly classes.
        // We don't have that luxury and have to pry the values from annoying attributes.
        $methods = [HttpMethod::GET]; // Get is a given
//
        $showGet = in_array('showget', $classes);

        if (
            !$showGet &&
            !in_array('hidepost', $methods, true) &&
            in_array('POST', $methods, true)
        ) {
            $methods[] = HttpMethod::POST;
        }

        if (
            !$showGet &&
            !in_array('hideput', $methods, true) &&
            in_array('PUT', $methods, true)
        ) {
            $methods[] = HttpMethod::PUT;
        }

        if (
            $isPrimaryKey &&
            in_array('DELETE', $methods, true)
        ) {
            $methods[] = HttpMethod::DELETE;
        }

        foreach ($methods as $method) {
            $attributes['method::' . $method->value] = new Method($method);
        }

        $prop = $meta->fqcn . '::$' . $name;
        if ($this->attributeOverrides->hasOverrides($prop)) {
            $attributes = $this->attributeOverrides->override($prop, $attributes);
        }

        $meta->properties[$name] = new BuildPropertyMeta(
            $meta,
            $name,
            $local,
            $description,
            $typeDescription,
            attributes: $attributes
        );
    }

    protected function walkPropertyAttributes(BuildFileMeta $meta): void
    {
        /**
         * @var BuildPropertyMeta $property
         */
        foreach ($meta->properties as $name => $property) {

            if (array_key_exists('collection', $property->attributes)) {
                $type = $property->attributes['collection'];
                $this->logger->debug('Collection requested, attempting to resolve ' . $type);
                $this->logger->debug('');
                // Try to find the class using a few tricks
                $fullClass =
                    $this->globalLookup[$type]['class'] // Find it in global-scope (This will likely fail).
                    ?? $this->globalLookup[$meta->department . $type]['class'] // Find it in the local department scope (Still likely to fail)
                    ?? null;


                // Find it by scanning over all available departments with the type attached.
                foreach (array_keys($this->departments) as $d) {
                    if (array_key_exists($d . $type, $this->globalLookup)) {
                        $fullClass = $this->globalLookup[$d . $type]['class'];
                        break;
                    }
                }

                // Still not found? Hail marry attempt.
                if (!$fullClass) {
                    // One more attempt, using CamelCased Namespace of the class... **sigh**
                    $ns = str_replace("PISystems\\ExactOnline\\Model\\Exact\\", '', substr($meta->fqcn, strrpos($meta->fqcn, '\\') + 1));
                    $ns = explode('\\', $ns);
                    $ns = array_map('ucfirst', $ns);
                    $ns = implode('\\', $ns);
                    $test = $ns . $type;
                    $fullClass = $this->globalLookup[$test]['class'] ?? null;
                }

                // We give up, just couple it to the basic DataSource and let the user deal with it.
                if ($fullClass) {
                    $fullClass = '\\' . $fullClass;
                } else {
                    $fullClass = 'DataSource';
                }

                $property->attributes['collection'] = new Collection($fullClass, $type);
            }

            $property->localType = $name;
            $property->default = 'null';
        }
    }
}