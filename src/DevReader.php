<?php

namespace PISystems\ExactOnline;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use PISystems\ExactOnline\Builder\ExactDocsReader;
use PISystems\ExactOnline\Polyfill\SimpleClosureLogger;
use PISystems\ExactOnline\Polyfill\SimpleFileCache;
use Psr\Http\Client\ClientExceptionInterface;

require('../vendor/autoload.php');

class DevReader
{
    const int TTL = 60 * 60 * 24 * 30;

    public function __invoke() : int
    {
        $cache = new SimpleFileCache(__DIR__.'/Resources/ExactDocumentationCache', defaultTtl: self::TTL);
        $cache->ignoreTimeout = true;

        $reader = new ExactDocsReader(
            $cache,
            new HttpFactory(),
            new Client(),
            new SimpleClosureLogger(
                fn(int $level, string $message) => $level > SimpleClosureLogger::DEBUG && printf("[%s] %s\n", SimpleClosureLogger::toLogLevel($level), $message)
            ),
            self::TTL
        );

        $reader->localOnly = true;

        try {
//            $reader->build('/.*\/read\/.*/');
            $reader->build();
        } catch (ClientExceptionInterface $e) {
            print $e->getMessage();
        }
        return 1;
    }
}

$reader = new DevReader();
$reader();