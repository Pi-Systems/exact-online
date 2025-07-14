<?php

namespace PISystems\ExactOnline\Entity\Inventory;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to :
 *   Create a link between an item and a warehouse.
 *   Retrieve an existing linked item warehouse record.
 *   Update item information such as MaximumStock, ReorderPoint, SafetyStock, and more in a warehouse.
 *   Delete the link between an item and a warehouse.
 * This endpoint gives an insight on the current stock levels and look ahead to
 * the projected stock level with planned movements of stock.
 *
 * An item can be linked to a warehouse to keep inventory organised. An item can be linked to multiple warehouses. Storage locations can also
 * be specified if the warehouse supports storage locations.
 * For more information about the items by warehouses functionality in Exact Online, see Overview: Items by warehouses.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=InventoryItemWarehouses
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/inventory/ItemWarehouses", "ItemWarehouses")]
#[Exact\Method(HttpMethod::GET)]
#[Exact\Method(HttpMethod::POST)]
#[Exact\Method(HttpMethod::PUT)]
#[Exact\Method(HttpMethod::DELETE)]
class ItemWarehouses extends DataSource
{

    /**
     * A guid that is the unique identifier of the linkage between item and warehouse
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
    #[Exact\Method(HttpMethod::DELETE)]
    public null|string $ID = null;

    /**
     * Indicates the number of days for next cycle count.
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|int $CountingCycle = null;

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
     * Quantity that is currently in stock, included the stock committed to sales order
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $CurrentStock = null;

    /**
     * This is a default storage location
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $DefaultStorageLocation = null;

    /**
     * Default storage location's code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DefaultStorageLocationCode = null;

    /**
     * Default storage location's description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DefaultStorageLocationDescription = null;

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
     * Item ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Item = null;

    /**
     * Barcode of item
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemBarcode = null;

    /**
     * Code of item
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
     * Together with ItemStartDate this determines if the item is active
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $ItemEndDate = null;

    /**
     * Indicates if fractions (for example 0.35) are allowed for quantities of this item
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $ItemIsFractionAllowedItem = null;

    /**
     * Indicates if this is a stock item
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $ItemIsStockItem = null;

    /**
     * Together with ItemEndDate this determines if the item is active
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $ItemStartDate = null;

    /**
     * The standard unit code of this item
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemUnit = null;

    /**
     * Description of item's unit
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemUnitDescription = null;

    /**
     * Maximum quantity of items that you want in warehouse
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|float $MaximumStock = null;

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
     * Indicates the date for next cycle count
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $NextCountingCycle = null;

    /**
     * Order Policy options: 1-Lot for lot, 2-Fixed order quantity, 3-Min / Max, 4-Order
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|int $OrderPolicy = null;

    /**
     * Period that work together with replenishment in MRP
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|int $Period = null;

    /**
     * The quantity still open to be received based on i.e. purchase orders and assembly orders.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $PlannedStockIn = null;

    /**
     * The quantity still open to be delivered based on i.e. sales orders and assembly orders.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $PlannedStockOut = null;

    /**
     * URL of the stock planning details of this record
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PlanningDetailsUrl = null;

    /**
     * The quantity of stock projected given all planned future stock changes
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $ProjectedStock = null;

    /**
     * Quantity of items as an indication of when you need to reorder more stock for the warehouse
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|float $ReorderPoint = null;

    /**
     * Reorder quantity that work together with replenishment in MRP
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|float $ReorderQuantity = null;

    /**
     * Replenishment options: 1-Purchase, 2-Assemble, 3-Make, 4-Transfer, 5-No replenishment advice
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|int $ReplenishmentType = null;

    /**
     * The quantity in a back to back order process which is already received from the purchase order, but not yet delivered for the sales order.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $ReservedStock = null;

    /**
     * Minimum quantity of items you must have in stock
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|float $SafetyStock = null;

    /**
     * Sequence number of the item (Premium Only)
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $StorageLocationSequenceNumber = null;

    /**
     * URL pointing to details of which storage locations this ItemWarehouse's stock is located
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $StorageLocationUrl = null;

    /**
     * Warehouse ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Warehouse = null;

    /**
     * Code of warehouse
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $WarehouseCode = null;

    /**
     * Description of warehouse
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $WarehouseDescription = null;
}