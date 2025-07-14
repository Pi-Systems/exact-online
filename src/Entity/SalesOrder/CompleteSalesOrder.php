<?php

namespace PISystems\ExactOnline\Entity\SalesOrder;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to complete a sales order either the remaining goods delivery, the outstanding invoice or both. The sales order will be completed without further goods deliveries, invoice or both.Once completed, this action cannot be undone.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SalesOrderCompleteSalesOrder
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/salesorder/CompleteSalesOrder", "CompleteSalesOrder")]
#[Exact\Method(HttpMethod::POST)]
class CompleteSalesOrder extends DataSource
{

    /**
     * Sales order entry ID.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $OrderID = null;

    /**
     * Complete the remaining goods delivery. Values: true or false. Default = false
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|bool $CompleteDelivery = null;

    /**
     * Complete the outstanding invoice amount. Values: true or false. Default = false
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|bool $CompleteInvoice = null;

    /**
     * Division code.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $Division = null;

    /**
     * Contains the error message if an error occurred during the completing of the sales order.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ErrorMessage = null;

    /**
     * Contains information if the sales order was successfully completed.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SuccessMessage = null;
}