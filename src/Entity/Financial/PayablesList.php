<?php

namespace PISystems\ExactOnline\Entity\Financial;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to get all your suppliers payment terms information. Payment term are created when an purchase invoice is created or when money is paid to the supplier.
 * You can view this information in the Outstanding items report as well.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=ReadFinancialPayablesList
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/read/financial/PayablesList", "PayablesList")]
#[Exact\Method(HttpMethod::GET)]
class PayablesList extends DataSource
{

    /**
     * Primary key, human readable ID
     *
     *
     * @var null|int Int64
     */
    #[EDM\Int64]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $HID = null;

    /**
     * Code of Account
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountCode = null;

    /**
     * Reference to the account
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountId = null;

    /**
     * Name of Account
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountName = null;

    /**
     * Amount
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $Amount = null;

    /**
     * Amount in transit - The amount that you have requested your bank to pay
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $AmountInTransit = null;

    /**
     * Approval status:     * null - Invoice was entered before approval functionality was activated (treated as Approved for payments)     * 1 - N/A (used for non-electronic payment methods)     * 2 - Awaiting review     * 3 - Awaiting approval     * 4 - Approved
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ApprovalStatus = null;

    /**
     * Code of Currency
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CurrencyCode = null;

    /**
     * Description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Description = null;

    /**
     * Date the invoice is due (This due date is not the discount due date)
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $DueDate = null;

    /**
     * The entry number of this payment term corresponding purchase/cashflow entry
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EntryNumber = null;

    /**
     * Obsolete
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Id = null;

    /**
     * The date of the invoice or the date when money is paid to the supplier
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $InvoiceDate = null;

    /**
     * For purchase entry, it would be the entrynumber. For the cashflow entry, it will be entrynumber of an invoice which this payment is for
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $InvoiceNumber = null;

    /**
     * Code of Journal
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $JournalCode = null;

    /**
     * Description of Journal
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $JournalDescription = null;

    /**
     * Notes collection
     *
     *
     * @var ?array A collection of PayablesList\Notes
     */
    #[EDM\Collection(DataSource::class, 'Notes')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $Notes = null;

    /**
     * Purchase invoice Your Reference number. Will be null if it is a cashflow entry
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $YourRef = null;
}