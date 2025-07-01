<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Polyfill\JsonDataStream;

/**
 * Be careful with this information if this is leaked, not only will the app be compromised.
 *
 * >> But __ALL THE ADMINISTRATIONS THAT THIS APP HAS ACCESS TO__ <<
 */
interface ExactAppConfigurationInterface
{
    public const int CLIENT_ID = 1;
    public const int CLIENT_SECRET = 2;
    public const int CLIENT_WEBHOOK = 4;
    public const int CLIENT_REDIRECT_URI = 8;

    public function addClientDetails(
        AddableStreamInterface $stream,
        int            $elements = self::CLIENT_ID | self::CLIENT_SECRET | self::CLIENT_REDIRECT_URI
    ) : AddableStreamInterface;

    public function clientId() : string;

    public function redirectUri() : string;
}
