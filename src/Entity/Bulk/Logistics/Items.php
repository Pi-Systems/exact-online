<?php

namespace PISystems\ExactOnline\Entity\Bulk\Logistics;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * This bulk service has a page size of 1000.
 *
 *
 * It is mandatory to provide the $select query option with a selection of one or more of the resource properties.
 *
 *
 *
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=BulkLogisticsItems
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/bulk/Logistics/Items", "Logistics/Items")]
#[Exact\Method(HttpMethod::GET)]
class Items extends DataSource
{

    /**
     * A guid that is the unique identifier of the item
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
     * Assembly lead days
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $AssembledLeadDays = null;

    /**
     * The current average cost price
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $AverageCost = null;

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
     * Batch quantity
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $BatchQuantity = null;

    /**
     * Item class code referring to ItemClasses with ClassID 1
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_01 = null;

    /**
     * Item class code referring to ItemClasses with ClassID 2
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_02 = null;

    /**
     * Item class code referring to ItemClasses with ClassID 3
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_03 = null;

    /**
     * Item class code referring to ItemClasses with ClassID 4
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_04 = null;

    /**
     * Item class code referring to ItemClasses with ClassID 5
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_05 = null;

    /**
     * Item class code referring to ItemClasses with ClassID 6
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_06 = null;

    /**
     * Item class code referring to ItemClasses with ClassID 7
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_07 = null;

    /**
     * Item class code referring to ItemClasses with ClassID 8
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_08 = null;

    /**
     * Item class code referring to ItemClasses with ClassID 9
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_09 = null;

    /**
     * Item class code referring to ItemClasses with ClassID 10
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Class_10 = null;

    /**
     * Item code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Code = null;

    /**
     * Copy sales remarks to sales lines
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $CopyRemarks = null;

    /**
     * The currency of the current and proposed cost price
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CostPriceCurrency = null;

    /**
     * Proposed cost price
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $CostPriceNew = null;

    /**
     * The current standard cost price
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $CostPriceStandard = null;

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
     * Custom field endpoint. Provided only for the Exact Online Premium users.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CustomField = null;

    /**
     * Description of the item
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
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
     * Together with StartDate this determines if the item is active
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $EndDate = null;

    /**
     * Extra description text, slightly longer than the regular description (255 instead of 60)
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ExtraDescription = null;

    /**
     * Free boolean field 1
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $FreeBoolField_01 = null;

    /**
     * Free boolean field 2
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $FreeBoolField_02 = null;

    /**
     * Free boolean field 3
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $FreeBoolField_03 = null;

    /**
     * Free boolean field 4
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $FreeBoolField_04 = null;

    /**
     * Free boolean field 5
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $FreeBoolField_05 = null;

    /**
     * Free date field 1
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $FreeDateField_01 = null;

    /**
     * Free date field 2
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $FreeDateField_02 = null;

    /**
     * Free date field 3
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $FreeDateField_03 = null;

    /**
     * Free date field 4
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $FreeDateField_04 = null;

    /**
     * Free date field 5
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $FreeDateField_05 = null;

    /**
     * Free numeric field 1
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $FreeNumberField_01 = null;

    /**
     * Free numeric field 2
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $FreeNumberField_02 = null;

    /**
     * Free numeric field 3
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $FreeNumberField_03 = null;

    /**
     * Free numeric field 4
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $FreeNumberField_04 = null;

    /**
     * Free numeric field 5
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $FreeNumberField_05 = null;

    /**
     * Free numeric field 6
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $FreeNumberField_06 = null;

    /**
     * Free numeric field 7
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $FreeNumberField_07 = null;

    /**
     * Free numeric field 8
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $FreeNumberField_08 = null;

    /**
     * Free text field 1
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FreeTextField_01 = null;

    /**
     * Free text field 2
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FreeTextField_02 = null;

    /**
     * Free text field 3
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FreeTextField_03 = null;

    /**
     * Free text field 4
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FreeTextField_04 = null;

    /**
     * Free text field 5
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FreeTextField_05 = null;

    /**
     * Free text field 6
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FreeTextField_06 = null;

    /**
     * Free text field 7
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FreeTextField_07 = null;

    /**
     * Free text field 8
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FreeTextField_08 = null;

    /**
     * Free text field 9
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FreeTextField_09 = null;

    /**
     * Free text field 10
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FreeTextField_10 = null;

    /**
     * GL account the cost entries will be booked on. This overrules the GL account from the item group. If the license contains 'Intuit integration' this property overrides the value in Settings, not the item group.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLCosts = null;

    /**
     * Code of GL account for costs
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLCostsCode = null;

    /**
     * Description of GLCosts
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLCostsDescription = null;

    /**
     * GL account the work in progress project cost entries will be booked on. This overrules the GL account from the item group.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLCostsWorkInProgress = null;

    /**
     * Code of GL account for costs Work In Progress
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLCostsWorkInProgressCode = null;

    /**
     * Description of GLCosts Work In Progress
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLCostsWorkInProgressDescription = null;

    /**
     * GL account the revenue will be booked on. This overrules the GL account from the item group. If the license contains 'Intuit integration' this property overrides the value in Settings, not the item group.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLRevenue = null;

    /**
     * Code of GLRevenue
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLRevenueCode = null;

    /**
     * Description of GLRevenue
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLRevenueDescription = null;

    /**
     * GL account the work in progress project revenue entries will be booked on. This overrules the GL account from the item group.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLRevenueWorkInProgress = null;

    /**
     * Code of GLRevenue Work In Progress
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLRevenueWorkInProgressCode = null;

    /**
     * Description of GLRevenue Work In Progress
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLRevenueWorkInProgressDescription = null;

    /**
     * GL account the stock entries will be booked on. This overrules the GL account from the item group. If the license contains 'Intuit integration' this property overrides the value in Settings, not the item group.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLStock = null;

    /**
     * Code of GL account for stock
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLStockCode = null;

    /**
     * Description of GLStock
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLStockDescription = null;

    /**
     * Gross weight for international goods shipments
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $GrossWeight = null;

    /**
     * Indicates if batches are used for this item
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsBatchItem = null;

    /**
     * This property is obsolete. Use property 'IsBatchItem' instead.
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsBatchNumberItem = null;

    /**
     * Indicates if fractions (for example 0.35) are allowed for quantities of this item
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsFractionAllowedItem = null;

    /**
     * Indicates that an Item is produced to Inventory, not purchased
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsMakeItem = null;

    /**
     * Only used for packages (IsPackageItem=1). To indicate if this package is a new contract type package
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsNewContract = null;

    /**
     * Is On demand Item
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsOnDemandItem = null;

    /**
     * Indicates if the item is a package item. Can only be created in the hosting administration
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsPackageItem = null;

    /**
     * Indicates if the item can be purchased
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsPurchaseItem = null;

    /**
     * Indicated if the item is used in voucher functionality
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsRegistrationCodeItem = null;

    /**
     * Indicates if the item can be sold
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsSalesItem = null;

    /**
     * Indicates that serial numbers are used for this item
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsSerialItem = null;

    /**
     * This property is obsolete. Use property 'IsSerialItem' instead.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsSerialNumberItem = null;

    /**
     * If you have the Trade or Manufacturing license and you check this property the item will be shown in the stock positions overview, stock counts and transaction lists. If you have the Invoice module and you check this property you will get a general journal entry based on the Stock and Costs G/L accounts of the item group. If you donât want the general journal entry to be created you should change the Stock/Costs G/L account on the Item group page to the type Costs instead of Inventory. If you have the CRM Standalone license, the item will not be available.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsStockItem = null;

    /**
     * Indicates if the item is provided by an outside supplier
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsSubcontractedItem = null;

    /**
     * Indicates if tax needs to be calculated for this item
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsTaxableItem = null;

    /**
     * Indicates if the item is a time unit item (for example a labor hour item)
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsTime = null;

    /**
     * Indicates if the item can be exported to a web shop. If you have the CRM Standalone license, the item will not be available.
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsWebshopItem = null;

    /**
     * GUID of Item group of the item
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemGroup = null;

    /**
     * Code of ItemGroup
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemGroupCode = null;

    /**
     * Description of ItemGroup
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemGroupDescription = null;

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
     * Net weight for international goods shipments
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $NetWeight = null;

    /**
     * Net Weight unit for international goods shipment, only available in manufacturing packages
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $NetWeightUnit = null;

    /**
     * Notes
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Notes = null;

    /**
     * This field is write-only. The picture can be downloaded through PictureUrl and PictureThumbnailUrl.
     *
     *
     * @var null|string Binary data, warning, do not pre-encode this data.
     */
    #[EDM\Binary]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Picture = null;

