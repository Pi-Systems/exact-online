<?php

namespace PISystems\ExactOnline\Entity\System;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * This end point shows info related to the accountant of the customer. If the customer is an accountant himself, the service will show the info of the customer.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SystemSystemAccountantInfo
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/system/AccountantInfo", "AccountantInfo")]
#[Exact\Method(HttpMethod::GET)]
class AccountantInfo extends DataSource
{

    /**
     * The account ID of the accountant.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ID = null;

    /**
     * First address line.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AddressLine1 = null;

    /**
     * Second address line.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AddressLine2 = null;

    /**
     * Third address line.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AddressLine3 = null;

    /**
     * City.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $City = null;

    /**
     * Email.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Email = null;

    /**
     * Indicates if the customer is an accountant himself.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsAccountant = null;

    /**
     * Logo.
     *
     *
     * @var null|string Binary data, warning, do not pre-encode this data.
     */
    #[EDM\Binary]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Logo = null;

    /**
     * Url to retrieve the logo of the accountant.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $MenuLogoUrl = null;

    /**
     * The name of the accountant.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Name = null;

    /**
     * Phone.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Phone = null;

    /**
     * Postcode.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Postcode = null;

    /**
     * Website.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Website = null;
}