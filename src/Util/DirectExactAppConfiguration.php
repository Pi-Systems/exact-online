<?php

namespace PISystems\ExactOnline\Util;

use PISystems\ExactOnline\Model\AddableStreamInterface;
use PISystems\ExactOnline\Model\ExactAppConfigurationInterface;
use PISystems\ExactOnline\Polyfill\Validation;

final readonly class DirectExactAppConfiguration implements ExactAppConfigurationInterface
{
    public function __construct(
        #[\SensitiveParameter]
        public string            $clientId,
        #[\SensitiveParameter]
        public string            $redirectUri,
        #[\SensitiveParameter]
        private readonly string           $clientSecret,
        #[\SensitiveParameter]
        private readonly string           $webhookSecret
    ) {
        if (!Validation::is_guid($this->clientId)) {
            throw new \InvalidArgumentException("Guid must be a valid GUID");
        }

        if (filter_var($this->redirectUri, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException("Redirect URI must be a valid URL");
        }
    }

    public function addClientDetails(
        AddableStreamInterface $stream,
        int                    $elements = self::CLIENT_ID | self::CLIENT_SECRET | self::CLIENT_REDIRECT_URI
    ) : AddableStreamInterface {
        $sections = [];

        if ($elements & 1) {
            $sections['client_id'] = $this->clientId;
        }

        if ($elements & 2) {
            $sections['client_secret'] = $this->clientSecret;
        }

        if ($elements & 4) {
            $sections['webhook_secret'] = $this->webhookSecret;
        }

        if ($elements & 8) {
            $sections['redirect_uri'] = $this->redirectUri;
        }

        $stream->add($sections);
        return $stream;
    }

    public function clientId(): string
    {
        return $this->clientId;
    }

    public function redirectUri(): string
    {
        return $this->redirectUri;
    }
}