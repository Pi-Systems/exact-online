<?php

namespace PISystems\ExactOnline\Entity\Cashflow;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to get an overview of all the payments in your administration. Paymentlines are grouped by own bank account, account bank account, payment reference, payment method, entry date and status. Payments of one entry have the same TransactionId. Among other things, with this endpoint you can get information like the payments status (e.g. Open, Processed) or when the payment is due.
 * PUT to this endpoint allows you to prepare an invoice for payment by adjusting the payment method, description and payment reference.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=CashflowPayments
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/cashflow/Payments", "Payments")]
#[Exact\Method(HttpMethod::GET)]
#[Exact\Method(HttpMethod::PUT)]
class Payments extends DataSource
{

    /**
     * Identifier of the payment.
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
     * The supplier to which the payment has to be done.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Account = null;

    /**
     * The bank account of the supplier, to which the payment has to be done.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $AccountBankAccountID = null;

    /**
     * The bank account number of the supplier, to which the payment has to be done.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountBankAccountNumber = null;

    /**
     * The code of the supplier to which the payment has to be done.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountCode = null;

    /**
     * Contact person copied from the purchase invoice linked to the related purchase entry. Used as prefered contact when sending reminders.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountContact = null;

    /**
     * Name of the contact person of the supplier.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountContactName = null;

    /**
     * Name of the supplier.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountName = null;

    /**
     * The amount in default currency (division currency). Payments are matched on this amount.
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
     * The amount of the payment. This is in the amount of the selected currency.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $AmountFC = null;

    /**
     * Own bank account from which the payment must be done.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BankAccountID = null;

    /**
     * Own bank account number from which the payment must be done.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BankAccountNumber = null;

    /**
     * When processing payments, all payments with the same processing data are put in a batch. This field contains the code of that batch.
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
     * The currency of the payment. This currency can only deviate from the division currency if the module Currency is in the license.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Currency = null;

    /**
     * Extra description for the payment that may be included in the bank export file.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Description = null;

    /**
     * Date before which the payment must be done to be eligible for discount.
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
     * Document that is created when processing payments.  The bank export file is attached to the document.
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
     * Date before which the payment must be done.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $DueDate = null;

    /**
     * Date since when the payment is no longer an outstanding item. This is the highest invoice date of all matched payments.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $EndDate = null;

    /**
     * Period since when the payment is no longer an outstanding item. This is the highest period of all matched payments.
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EndPeriod = null;

    /**
     * Year (of period) since when the payment is no longer an outstanding item. This is the highest year of all matched payments. Used in combination with EndPeriod.
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EndYear = null;

    /**
     * Processing date of the payment.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $EntryDate = null;

    /**
     * The unique identifier for a set of payments. A payment can be split so that one part is paid on a different date. In that case the two records get a different EntryID.
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
     * G/L account of the payment. Must be of type 22 (Accounts payable).
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
     * Boolean indicating whether the payment is part of a batch booking.
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $IsBatchBooking = null;

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
     * Number assigned during the of processing payments. When payments are processed a bank export file is created. This file contains one or more batches that contain one or more payments. Each batch gets a sequence number that is stored for each payment in that batch.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $PaymentBatchNumber = null;

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
     * Method of payment. B = On credit (default) I = Collection K = Cash V = Credit card.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $PaymentMethod = null;

    /**
     * Payment reference for the payment that may be included in the bank export file.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $PaymentReference = null;

    /**
     * Date and time since when the payment is selected to be paid.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $PaymentSelected = null;

    /**
     * User who selected the payment to be paid.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PaymentSelector = null;

    /**
     * Name of the payment selector.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PaymentSelectorFullName = null;

    /**
     * Exchange rate from payment currency to division currency. AmountFC * RateFC = AmountDC.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $RateFC = null;

    /**
     * The source of the payment.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Source = null;

    /**
     * The status of the payment. 20 = open 30 = selected - payment is selected to be paid 40 = processed - payment has been done 50 = matched - payment is matched with one or more other outstanding items or financial statement lines
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
     * Linked transaction. Use this as reference to PurchaseEntries.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $TransactionEntryID = null;

    /**
     * Linked transaction line. Use this as reference to PurchaseEntryLines
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
     * Invoice number of the supplier. In case the payment belongs to a bank entry line and is matched with one invoice, YourRef is filled with the YourRef of this invoice.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $YourRef = null;
}