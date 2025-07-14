<?php

namespace PISystems\ExactOnline\Entity\Logistics;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to read item assortments.
 *
 * Item assortments can be used to filter or to create reports.
 * For more information about the item assortments functionality in Exact Online, see Working with item assortments.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=LogisticsItemAssortment
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/logistics/ItemAssortment", "ItemAssortment")]
#[Exact\Method(HttpMethod::GET)]
class ItemAssortment extends DataSource
{

    /**
     * ID of ItemAssortment
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
     * Code of ItemAssortment
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Code = null;

    /**
     * Description of ItemAssortment
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Description = null;

    /**
     * Division
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Division = null;

    /**
     * Properties of this ItemAssortment
     *
     *
     * @var ?array A collection of ItemAssortment\Properties
     */
    #[EDM\Collection(DataSource::class, 'Properties')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $Properties = null;
}