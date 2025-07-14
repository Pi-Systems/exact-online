<?php

namespace PISystems\ExactOnline\Entity\Financial;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to get an overall stastictic of your Suppliers or Customers outstanding items. From this API, you can know
 * 1. The total number of invoices and the amount that needs to be paid
 * 2. The total number of invoices and the amount that needs to be collected
 * 3. The total number of overdue invoices and the  amount that needs to be paid
 * 4. The total number of overdue invoices and the amount that needs to be collected
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=ReadFinancialOutstandingInvoicesOverview
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/read/financial/OutstandingInvoicesOverview", "OutstandingInvoicesOverview")]
#[Exact\Method(HttpMethod::GET)]
class OutstandingInvoicesOverview extends DataSource
{

    /**
     * Primary key
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CurrencyCode = null;

    /**
     * Total invoice amount to be paid
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $OutstandingPayableInvoiceAmount = null;

    /**
     * Number of invoices to be paid
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $OutstandingPayableInvoiceCount = null;

    /**
     * Total invoice amount to be received
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $OutstandingReceivableInvoiceAmount = null;

    /**
     * Number of invoices to be received
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $OutstandingReceivableInvoiceCount = null;

    /**
     * Total payable invoice amount that is overdue
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $OverduePayableInvoiceAmount = null;

    /**
     * Number of payable invoices that are overdue
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $OverduePayableInvoiceCount = null;

    /**
     * Total receivable invoice amount that is overdue
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $OverdueReceivableInvoiceAmount = null;

    /**
     * Number of receivable invoices that are overdue
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $OverdueReceivableInvoiceCount = null;
}