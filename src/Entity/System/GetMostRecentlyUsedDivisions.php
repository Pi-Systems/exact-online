<?php

namespace PISystems\ExactOnline\Entity\System;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * The end point retrieves the top 5 most recently used companies.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SystemSystemGetMostRecentlyUsedDivisions
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/system/GetMostRecentlyUsedDivisions", "GetMostRecentlyUsedDivisions")]
#[Exact\Method(HttpMethod::GET)]
class GetMostRecentlyUsedDivisions extends DataSource
{

    /**
     * Primary key
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Code = null;

    /**
     * Address line 1
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AddressLine1 = null;

    /**
     * Address line 2
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AddressLine2 = null;

    /**
     * Address line 3
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AddressLine3 = null;

    /**
     * Date on which the division is archived
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $ArchiveDate = null;

    /**
     * Values: 0 = Not blocked, 1 = Backup/restore, 2 = Conversion busy, 3 = Conversion shadow, 4 = Conversion waiting, 5 = Copy data waiting, 6 = Copy data busy
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $BlockingStatus = null;

    /**
     * Business type code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BusinessTypeCode = null;

    /**
     * Business type description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BusinessTypeDescription = null;

    /**
     * Chamber of commerce establishment
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ChamberOfCommerceEstablishment = null;

    /**
     * Chamber of commerce number
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ChamberOfCommerceNumber = null;

    /**
     * City
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $City = null;

    /**
     * First division classification. User should have access rights to view division classifications.
     *
     *
     * @var mixed Unknown ExactWeb type Exact.Web.Api.Models.HRM.DivisionClass
     */
    #[Exact\ExactWeb('Exact.Web.Api.Models.HRM.DivisionClass')]
    #[Exact\Method(HttpMethod::GET)]
    public mixed $Class_01 = null;

    /**
     * Second division classification. User should have access rights to view division classifications.
     *
     *
     * @var mixed Unknown ExactWeb type Exact.Web.Api.Models.HRM.DivisionClass
     */
    #[Exact\ExactWeb('Exact.Web.Api.Models.HRM.DivisionClass')]
    #[Exact\Method(HttpMethod::GET)]
    public mixed $Class_02 = null;

    /**
     * Third division classification. User should have access rights to view division classifications.
     *
     *
     * @var mixed Unknown ExactWeb type Exact.Web.Api.Models.HRM.DivisionClass
     */
    #[Exact\ExactWeb('Exact.Web.Api.Models.HRM.DivisionClass')]
    #[Exact\Method(HttpMethod::GET)]
    public mixed $Class_03 = null;

    /**
     * Fourth division classification. User should have access rights to view division classifications.
     *
     *
     * @var mixed Unknown ExactWeb type Exact.Web.Api.Models.HRM.DivisionClass
     */
    #[Exact\ExactWeb('Exact.Web.Api.Models.HRM.DivisionClass')]
    #[Exact\Method(HttpMethod::GET)]
    public mixed $Class_04 = null;

    /**
     * Fifth division classification. User should have access rights to view division classifications.
     *
     *
     * @var mixed Unknown ExactWeb type Exact.Web.Api.Models.HRM.DivisionClass
     */
    #[Exact\ExactWeb('Exact.Web.Api.Models.HRM.DivisionClass')]
    #[Exact\Method(HttpMethod::GET)]
    public mixed $Class_05 = null;

    /**
     * Company size code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CompanySizeCode = null;

    /**
     * Company size description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CompanySizeDescription = null;

    /**
     * Country of the division. Is used for determination of legislation
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Country = null;

    /**
     * Creation date
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Created = null;

    /**
     * User ID of creator
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Creator = null;

    /**
     * Name of the creator
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CreatorFullName = null;

    /**
     * Default currency
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Currency = null;

    /**
     * True when this division is most recently used by the API
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $Current = null;

    /**
     * Owner account of the division
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Customer = null;

    /**
     * Owner account code of the division
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CustomerCode = null;

    /**
     * Owner account name of the division
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CustomerName = null;

    /**
     * Accountant number DATEV (Germany)
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DatevAccountantNumber = null;

    /**
     * Client number DATEV (Germany)
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DatevClientNumber = null;

    /**
     * Description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Description = null;

    /**
     * Date when the division was linked or unlinked to Exact Online HR. Please resync all data when this value changes because value of Timestamp is regenerated.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $DivisionHRLinkUnlinkDate = null;

    /**
     * Date when the division was moved. Please resync all data when this value changes because value of Timestamp is regenerated.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $DivisionMoveDate = null;

    /**
     * Email address
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Email = null;

    /**
     * Fax number
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Fax = null;

    /**
     * Company number that is assigned by the customer
     *
     *
     * @var null|int Int64
     */
    #[EDM\Int64]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Hid = null;

    /**
     * True if the division is a dossier division
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsDossierDivision = null;

    /**
     * True if the division is linked to Exact Online HR
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsHRDivision = null;

    /**
     * True if the division is the main division
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsMainDivision = null;

    /**
     * True if the division is a practice division
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsPracticeDivision = null;

    /**
     * Legislation
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Legislation = null;

    /**
     * Last modified date
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Modified = null;

    /**
     * User ID of modifier
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Modifier = null;

    /**
     * Name of the last modifier
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ModifierFullName = null;

    /**
     * The soletrader VAT number used for offical returns to tax authority
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $OBNumber = null;

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
     * Postcode
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Postcode = null;

    /**
     * SBI code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SbiCode = null;

    /**
     * SBI description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SbiDescription = null;

    /**
     * Sector code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SectorCode = null;

    /**
     * Sector description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SectorDescription = null;

    /**
     * the part of the capital of a company that comes from the issue of shares (France)
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $ShareCapital = null;

    /**
     * An INSEE code which allows the geographic identification of the company. (France)
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SiretNumber = null;

    /**
     * Date on which the division becomes active
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $StartDate = null;

    /**
     * State/Province code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $State = null;

    /**
     * Follow the Division Status 0 for Inactive, 1 for Active and 2 for Archived Divisions
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Status = null;

    /**
     * Subsector code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SubsectorCode = null;

    /**
     * Subsector description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SubsectorDescription = null;

    /**
     * Number of your local tax authority (Germany)
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $TaxOfficeNumber = null;

    /**
     * Local tax reference number (Germany)
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $TaxReferenceNumber = null;

    /**
     * Division template code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $TemplateCode = null;

    /**
     * The number under which the account is known at the Value Added Tax collection agency
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $VATNumber = null;

    /**
     * Customer value, hyperlink to external website
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Website = null;
}