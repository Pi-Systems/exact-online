<?php

namespace PISystems\ExactOnline\Entity\Sync\Manufacturing;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Any change in the ShopOrderMaterialPlans will lead to a new timestamp value. The API will return the data in the same API call.
 *
 *
 * The sync api's have the goal to keep the data between Exact Online and a 3rd party application the same.
 *
 *
 * The sync api's are all based on row versioning and because of that it is guaranteed to be unique. Every time an existing record is changed or a new record is inserted, the row versioning value is higher than the highest available value at that time. When retrieving records via these api's also a timestamp value is returned. The highest timestamp value of the records returned should be stored on client side. Next time records are retrieved, the timestamp value stored on client side should be provided as parameter. The api will then return only the new and changed records. Using this method is more reliable than using modified date, since it can happen that multiple records have the same modified date and therefore same record can be returned more than once. This will not happen when using timestamp.
 *
 *
 * The sync api's are also developed to give best performance when retrieving records. Because of performance and the intended purpose of the api's, only the timestamp field is allowed as parameter.
 *
 *
 * The single and bulk apiâs are designed for a different purpose. They provide ability to retrieve specific record or a set of records which meet certain conditions.
 *
 *
 * In case the division is moved to another database in Exact Online the timestamp values will be reset. Therefore, after a division is moved all data needs to be synchronized again in order to get the new timestamp values. To see if a division was moved, the /api/v1/{division}/system/Divisions can be used. The property DivisionMoveDate indicated at which date a division was moved and this date can be used to determine if it is needed to synchronize all data again.
 *
 *
 * The API has two important key fields, the Timestamp and the ID. The ID should be used to uniquely identify the record and will never change
 * . The Timestamp is used to get new or changed records in an efficient way and will change for every change made to the record.
 *
 *
 * The timestamp value returned has no relation with actual date or time. As such it cannot be converted to a date\time value. The timestamp is a rowversion value.
 *
 *
 * When you use the sync or delete api for the first time for a particular division, filter on timestamp greater than 1.
 *
 *
 * Or use the SyncTimestamp API to GET a timestamp from a certain date.
 *
 *
 * Note: This endpoint does not support query { $select=* } since there are a lot of properties in this endpoint.
 *
 *
 * This endpoint is available for the following packages:
 *
 * • Manufacturing (All)
 *
 *
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SyncManufacturingShopOrderMaterialPlans
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/sync/Manufacturing/ShopOrderMaterialPlans", "Manufacturing/ShopOrderMaterialPlans")]
#[Exact\Method(HttpMethod::GET)]
class ShopOrderMaterialPlans extends DataSource
{

    /**
     * Timestamp
     *
     *
     * @var null|int Int64
     */
    #[EDM\Int64]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Timestamp = null;

    /**
     * Indicates if this is a backflush step
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Backflush = null;

    /**
     * Barcode of the item (numeric string)
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Barcode = null;

    /**
     * Calculator type
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $CalculatorType = null;

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
     * Description of the material
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Description = null;

    /**
     * Detail drawing reference
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DetailDrawing = null;

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
     * Primary key
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ID = null;

    /**
     * Reference to Items table
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Item = null;

    /**
     * Item Code (material)
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemCode = null;

    /**
     * Description of Item (material)
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemDescription = null;

    /**
     * Reference to BillOfMaterialMaterials
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemMaterial = null;

    /**
     * URL of the material item's picture
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemPictureUrl = null;

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
     * Line notes
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Notes = null;

    /**
     * Planned amount in the currency of the transaction
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $PlannedAmountFC = null;

    /**
     * Date that the material is required.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $PlannedDate = null;

    /**
     * Planned price of the material
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $PlannedPriceFC = null;

    /**
     * Intended total planned quantity of the material including waste
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $PlannedQuantity = null;

    /**
     * Intended quantity of the material per unit excluding waste
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $PlannedQuantityFactor = null;

    /**
     * ID of the routing step.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $RoutingStepID = null;

    /**
     * Reference to ShopOrders table
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ShopOrder = null;

    /**
     * Line status: 5 = Draft, 10 = Open, 20 = On order, 25 = Partial, 30 = Finished
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Status = null;

    /**
     * Description of Status
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $StatusDescription = null;

    /**
     * Type: 1 = Material, 2 = By-product
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Type = null;

    /**
     * Unit
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Unit = null;

    /**
     * Unit description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $UnitDescription = null;

    /**
     * Waste percentage must be null or integer from 1 to 9999 (only available in Manufacturing Professional and Premium)
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $WastePercentage = null;
}