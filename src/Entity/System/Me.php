<?php

namespace PISystems\ExactOnline\Entity\System;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * This end point retrieves information about the current user.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SystemSystemMe
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/current/Me", "Me")]
#[Exact\Method(HttpMethod::GET)]
class Me extends DataSource
{

    /**
     * Primary key
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $UserID = null;

    /**
     * Accounting division number
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $AccountingDivision = null;

    /**
     * Division number that is currently used in the API. You should use a division number in the url
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $CurrentDivision = null;

    /**
     * Account code of the logged in user.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CustomerCode = null;

    /**
     * Owner account of the division
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DivisionCustomer = null;

    /**
     * Owner account code of the division
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DivisionCustomerCode = null;

    /**
     * Owner account name of the division
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DivisionCustomerName = null;

    /**
     * Owner account SIRET Number of the division for French legislation
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DivisionCustomerSiretNumber = null;

    /**
     * Owner account VAT Number of the division
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DivisionCustomerVatNumber = null;

    /**
     * Dossier division number (optional)
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $DossierDivision = null;

    /**
     * Email address of the user
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Email = null;

    /**
     * Employee ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $EmployeeID = null;

    /**
     * First name
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FirstName = null;

    /**
     * Full name of the user
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FullName = null;

    /**
     * Gender: M=Male, V=Female, O=Unknown
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Gender = null;

    /**
     * Initials
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Initials = null;

    /**
     * Client user of an accountant: either a portal user or a non-accountant user with his own license (internal use)
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsClientUser = null;

    /**
     * Employee user with limited access and specific start page
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsEmployeeSelfServiceUser = null;

    /**
     * MyFirm lite user of accountant with limited access and specific start page (internal use)
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsMyFirmLiteUser = null;

    /**
     * MyFirm user of accountant with limited access and specific start page (internal use)
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsMyFirmPortalUser = null;

    /**
     * Determines whether one exact identity migration is mandatory for the user. True - User does have to migrate, False - User does not have to migrate
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsOEIMigrationMandatory = null;

    /**
     * Starter user with limited access and specific start page (internal use)
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsStarterUser = null;

    /**
     * Language spoken by this user
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Language = null;

    /**
     * Language (culture) that is used in Exact Online
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $LanguageCode = null;

    /**
     * Last name
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $LastName = null;

    /**
     * Legislation
     *
     *
     * @var null|int Int64
     */
    #[EDM\Int64]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Legislation = null;

    /**
     * Middle name
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $MiddleName = null;

    /**
     * Mobile phone
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Mobile = null;

    /**
     * Nationality
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Nationality = null;

    /**
     * Package code used in the customers license
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PackageCode = null;

    /**
     * Phone number
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Phone = null;

    /**
     * Phone number extension
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PhoneExtension = null;

    /**
     * Url that can be used to retrieve the picture of the user
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PictureUrl = null;

    /**
     * The current date and time in Exact Online
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ServerTime = null;

    /**
     * The time difference with UTC in seconds
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $ServerUtcOffset = null;

    /**
     * Binary thumbnail picture of this user (This property will never return value and will be removed in the near future.)
     *
     *
     * @var null|string Binary data, warning, do not pre-encode this data.
     */
    #[EDM\Binary]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ThumbnailPicture = null;

    /**
     * File type of the picture (This property will never return value and will be removed in the near future.)
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ThumbnailPictureFormat = null;

    /**
     * Title
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Title = null;

    /**
     * Login name of the user. If the user logs in with One Exact Identity, the login name is in the email address field
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $UserName = null;
}