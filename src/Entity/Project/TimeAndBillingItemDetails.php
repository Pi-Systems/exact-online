<?php

namespace PISystems\ExactOnline\Entity\Project;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * This endpoint enables users to retrieve a list of Items with details.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=ReadProjectTimeAndBillingItemDetails
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/read/project/TimeAndBillingItemDetails", "TimeAndBillingItemDetails")]
#[Exact\Method(HttpMethod::GET)]
class TimeAndBillingItemDetails extends DataSource
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
     * The code of the item used in time and billing projects
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Code = null;

    /**
     * The description of the item used in time and billing projects
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Description = null;

    /**
     * A boolean field to indicated if this item's quantity is allowed to be used in fraction's, which changes the calculation of the total amount invoiced
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsFractionAllowedItem = null;

    /**
     * A boolean field to indicated if this item is enabled to be used in a invoice proposal
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsSalesItem = null;

    /**
     * The code of the sales currency used in this item
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SalesCurrency = null;

    /**
     * Sales price of the item which is used when processing sales invoice
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $SalesPrice = null;
}