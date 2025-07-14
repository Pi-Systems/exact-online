<?php

namespace PISystems\ExactOnline\Entity\SalesInvoice;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to pass sales order IDs in a POST to InvoiceSalesOrders.
 * Note: It is a sub entity of InvoiceSalesOrders.
 *
 * Sales orders describe the items that you plan to sell to your customers.
 * For more information about the sales orders functionality in Exact Online, see About sales orders.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SalesInvoiceSalesOrderID
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/salesinvoice/SalesOrderID", "SalesOrderID")]
#[Exact\Method(HttpMethod::POST)]
class SalesOrderID extends DataSource
{

    /**
     * Use this ID to pass sales order IDs in a POST to InvoiceSalesOrders
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
}