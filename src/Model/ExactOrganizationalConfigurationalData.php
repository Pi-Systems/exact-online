<?php

namespace PISystems\ExactOnline\Model;

use GuzzleHttp\Exception\RequestException;
use PISystems\ExactOnline\Polyfill\FormStream;
use Psr\Http\Message\RequestInterface;

readonly class ExactOrganizationalConfigurationalData
{

    /**
     * @param int|null $division
     * @param string|null $organizationAuthorizationCode
     * @param string|null $organizationAccessToken
     * @param \DateTimeInterface|null $organizationAccessTokenExpires
     * @param string|null $organizationRefreshToken
     */
    public function __construct(
        public ?int                                     $division = null,
        #[\SensitiveParameter]
        public ?string                                 $organizationAuthorizationCode = null,
        #[\SensitiveParameter]
        public ?string                                 $organizationAccessToken = null,
        #[\SensitiveParameter]
        public ?\DateTimeInterface                     $organizationAccessTokenExpires = null,
        #[\SensitiveParameter]
        public ?string                                 $organizationRefreshToken = null,
    )
    {
    }
}
