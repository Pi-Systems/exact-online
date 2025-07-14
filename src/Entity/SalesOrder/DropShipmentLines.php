<?php

namespace PISystems\ExactOnline\Entity\SalesOrder;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Entity\Inventory\StockBatchNumbers;
use PISystems\ExactOnline\Entity\Inventory\StockSerialNumbers;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to :
 *   Create new drop shipment lines.
 *   Read existing drop shipment lines.
 *   Update existing drop shipment lines.
 *
 * Delivery lines support trade-in lines. For more details, please refer description under properties QuantityDelivered.
 *
 * Note: DropShipmentLines may not be posted individually. They should be part of the DropShipments Post.
 * For creating drop shipment lines, it is mandatory to supply SalesOrderLineID, PurchaseOrderId and QuantityDelivered.
 *
 * For more information about the  functionality in Exact Online, see Drop Shipments - New.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SalesOrderDropShipmentLines
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/salesorder/DropShipmentLines", "DropShipmentLines")]
#[Exact\Method(HttpMethod::GET)]
#[Exact\Method(HttpMethod::POST)]
#[Exact\Method(HttpMethod::PUT)]
class DropShipmentLines extends DataSource
{

    /**
     * The unique identifier of a stock transaction for a dropshipment line.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $ID = null;

    /**
     * Collection of batch numbers
     *
     *
     * @var ?array A collection of DropShipmentLines\StockBatchNumbers
     */
    #[EDM\Collection(StockBatchNumbers::class, 'StockBatchNumbers')]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public ?array $BatchNumbers = null;

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
     * Code the customer uses for this item
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CustomerItemCode = null;

    /**
     * Date of goods delivery
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $DeliveryDate = null;

    /**
     * Description of sales order dropshipment
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Description = null;

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
     * The EntryID identifies the dropshipment. All the lines of a dropshipment have the same EntryID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $EntryID = null;

    /**
     * Reference to item
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Item = null;

    /**
     * Item code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemCode = null;

    /**
     * Description of item
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemDescription = null;

    /**
     * Line number
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $LineNumber = null;

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

    /**
     * Notes
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Notes = null;

    /**
     * Reference to purchase order
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $PurchaseOrderLineID = null;

    /**
     * Quantity delivered
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|float $QuantityDelivered = null;

    /**
     * Quantity ordered
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $QuantityOrdered = null;

    /**
     * Reference to sales order
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $SalesOrderLineID = null;

    /**
     * Sales order line number
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $SalesOrderLineNumber = null;

    /**
     * Sales order number
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $SalesOrderNumber = null;

    /**
     * Collection of serial numbers
     *
     *
     * @var ?array A collection of DropShipmentLines\StockSerialNumbers
     */
    #[EDM\Collection(StockSerialNumbers::class, 'StockSerialNumbers')]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public ?array $SerialNumbers = null;

    /**
     * Reference to tracking number
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $TrackingNumber = null;

    /**
     * Code of item unit
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Unitcode = null;
}