    /**
     * File name of picture
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PictureName = null;

    /**
     * Url where thumbnail picture can be retrieved
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PictureThumbnailUrl = null;

    /**
     * Url where picture can be retrieved
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PictureUrl = null;

    /**
     * Code of SalesVat
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SalesVatCode = null;

    /**
     * Description of SalesVatCode
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SalesVatCodeDescription = null;

    /**
     * Search code of the item
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SearchCode = null;

    /**
     * Security level (0 - 100)
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $SecurityLevel = null;

    /**
     * Standard sales price
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $StandardSalesPrice = null;

    /**
     * Together with EndDate this determines if the item is active
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $StartDate = null;

    /**
     * Statistical code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $StatisticalCode = null;

    /**
     * Statistical net weight
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $StatisticalNetWeight = null;

    /**
     * Statistical units
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $StatisticalUnits = null;

    /**
     * Statistical value
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $StatisticalValue = null;

    /**
     * Quantity that is in stock
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $Stock = null;

    /**
     * The standard unit of this item
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Unit = null;

    /**
     * Description of Unit
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $UnitDescription = null;

    /**
     * Type of unit: A=Area, L=Length, O=Other, T=Time, V=Volume, W=Weight
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $UnitType = null;

    /**
     * Assemble at delivery
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $UseExplosion = null;
}