<?php

namespace PISystems\ExactOnline\Entity\SalesOrder;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to print or send a sales order document.
 * The document is created through the given 'OrderId', and is sent to the respective receiver based on the given 'SendEmailToCustomer' and 'SenderEmailAddress'.
 * An existing sales order entry must be located to create a sales order document.
 *
 * When you have created a sales order, you can print it for internal use or to send it to a customer. You cannot print sales orders that have been cancelled.
 * For more information about the sales order functionality in Exact Online, see Print sales orders.
 *
 * To view an example of the business use of this endpoint, see Rest API - Business example API sales order.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SalesOrderPrintedSalesOrders
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/salesorder/PrintedSalesOrders", "PrintedSalesOrders")]
#[Exact\Method(HttpMethod::POST)]
class PrintedSalesOrders extends DataSource
{

    /**
     * Primary key, Reference to OrderID of SalesOrder
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $OrderId = null;

    /**
     * Division code
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $Division = null;

    /**
     * Contains the id of the document that was created
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $Document = null;

    /**
     * Contains the error message if an error occurred during the creation of the document
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $DocumentCreationError = null;

    /**
     * Contains information if a document was succesfully created
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $DocumentCreationSuccess = null;

    /**
     * Based on this layout a PDF is created and attached to an Exact Online document and an email
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $DocumentLayout = null;

    /**
     * Contains the error message if an error occurred during the creation of the email
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $EmailCreationError = null;

    /**
     * Contains confirmation that an email was sent. If an email cannot be delivered this property will still show confirmation that the email was sent.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $EmailCreationSuccess = null;

    /**
     * Based on this layout the email text is produced
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $EmailLayout = null;

    /**
     * Extra text that can be added to the printed document and email
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ExtraText = null;

    /**
     * Set to True if an email containing the sales order should be sent to the customer
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|bool $SendEmailToCustomer = null;

    /**
     * Email address from which the email will be sent. If not specified, the company email address will be used.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SenderEmailAddress = null;
}