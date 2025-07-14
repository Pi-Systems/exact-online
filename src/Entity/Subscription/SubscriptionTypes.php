<?php

namespace PISystems\ExactOnline\Entity\Subscription;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to retrieve the susbcription types information.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SubscriptionSubscriptionTypes
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/subscription/SubscriptionTypes", "SubscriptionTypes")]
#[Exact\Method(HttpMethod::GET)]
class SubscriptionTypes extends DataSource
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
    public null|string $ID = null;

    /**
     * Number of days before or after generating the subscription invoice. Company settings and automatic     * generate invoice type need to be enabled before subscription invoice generated automatically
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $AutomaticGenerateInvoiceDays = null;

    /**
     * Description of the automatic generated invoice
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AutomaticGenerateInvoiceDescription = null;

    /**
     * Type of automatic generate invoice: 1=Never, 2=Before the subscription period, 3=After the subscription period.     * Company settings need to be enabled before subscription invoice generated automatically
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $AutomaticGenerateInvoiceType = null;

    /**
     * Number of days after sending the subscription invoice. Company settings and automatic     * sending invoice type need to be enabled before subscription invoice sent automatically
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $AutomaticSendInvoiceDays = null;

    /**
     * Method of automatic send invoice: 1=Send based on account, 2=Send via email, 3=Create documents, 4=Send via digital postbox,     * 5=Send and track, 6=Send via peppol. Company settings need to be enabled before subscription invoice sent automatically
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $AutomaticSendInvoiceMethod = null;

    /**
     * Sender's email of automatic send invoice: 1=Company email address, 2=Main user email address.     * Company settings need to be enabled before subscription invoice sent automatically
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $AutomaticSendInvoiceSender = null;

    /**
     * ID of automatic send invoice sender's mailbox. Company settings need to be enabled before subscription invoice sent automatically
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AutomaticSendInvoiceSenderMailbox = null;

    /**
     * Type of automatic send invoice: 1=Never, 2=When available.     * Company settings need to be enabled before subscription invoice sent automatically
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $AutomaticSendInvoiceType = null;

    /**
     * Cancellation period of subscription type
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $CancellationPeriod = null;

    /**
     * Unit of cancellation period: wk=Week, mm=Month, yy=Year, hy=Half-year, qt=Quarter
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CancellationPeriodUnit = null;

    /**
     * Code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Code = null;

    /**
     * Date and time when the subscription type was created
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Created = null;

    /**
     * ID of user that created the subscription type
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Creator = null;

    /**
     * Full name of user that created the subscription type
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CreatorFullName = null;

    /**
     * Custom field endpoint. Provided only for the Exact Online Premium users.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CustomField = null;

    /**
     * Description of subscription type
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Description = null;

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
     * Enable payment link: 0=Never, 1=Always, 2=Based on account
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EnablePaymentLink = null;

    /**
     * Invoice correction method: 1=Ratio based, 2=Zero Invoice, 3=Never invoiced
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $InvoiceCorrectionMethod = null;

    /**
     * Invoice period of subscription type
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $InvoicePeriod = null;

    /**
     * Unit of invoice period: wk=Week, mm=Month, yy=Year, hy=Half-year, qt=Quarter
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $InvoicePeriodUnit = null;

    /**
     * Manual renewal method: 1=Use item prices, 2=Use current subscription prices
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ManualRenewalMethod = null;

    /**
     * Last modified date of subscription type
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Modified = null;

    /**
     * ID of user that modified the subscription type
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Modifier = null;

    /**
     * Full name of user that modified the subscription type
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ModifierFullName = null;

    /**
     * Additional information about subscription type
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Notes = null;

    /**
     * Prolongation type: 0=No, 1=Manual, 2=Automatic
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ProlongationType = null;

    /**
     * Renewal cancellation period of subscription type
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $RenewalCancellationPeriod = null;

    /**
     * Unit of renewal cancellation period: wk=Week, mm=Month, yy=Year, hy=Half-year, qt=Quarter
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $RenewalCancellationPeriodUnit = null;

    /**
     * Renewal period of subscription type
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $RenewalPeriod = null;

    /**
     * Unit of renewal period: wk=Week, mm=Month, yy=Year, hy=Half-year, qt=Quarter
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $RenewalPeriodUnit = null;

    /**
     * Subscription period of subscription type
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $SubscriptionPeriod = null;

    /**
     * Unit of subscription period: wk=Week, mm=Month, yy=Year, hy=Half-year, qt=Quarter
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SubscriptionPeriodUnit = null;
}