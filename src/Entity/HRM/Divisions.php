<?php

namespace PISystems\ExactOnline\Entity\HRM;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Returns only divisions that are accessible to the signed-in user, as configured in the user card under 'Companies: Access rights'. Accountants will only see divisions that belong to a single license (either their own or a client's), being the license that owns the division specified in the URI.
 * Please note that divisions returned are only those which the user has granted permission to.
 * Recommended alternative that is not limited to accessible divisions: /api/v1/{division}/system/AllDivisions
 * Recommended alternative that is not limited to a single license: /api/v1/{division}/system/Divisions
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=HRMDivisions
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/hrm/Divisions", "Divisions")]
#[Exact\Method(HttpMethod::GET)]
class Divisions extends DataSource
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
     * First division classification. User should have access rights to view division classifications.
     *
     *
     * @var ?array A collection of Divisions\DivisionClasses
     */
    #[EDM\Collection(DivisionClasses::class, 'DivisionClasses')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $Class_01 = null;

    /**
     * Second division classification. User should have access rights to view division classifications.
     *
     *
     * @var ?array A collection of Divisions\DivisionClasses
     */
    #[EDM\Collection(DivisionClasses::class, 'DivisionClasses')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $Class_02 = null;

    /**
     * Third division classification. User should have access rights to view division classifications.
     *
     *
     * @var ?array A collection of Divisions\DivisionClasses
     */
    #[EDM\Collection(DivisionClasses::class, 'DivisionClasses')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $Class_03 = null;

    /**
     * Fourth division classification. User should have access rights to view division classifications.
     *
     *
     * @var ?array A collection of Divisions\DivisionClasses
     */
    #[EDM\Collection(DivisionClasses::class, 'DivisionClasses')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $Class_04 = null;

    /**
     * Fifth division classification. User should have access rights to view division classifications.
     *
     *
     * @var ?array A collection of Divisions\DivisionClasses
     */
    #[EDM\Collection(DivisionClasses::class, 'DivisionClasses')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $Class_05 = null;

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
     * Description of Country
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CountryDescription = null;

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
     * Default currency of the division
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Currency = null;

    /**
     * Description of Currency
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CurrencyDescription = null;

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
     * Description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Description = null;

    /**
     * Number that customers give to the division
     *
     *
     * @var null|int Int64
     */
    #[EDM\Int64]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $HID = null;

    /**
     * True for the main (hosting) division
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $Main = null;

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
     * Siret Number of the division (France)
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
     * Regular administrations will have status 0.  Currently, the only other possibility is 'archived' (1), which means the administration is not actively used, but still needs to be accessible for the customer/accountant to meet legal obligations
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Status = null;

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
     * VAT number
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