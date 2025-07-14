<?php

namespace PISystems\ExactOnline\Entity\Purchase;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to create and read purchase invoice lines.
 *
 * A purchase invoice line is always part of a purchase invoice and describes an item that you purchased from a supplier.
 *
 * A purchase invoice line can not be POSTed by itself. It has to be part of a POST of a purchase invoice. Each purchase invoice line has to contain either an item or a purchase order line.
 *
 * In a POST request it is important to know the type (including or excluding) of the VAT code. This type determines how the VAT amount is calculated in relation to the amount or price. For example an amount of 100 with a '21% including' VAT code results in a VAT amount of 17.36. With a '21% excluding' VAT code the VAT amount will be 21.00. When you don't specify a VAT code a default value will be used that is based on the configuration of the supplier and the item. We recommend to always specify Amount and VATCode when you create a new purchase invoice line for an item.
 * For more information about the purchase invoice functionality in Exact Online, see Create purchase invoices.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=PurchasePurchaseInvoiceLines
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/purchase/PurchaseInvoiceLines", "PurchaseInvoiceLines")]
#[Exact\Method(HttpMethod::GET)]
#[Exact\Method(HttpMethod::POST)]
class PurchaseInvoiceLines extends DataSource
{

    /**
     * A guid that uniquely identifies the purchase invoice line.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ID = null;

    /**
     * In a GET request the line amount is always returned excluding VAT in foreign currency.In a POST request the line amount has to be submitted either including or excluding the VAT amount. This depends on the type (including or excluding) of the VAT code.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|float $Amount = null;

    /**
     * Amount excluding VAT in default currency.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $AmountDC = null;

    /**
     * The code of the cost center that is linked to this invoice line.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $CostCenter = null;

    /**
     * The code of the cost unit that is linked to this invoice line.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $CostUnit = null;

    /**
     * The currency of the line amount. The total invoice amount and all individual line amounts are in the same currency.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Currency = null;

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
     * Description of the invoice line.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $Description = null;

    /**
     * The discount given on the default price. A value of 0.1 translates to 10% discount.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|float $Discount = null;

    /**
     * Expense related to the Work Breakdown Structure of the selected project. Only available with a professional service license
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $Expense = null;

    /**
     * Description of expense. Only available with a professional service license
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ExpenseDescription = null;

    /**
     * The unique identifier of the purchase invoice this line belongs to.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $InvoiceID = null;

    /**
     * Purchase invoice type.
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $InvoiceType = null;

    /**
     * Guid that identifies the purchase item. In a POST request either the Item or the PurchaseOrderLine has to be supplied.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $Item = null;

    /**
     * The default unit of the purchased item.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ItemUnit = null;

    /**
     * The sequence number of the line.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $LineNumber = null;

    /**
     * The date and time the invoice line was last modified.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Modified = null;

    /**
     * The net price that has to be paid per unit. NetPrice = UnitPrice * (1.0 - Discount).Depending on the type of the VAT code the net price is including or excluding VAT.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|float $NetPrice = null;

    /**
     * The user can enter notes related to the invoice line here.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $Notes = null;

    /**
     * The project linked to the purchase invoice line. This field is only applicable for Manufacturing and Professional Services.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $Project = null;

    /**
     * Guid that identifies the purchase order line that is being invoiced. When doing a POST either the Item or the PurchaseOrderLine has to be supplied.The values of the purchase order line such as Quantity, Item and Amount will be copied to the purchase invoice line.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $PurchaseOrderLine = null;

    /**
     * The number of purchased items in purchase units. The purchase unit is defined on the item card and it can also be found using the logistics/SupplierItem api endpoint.For divisible items the quantity can be a fractional number, otherwise it is an integer.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|float $Quantity = null;

    /**
     * The number of purchased items in default units. An item has both a default unit and a purchase unit, for example piece and box with a box containing 12 pieces. The multiplication factor (12 in this example) between the default unit and purchase unit is maintained on the item card. When you GET a purchase invoice line for 1 box of items the field Quantity = 1 and QuantityInDefaultUnits = 12.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $QuantityInDefaultUnits = null;

    /**
     * Indicates whether the purchase invoice line needs to be rebilled. Only available with a professional service license
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|bool $Rebill = null;

    /**
     * The code of the unit in which the item is purchased. For example piece, box or kg. The value is taken from the purchase unit in the item card.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Unit = null;

    /**
     * The default purchase price per unit.Depending on the type of the VAT code the unit price is including or excluding VAT.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|float $UnitPrice = null;

    /**
     * The VAT amount of the invoice line.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|float $VATAmount = null;

    /**
     * The VAT code used for the invoice line.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $VATCode = null;

    /**
     * The VAT percentage.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $VATPercentage = null;
}