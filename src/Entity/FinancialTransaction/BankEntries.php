<?php

namespace PISystems\ExactOnline\Entity\FinancialTransaction;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to create, delete or read bank entries for the bank statements received.
 * Note: Bank entries are separated to header and line information. Endpoint BankEntries is used for the header information. You must include a parameter for 'BankEntryLines' to add cash entry lines when you POST to the BankEntries endpoint.
 * Bank entries have an opening balance which should be the end balance of the previous entry. Opening balance plus all bank entry lines should result in the closing balance.
 *
 * This entity supports webhooks.
 * Subscribe to the topic BankEntries for updates on the BankEntries resource. Note that you will also receive events on the BankEntryLines resource.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=FinancialTransactionBankEntries
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/financialtransaction/BankEntries", "BankEntries")]
#[Exact\Method(HttpMethod::GET)]
#[Exact\Method(HttpMethod::POST)]
#[Exact\Method(HttpMethod::DELETE)]
class BankEntries extends DataSource
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
    #[Exact\Method(HttpMethod::DELETE)]
    public null|string $EntryID = null;

    /**
     * Collection of lines
     *
     *
     * @var ?array A collection of BankEntries\BankEntryLines
     */
    #[EDM\Collection(BankEntryLines::class, 'BankEntryLines')]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public ?array $BankEntryLines = null;

    /**
     * Reference to document with bank statement
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $BankStatementDocument = null;

    /**
     * Document number of document with bank statement
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $BankStatementDocumentNumber = null;

    /**
     * Subject of document with bank statement
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BankStatementDocumentSubject = null;

    /**
     * Closing balance in the currency of the transaction
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|float $ClosingBalanceFC = null;

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
     * Currency code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $Currency = null;

    /**
     * Custom field endpoint
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CustomField = null;

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
     * Entry number
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $EntryNumber = null;

    /**
     * The period of the transaction lines. The period should exist in the period date table
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $FinancialPeriod = null;

    /**
     * The financial year to which the entry belongs. The financial year should exist in the period date table
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $FinancialYear = null;

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
     * Description of Journal
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $JournalDescription = null;

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
     * Opening balance in the currency of the transaction
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|float $OpeningBalanceFC = null;

    /**
     * Status: 20 = Open, 50 = Processed
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Status = null;

    /**
     * Description of Status
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $StatusDescription = null;
}