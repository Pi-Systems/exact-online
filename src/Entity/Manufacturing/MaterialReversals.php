<?php

namespace PISystems\ExactOnline\Entity\Manufacturing;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Entity\Inventory\StockBatchNumbers;
use PISystems\ExactOnline\Entity\Inventory\StockSerialNumbers;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * POSTing a reversal requires an original stock transaction with HasReversibleQuantity=true. Batch or serial numbers will be reversed automatically using the original stock transaction's data. This POST will reverse the entire reversible quantity from the original stock transaction. If a partial reversal has been completed in EOL's UI, this POST will reverse only the remaining quantity.Partial reversal can be done by include quantity in post bodyBatch or serial numbers will be require for serial/batch item when post with partial reversal.If the MaterialIssue or ByProductReceipt has property IsBackflush=True, then this transaction can only be reversed by reversing the originating ShopOrderReceipt Or SubOrderReceipt.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=ManufacturingMaterialReversals
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/manufacturing/MaterialReversals", "MaterialReversals")]
#[Exact\Method(HttpMethod::GET)]
#[Exact\Method(HttpMethod::POST)]
class MaterialReversals extends DataSource
{

    /**
     * ID of stock transaction related to this material issue
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ReversalStockTransactionId = null;

    /**
     * Collection of batch numbers
     *
     *
     * @var ?array A collection of MaterialReversals\StockBatchNumbers
     */
    #[EDM\Collection(StockBatchNumbers::class, 'StockBatchNumbers')]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public ?array $BatchNumbers = null;

    /**
     * ID of creating user
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CreatedBy = null;

    /**
     * Name of the creating user
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CreatedByFullName = null;

    /**
     * Date this reversal was created
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $CreatedDate = null;

    /**
     * Boolean indicating if this reversal was the result of shop order backflushing, processed during a ShopOrderReversal
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsBackflush = null;

    /**
     * Does the issue reversal's item use batch numbers
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsBatch = null;

    /**
     * Indicates if fractions (for example 0.35) are allowed for quantities of the material reversal's item
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsFractionAllowedItem = null;

    /**
     * Does the issue reversal's item use serial numbers
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsSerial = null;

    /**
     * Item reversed
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Item = null;

    /**
     * Code of item reversed
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemCode = null;

    /**
     * Description of item reversed
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemDescription = null;

    /**
     * Picture url of item issued
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemPictureUrl = null;

    /**
     * Notes logged with this reversal
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $Note = null;

    /**
     * ID of the original stock transaction, which was to be reverse
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $OriginalStockTransactionId = null;

    /**
     * Quantity of this reversal
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|float $Quantity = null;

    /**
     * Collection of serial numbers
     *
     *
     * @var ?array A collection of MaterialReversals\StockSerialNumbers
     */
    #[EDM\Collection(StockSerialNumbers::class, 'StockSerialNumbers')]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public ?array $SerialNumbers = null;

    /**
     * ID of shop order reversed from
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ShopOrder = null;

    /**
     * ID of shop order material plan
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ShopOrderMaterialPlan = null;

    /**
     * Number of shop order reversed from
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ShopOrderNumber = null;

    /**
     * ID of storage location reversed to
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $StorageLocation = null;

    /**
     * Code of storage location reversed to
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $StorageLocationCode = null;

    /**
     * Description of storage location reversed to
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $StorageLocationDescription = null;

    /**
     * Effective date of this reversal
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|\DateTimeInterface $TransactionDate = null;

    /**
     * Unit of measurement abbreviation of item reversed
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Unit = null;

    /**
     * Unit of measurement of item reversed
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $UnitDescription = null;

    /**
     * ID of warehouse reversed to
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Warehouse = null;

    /**
     * Code of warehouse reversed to
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $WarehouseCode = null;

    /**
     * Description of warehouse reversed to
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $WarehouseDescription = null;
}