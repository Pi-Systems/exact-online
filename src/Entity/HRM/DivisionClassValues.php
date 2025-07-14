<?php

namespace PISystems\ExactOnline\Entity\HRM;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * This end point returns the values as used per company classification for a given company.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=HRMDivisionClassValues
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/hrm/DivisionClassValues", "DivisionClassValues")]
#[Exact\Method(HttpMethod::GET)]
class DivisionClassValues extends DataSource
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
    public null|string $ID = null;

    /**
     * First classification
     *
     *
     * @var ?array A collection of DivisionClassValues\Class_01
     */
    #[EDM\Collection(DataSource::class, 'Class_01')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $Class_01 = null;

    /**
     * First classification ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_01_ID = null;

    /**
     * Second classification
     *
     *
     * @var ?array A collection of DivisionClassValues\Class_02
     */
    #[EDM\Collection(DataSource::class, 'Class_02')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $Class_02 = null;

    /**
     * Second classification ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_02_ID = null;

    /**
     * Third classification
     *
     *
     * @var ?array A collection of DivisionClassValues\Class_03
     */
    #[EDM\Collection(DataSource::class, 'Class_03')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $Class_03 = null;

    /**
     * Third classification ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_03_ID = null;

    /**
     * Fourth classification
     *
     *
     * @var ?array A collection of DivisionClassValues\Class_04
     */
    #[EDM\Collection(DataSource::class, 'Class_04')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $Class_04 = null;

    /**
     * Fourth classification ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_04_ID = null;

    /**
     * Fifth classification
     *
     *
     * @var ?array A collection of DivisionClassValues\Class_05
     */
    #[EDM\Collection(DataSource::class, 'Class_05')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $Class_05 = null;

    /**
     * Fifth classification ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_05_ID = null;

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
     * Name of creator
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CreatorFullName = null;

    /**
     * ID of customer
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Customer = null;

    /**
     * Division code
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Division = null;

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
     * Name of modifier
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ModifierFullName = null;
}