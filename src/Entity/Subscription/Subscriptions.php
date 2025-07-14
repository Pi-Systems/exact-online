<?php

namespace PISystems\ExactOnline\Entity\Subscription;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to create, read, update and delete subscriptions.
 *
 * Note: You must include a parameter for 'SubscriptionLines' to add subscription lines when you POST to the Subscriptions endpoint. This parameter is not necessary when you PUT to this endpoint.
 * For more information about the Subscription functionality in Exact Online, see About Subscriptions.
 *
 * This entity supports webhooks.
 * Subscribe to the topic Subscriptions to get updates on the Subscription resource. Note that you will also receive events on the Subscription resource.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SubscriptionSubscriptions
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/subscription/Subscriptions", "Subscriptions")]
#[Exact\Method(HttpMethod::GET)]
#[Exact\Method(HttpMethod::POST)]
#[Exact\Method(HttpMethod::PUT)]
#[Exact\Method(HttpMethod::DELETE)]
class Subscriptions extends DataSource
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
    #[Exact\Method(HttpMethod::PUT)]
    #[Exact\Method(HttpMethod::DELETE)]
    public null|string $EntryID = null;

    /**
     * Indicates if subscription is blocked for time cost entry
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|bool $BlockEntry = null;

    /**
     * Date of cancellation
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|\DateTimeInterface $CancellationDate = null;

    /**
     * Guid ID of classification, to filter the report based on the classification of a subscription
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Classification = null;

    /**
     * Code of classification, to filter the report based on the classification of a subscription
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ClassificationCode = null;

    /**
     * Description of classification, to filter the report based on the classification of a subscription
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ClassificationDescription = null;

    /**
     * Date when the subscription was created
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Created = null;

    /**
     * The Guid ID of the user that created the subscription
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Creator = null;

    /**
     * The full name of the user that created the subscription
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CreatorFullName = null;

    /**
     * Currency code used in the transactions
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Currency = null;

    /**
     * Purchase order number of that is linked to customer
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $CustomerPONumber = null;

    /**
     * Description of the subscription
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Description = null;

    /**
     * Division number of the company that the subscription is linked to
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Division = null;

    /**
     * The date and time when the subscription ends
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|\DateTimeInterface $EndDate = null;

    /**
     * For monthly, quaterly, half-year and yearly invoice period, the day number of the month when the invoice of the subscription is sent.     *      * While for weekly invoice period, the weekday number of the week when the invoice of the subscription is sent:     * 0=Monday     * 1=Tuesday     * 2=Wednesday     * 3=Thursday     * 4=Friday     * 5=Saturday     * 6=Sunday
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|int $InvoiceDay = null;

    /**
     * The date when the invoice of the subscription is sent
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $InvoicedTo = null;

    /**
     * The account which the subsciption is invoiced to
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $InvoiceTo = null;

    /**
     * The ID of the contact person that is linked to the account the subscription is invoice to
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $InvoiceToContactPerson = null;

    /**
     * The name of the contact person that is linked to the account the subscription is invoice to
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $InvoiceToContactPersonFullName = null;

    /**
     * Name of the account to invoice to
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $InvoiceToName = null;

    /**
     * Date to start invoicing the account
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|\DateTimeInterface $InvoicingStartDate = null;

    /**
     * Date and time when the subscription has been modified
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Modified = null;

    /**
     * The ID of the user that modified the subscription
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Modifier = null;

    /**
     * The name of the user that modified the subscription
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ModifierFullName = null;

    /**
     * For additional information regarding the subscription
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Notes = null;

    /**
     * Subscription number of the company
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Number = null;

    /**
     * Reference to order account, GUID ID of the subscriber
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $OrderedBy = null;

    /**
     * Reference of contact person of order account, displays the contact name of the subscriber
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $OrderedByContactPerson = null;

    /**
     * Name of contact person of order account, displays the full contact name of the subscriber
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $OrderedByContactPersonFullName = null;

    /**
     * Name of order account, displays the name of the subscriber
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $OrderedByName = null;

    /**
     * Code of subscriptions payment condition
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $PaymentCondition = null;

    /**
     * Description of subscriptions payment condition
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PaymentConditionDescription = null;

    /**
     * Indicates if subscription has been printed
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|bool $Printed = null;

    /**
     * The Guid ID of the project, reference to Project endpoint
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Project = null;

    /**
     * Guid ID of the reason of cancellation record linked to the subscription
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $ReasonCancelled = null;

    /**
     * Code of the reason of cancellation record linked to the subscription
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ReasonCancelledCode = null;

    /**
     * Description of the reason of cancellation record linked to the subscription
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ReasonCancelledDescription = null;

    /**
     * Start date of the subscription
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|\DateTimeInterface $StartDate = null;

    /**
     * Collection of subscription lines linked to the subscription link, can be referenced to SubscriptionLines endpoint
     *
     *
     * @var ?array A collection of Subscriptions\SubscriptionLines
     */
    #[EDM\Collection(SubscriptionLines::class, 'SubscriptionLines')]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public ?array $SubscriptionLines = null;

    /**
     * Collection of employee's restriction, can be referenced to SubscriptionRestrictionEmployees endpoint
     *
     *
     * @var ?array A collection of Subscriptions\SubscriptionRestrictionEmployees
     */
    #[EDM\Collection(SubscriptionRestrictionEmployees::class, 'SubscriptionRestrictionEmployees')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $SubscriptionRestrictionEmployees = null;

    /**
     * Collection of restriction items linked to the subscription, can be referenced to SubscriptionRestrictionItems endpoint
     *
     *
     * @var ?array A collection of Subscriptions\SubscriptionRestrictionItems
     */
    #[EDM\Collection(SubscriptionRestrictionItems::class, 'SubscriptionRestrictionItems')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $SubscriptionRestrictionItems = null;

    /**
     * The GUID ID of the subscription condition, reference to SubscriptionType endpoint
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $SubscriptionType = null;

    /**
     * The code of the subscription condition record
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SubscriptionTypeCode = null;

    /**
     * The code of the subscription condition description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SubscriptionTypeDescription = null;
}