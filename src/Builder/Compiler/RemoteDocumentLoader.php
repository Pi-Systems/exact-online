<?php

namespace PISystems\ExactOnline\Builder\Compiler;

use PISystems\ExactOnline\Builder\Compiler\Interfaces\RemoteDocumentLoaderInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;

class RemoteDocumentLoader implements RemoteDocumentLoaderInterface
{
    public const string REFERENCE_RESOURCES = 'HlpRestAPIResources.aspx';

    public ?int $timeout = 1; // in seconds, between calls.
    public bool $localOnly = false;

    public int $downloads = 0;

    public function __construct(
        protected CacheItemPoolInterface           $cache,
        protected readonly RequestFactoryInterface $requestFactory,
        protected readonly LoggerInterface         $logger,
        protected readonly ClientInterface         $client,
        public int                                 $expirationTime = 5 * 24 * 60 * 60,
        public ?array                              $requestHeaders = null
    )
    {
        $this->requestHeaders = [
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
            ] + ($this->requestHeaders ?? []);
    }

    public function getPage(string $uri): ?\DOMDocument
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

            try {
                $response = $this->client->sendRequest($request);
            } catch (ClientExceptionInterface) {
                return null;
            }

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
}