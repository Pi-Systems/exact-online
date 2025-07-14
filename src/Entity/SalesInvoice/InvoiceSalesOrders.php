<?php

namespace PISystems\ExactOnline\Entity\SalesInvoice;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to invoice sales orders.
 * Note: You must include a parameter for 'SalesOrderID' to add a collection of sales orders IDs to be invoiced. When you invoice more than one sales order, the maximum total number of sales order lines is 500.
 *
 * The To be invoiced page in Exact Online contains a list of all sales orders for which you need to create invoices.
 * For more information about the Sales order to be invoiced functionality in Exact Online, see About sales orders to be invoiced.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SalesInvoiceInvoiceSalesOrders
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/salesinvoice/InvoiceSalesOrders", "InvoiceSalesOrders")]
#[Exact\Method(HttpMethod::POST)]
class InvoiceSalesOrders extends DataSource
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
    public null|string $ID = null;

    /**
     * Invoice creation mode- 0: Per customer 1: Per sales order
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $CreateMode = null;

    /**
     * Stock entries entry number.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $DeliveryNumber = null;

    /**
     * Stock entries entry end date.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|\DateTimeInterface $EndDate = null;

    /**
     * Errors in the process.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Errors = null;

    /**
     * Invoice quantity processing mode- 0:By quantity delivered 1:By quantity ordered.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $InvoiceMode = null;

    /**
     * Code of Journal
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $JournalCode = null;

    /**
     * Number of invoices successfully created.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $NumberOfCreatedInvoices = null;

    /**
     * Number of invoices failed to create.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $NumberOfFailedInvoices = null;

    /**
     * Collection of Sales order IDs.
     *
     *
     * @var ?array A collection of InvoiceSalesOrders\SalesOrderIDs
     */
    #[EDM\Collection(DataSource::class, 'SalesOrderIDs')]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public ?array $SalesOrderIDs = null;

    /**
     * Stock entries entry start date.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|\DateTimeInterface $StartDate = null;

    /**
     * Possibility to override the InvoiceDate during creation of sales invoice from sales orders. Works only for integration with Intuit QuickBooks.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|\DateTimeInterface $UserInvoiceDate = null;
}