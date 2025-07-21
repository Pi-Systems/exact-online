<?php

namespace PISystems\ExactOnline\Model;

readonly class ExactOrganizationalConfigurationalData
{
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
        public ?RateLimits $limits = null
    )
    {
    }
}