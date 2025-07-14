<?php

namespace PISystems\ExactOnline\Entity\Cashflow;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to get an overview of all the payment to be received in your administration. Paymentlines are grouped by own bank account, account bank account, payment reference, payment method, entry date and status. Payments of one entry have the same TransactionId. Among other things, with this endpoint you can get information like the payments status (e.g. Open, Processed) or when the collection is due.
 * PUT to this endpoint allows you to prepare collections for sales invoices by adjusting the payment method,description and payment reference.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=CashflowReceivables
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/cashflow/Receivables", "Receivables")]
#[Exact\Method(HttpMethod::GET)]
#[Exact\Method(HttpMethod::PUT)]
class Receivables extends DataSource
{

    /**
     * Identifier of the receivable.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $ID = null;

    /**
     * The customer from which the receivable will come.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Account = null;

    /**
     * The bank account of the customer, from which the receivable will come.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountBankAccountID = null;

    /**
     * The bank account number of the customer, from which the receivable will come.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountBankAccountNumber = null;

    /**
     * The code of the customer from which the receivable will come.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountCode = null;

    /**
     * Contact person copied from the purchase invoice linked to the related purchase entry.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountContact = null;

    /**
     * Name of the contact person of the customer.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountContactName = null;

    /**
     * Country code of the customer.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountCountry = null;

    /**
     * Name of the customer.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountName = null;

    /**
     * The amount in default currency (division currency). Receivables are matched on this amount.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $AmountDC = null;

    /**
     * The amount of the discount in the default currency.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $AmountDiscountDC = null;

    /**
     * The amount of the discount. This is in the amount of the selected currency.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $AmountDiscountFC = null;

    /**
     * The amount of the receivable. This is in the amount of the selected currency.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $AmountFC = null;

    /**
     * Own bank account to which the receivable will be done.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BankAccountID = null;

    /**
     * Own bank account number to which the receivable will be done.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BankAccountNumber = null;

    /**
     * When processing receivables, all receivable with the same processing data are put in a batch. This field contains the code of that batch.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CashflowTransactionBatchCode = null;

    /**
     * Creation date.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Created = null;

    /**
     * User ID of the creator.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Creator = null;

    /**
     * Name of the creator.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CreatorFullName = null;

    /**
     * The currency of the receivable. This currency can only deviate from the division currency if the module Currency is in the license.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Currency = null;

    /**
     * Description.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Description = null;

    /**
     * Direct Debit Mandate used to collect the receivable.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DirectDebitMandate = null;

    /**
     * Description of the mandate.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DirectDebitMandateDescription = null;

    /**
     * Payment type of the mandate. 0 = One off payment 1 = Recurrent payment.
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $DirectDebitMandatePaymentType = null;

    /**
     * Unique mandate reference.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DirectDebitMandateReference = null;

    /**
     * Type of the mandate. 0 = Core 1 = Business-to-business.
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $DirectDebitMandateType = null;

    /**
     * Date before which the payment by the customer must be done to be eligible for discount.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $DiscountDueDate = null;

    /**
     * Division code.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Division = null;

    /**
     * Document that is created when processing collections.  The bank export file is attached to the document.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Document = null;

    /**
     * Number of the document.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $DocumentNumber = null;

    /**
     * Subject of the document.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DocumentSubject = null;

    /**
     * Date before which the payment by the customer must be done.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $DueDate = null;

    /**
     * Date since when the receivable is no longer an outstanding item. This is the highest invoice date of all matched receivables.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $EndDate = null;

    /**
     * Period since when the receivable is no longer an outstanding item. This is the highest period of all matched receivables.
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EndPeriod = null;

    /**
     * The value of the tag 'EndToEndID' when generating a SEPA file.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $EndToEndID = null;

    /**
     * Year (of period) since when the receivable is no longer an outstanding item. This is the highest year of all matched receivables. Used in combination with EndPeriod.
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EndYear = null;

    /**
     * Processing date of the receivable.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $EntryDate = null;

    /**
     * The unique identifier for a set of receivables. A receivable can be split so that one part is received on a different date. In that case the two records get a different EntryID.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $EntryID = null;

    /**
     * Entry number of the linked transaction.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EntryNumber = null;

    /**
     * G/L account of the payment. Must be of type 20 (Accounts receivable).
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLAccount = null;

    /**
     * Code of the G/L account.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLAccountCode = null;

    /**
     * Description of the G/L account.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLAccountDescription = null;

    /**
     * Invoice date of the linked transaction.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $InvoiceDate = null;

    /**
     * Invoice number of the linked transaction.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $InvoiceNumber = null;

    /**
     * Boolean indicating whether the receivable is part of a batch booking.
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsBatchBooking = null;

    /**
     * Boolean indicating whether the receivable was fully paid by the customer.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsFullyPaid = null;

    /**
     * Journal of the linked transaction.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Journal = null;

    /**
     * Description of the journal.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $JournalDescription = null;

    /**
     * Last payment date.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $LastPaymentDate = null;

    /**
     * Last modified date.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Modified = null;

    /**
     * User ID of modifier.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Modifier = null;

    /**
     * Name of modifier.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ModifierFullName = null;

    /**
     * Order number of the linked transaction.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $OrderNumber = null;

    /**
     * Payment condition of the linked transaction.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PaymentCondition = null;

    /**
     * Description of the payment condition.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PaymentConditionDescription = null;

    /**
     * Number of days between invoice date and due date.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $PaymentDays = null;

    /**
     * Number of days between invoice date and due date of the discount.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $PaymentDaysDiscount = null;

    /**
     * Payment discount percentage.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $PaymentDiscountPercentage = null;

    /**
     * PaymentInformationID tag from the SEPA xml file.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PaymentInformationID = null;

    /**
     * Method of payment. B = On credit (default) I = Collection K = Cash V = Credit card
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $PaymentMethod = null;

    /**
     * Payment reference for the receivable that may be included In the bank export file
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $PaymentReference = null;

    /**
     * Exchange rate from receivable currency to division currency. AmountFC * RateFC = AmountDC.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $RateFC = null;

    /**
     * Number assigned during the processing of receivables.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ReceivableBatchNumber = null;

    /**
     * Date and time since when the receivable is selected to be collected.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $ReceivableSelected = null;

    /**
     * User who selected the receivable to be collected.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ReceivableSelector = null;

    /**
     * Name of the receivable selector.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ReceivableSelectorFullName = null;

    /**
     * The source of the receivable.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Source = null;

    /**
     * The status of the receivable. 20 = open 30 = selected - receivable is selected to be collected 40 = processed - collection has been done 50 = matched - receivable is matched with one or more other outstanding items or financial statement lines
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Status = null;

    /**
     * Total amount of the linked transaction in default currency (division currency).
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $TransactionAmountDC = null;

    /**
     * Total amount of the linked transaction in the selected currency.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $TransactionAmountFC = null;

    /**
     * Due date of the linked transaction.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $TransactionDueDate = null;

    /**
     * Linked transaction. Use this as reference to SalesEntries.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $TransactionEntryID = null;

    /**
     * Linked transaction line. Use this as reference to SalesEntryLines.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $TransactionID = null;

    /**
     * Indicates if the linked transaction is a reversal entry.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $TransactionIsReversal = null;

    /**
     * Period of the linked transaction.
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $TransactionReportingPeriod = null;

    /**
     * Year of the linked transaction.
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $TransactionReportingYear = null;

    /**
     * Status of the linked transaction.
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $TransactionStatus = null;

    /**
     * Type of the linked transaction.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $TransactionType = null;

    /**
     * Invoice number. In case the receivable belongs to a bank entry line and is matched with one invoice, YourRef is filled with the YourRef of this invoice.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $YourRef = null;
}