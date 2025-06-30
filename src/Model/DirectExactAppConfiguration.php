<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Polyfill\FormStream;

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
    }

    public function addClientDetails(
        FormStream $form,
        int $elements = self::CLIENT_ID | self::CLIENT_SECRET | self::CLIENT_REDIRECT_URI
    ) : FormStream {
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

        $form->add($sections);
        return $form;
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
