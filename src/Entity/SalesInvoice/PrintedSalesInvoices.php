<?php

namespace PISystems\ExactOnline\Entity\SalesInvoice;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to print or send a sales invoice document.
 * The document is created through the given 'InvoiceID', and is sent to the respective receiver based on the given 'SendEmailToCustomer', 'SenderEmailAddress', 'SendInvoiceToCustomerPostbox' , 'SendInvoiceViaPeppol' and 'SendOutputBasedOnAccount'.
 * An existing sales invoice entry must be located to create a sales invoice document.
 *
 * When you create sales invoices, you can print them for internal use or send them to your customers.
 * For more information about the sales invoice functionality in Exact Online, see Print sales invoices.
 *
 * To view an example of the business use of this endpoint, see Rest API - Business example API sales invoice.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SalesInvoicePrintedSalesInvoices
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/salesinvoice/PrintedSalesInvoices", "PrintedSalesInvoices")]
#[Exact\Method(HttpMethod::POST)]
class PrintedSalesInvoices extends DataSource
{

    /**
     * Primary key, Reference to EntryID of SalesInvoice
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $InvoiceID = null;

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
     * Date of the invoice
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|\DateTimeInterface $InvoiceDate = null;

    /**
     * Contains the error message if an error occurred during the sending via peppol
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $PeppolCreationError = null;

    /**
     * Contains information if sending via peppol was succesfully sent
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $PeppolCreationSuccess = null;

    /**
     * Contains the error message if an error occurred during the sending of a postbox message
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $PostboxMessageCreationError = null;

    /**
     * Contains information if a postbox message was succesfully sent
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $PostboxMessageCreationSuccess = null;

    /**
     * The postbox from where the message is sent
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $PostboxSender = null;

    /**
     * Reporting period
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $ReportingPeriod = null;

    /**
     * Reporting year
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $ReportingYear = null;

    /**
     * Set to True if an email containing the invoice should be sent to the invoice customer. This option overrules SendInvoiceToCustomerPostbox.
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

    /**
     * Set to True if a postbox message containing the invoice should be sent to the invoice customer Take notes:The digital postbox option only available if the license has Mailbox feature set.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|bool $SendInvoiceToCustomerPostbox = null;

    /**
     * Set to True if the invoice should be sent via peppol to the invoice customer.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|bool $SendInvoiceViaPeppol = null;

    /**
     * Set to True if the output preference should be taken from the account. It will be either Document only, Email, Digital postbox or Peppol. This option overrules by SendEmailToCustomer, SendInvoiceToCustomerPostbox, SendInvoiceViaPeppol. Take notes:The digital postbox option only available if the license has Mailbox feature set.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|bool $SendOutputBasedOnAccount = null;
}