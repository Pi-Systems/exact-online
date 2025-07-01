<?php
namespace PISystems;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Symfony\Component\Dotenv\Dotenv;

/**
 * There is nothing user configurable in this file, this is merely the bootstrap file for the example files.
 */

include '../vendor/autoload.php';

if (!class_exists(Dotenv::class)) {
    throw new \RuntimeException(
        "This example requires the 'DotEnv' library (Symfony/DotEnv) to be present, please install the dev env libraries."
    );
}

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

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');

if (!preg_match('/\w+_?test|dev/',$_ENV['APP_ENV'])) {
    throw new \RuntimeException('Refusing to run example in any non test/dev environment.');
}
