<?php

namespace PISystems\ExactOnline\Entity\Logistics;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to :
 *   Link a supplier to a particular item and create an active item purchase price. It will default item start date as
 * purchase price start date.
 *   Retrieve details of the linkage such as the purchase price, delivery terms, transport data, and the general details
 * between the item and the linked supplier. It only returns an active or future price.
 * Filter by StartDate and EndDate is not supported.
 *   Update the details of the linkage between the item and the linked supplier.
 *   Delete a linked supplier from the particular item.
 *
 * Main supplier will be automatically defaulted to the first supplier linked to an item; however it can be changed
 * when another supplier is linked and defined as the main supplier. A linked supplier cannot be removed from an item
 * if the item has been used in a purchase order or when the item has been linked to the particular supplier in a
 * purchase price list.
 * For more information about the suppliers by item functionality in Exact Online, see Overview | Suppliers by item.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=LogisticsSupplierItem
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/logistics/SupplierItem", "SupplierItem")]
#[Exact\Method(HttpMethod::GET)]
#[Exact\Method(HttpMethod::POST)]
#[Exact\Method(HttpMethod::PUT)]
#[Exact\Method(HttpMethod::DELETE)]
class SupplierItem extends DataSource
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
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    #[Exact\Method(HttpMethod::DELETE)]
    public null|string $ID = null;

    /**
     * This is the barcode for the unit other than standard unit of the item. Only supported by the Premium for Wholesale & Distribution and Manufacturing
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Barcode = null;

    /**
     * Copy purchase remarks to purchase lines
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|int $CopyRemarks = null;

    /**
     * Country of origin code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $CountryOfOrigin = null;

    /**
     * Description of country of origin
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CountryOfOriginDescription = null;

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
     * Currency of item price
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Currency = null;

    /**
     * Description of currency of item price
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CurrencyDescription = null;

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
     * Indicates that the supplier will deliver the item directly to customer. Values: 0 = No, 1 = Yes, 2 = Optional
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|int $DropShipment = null;

    /**
     * Together with StartDate this determines whether the price is active
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $EndDate = null;

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
     * Item code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemCode = null;

    /**
     * Description of Item
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemDescription = null;

    /**
     * Item Unit
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $ItemUnit = null;

    /**
     * Item Unit Code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemUnitCode = null;

    /**
     * Item Unit Description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemUnitDescription = null;

    /**
     * Indicates this is a main supplier
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|bool $MainSupplier = null;

    /**
     * Minimum quantity of the item for purchase, only available for Wholesale & Distribution (Professional and Premium only)
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|float $MinimumQuantity = null;

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
     * The number of days between placing an order with a supplier and receiving items from the supplier
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|int $PurchaseLeadTime = null;

    /**
     * Lot size of the item for purchase, only available for Wholesale & Distribution (Premium only)
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|int $PurchaseLotSize = null;

    /**
     * Purchase price. If neither active nor future price exists, it shows 0 when GET
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|float $PurchasePrice = null;

    /**
     * Unit code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $PurchaseUnit = null;

    /**
     * Description of unit
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PurchaseUnitDescription = null;

    /**
     * This is the multiplication factor when going from default item unit to the unit of this price
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|float $PurchaseUnitFactor = null;

    /**
     * VAT code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $PurchaseVATCode = null;

    /**
     * Description of VAT
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PurchaseVATCodeDescription = null;

    /**
     * Together with EndDate this determines whether the price is active
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $StartDate = null;

    /**
     * Supplier ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Supplier = null;

    /**
     * Supplier code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SupplierCode = null;

    /**
     * Description of supplier
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SupplierDescription = null;

    /**
     * Supplier's item code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $SupplierItemCode = null;
}