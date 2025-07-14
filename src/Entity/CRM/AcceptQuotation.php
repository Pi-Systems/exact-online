<?php

namespace PISystems\ExactOnline\Entity\CRM;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * You can accept a quotation and decide on an extra action to be taken. See the 'Action' property for possible actions. Depending on the action certain properties are required to be set.
 * For CRM standalone user, only the following values for Action are allowed:
 * 2 = Create sales invoice
 * 99 = Follow email with sign off action
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=CRMAcceptQuotation
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/crm/AcceptQuotation", "AcceptQuotation")]
#[Exact\Method(HttpMethod::POST)]
class AcceptQuotation extends DataSource
{

    /**
     * Identifier of the quotation.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $QuotationID = null;

    /**
     * 0 = No action (Default), 1 = create sales order, 2 = create sales invoice, 3 = create project, 4 = add to existing project, 5 = create subscription, 99 = follow email with sign off action.     *      * For CRM standalone:     * Code 99 only applicable for quotation with status '60 - Awaiting online acceptance'.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $Action = null;

    /**
     * Contains information if the quotation was successfully added to existing project.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $AddToExistingProjectSuccess = null;

    /**
     * Create a project item price agreement. Only needed when Action = 3 or Action = 4. Default = True.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|bool $CreateItemPriceAgreement = null;

    /**
     * Create a project work breakdown structure. Only needed when ProjectBudgetType = 2.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|bool $CreateProjectWBS = null;

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
     * Contains the error message if an error occurred during the acception of the quotation.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ErrorMessage = null;

    /**
     * The journal in which the sales invoice will be booked. Mandatory for Action = 2.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $InvoiceJournal = null;

    /**
     * Based on this layout the notification email is sent. In case it is not specified, then no email is sent.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $NotificationLayout = null;

    /**
     * Collection of optional quotation line IDs.
     *
     *
     * @var ?array A collection of AcceptQuotation\OptionalQuotationLineIDs
     */
    #[EDM\Collection(DataSource::class, 'OptionalQuotationLineIDs')]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public ?array $OptionalQuotationLineIDs = null;

    /**
     * The budget type of the project that will be created. Default = 0.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $ProjectBudgetType = null;

    /**
     * The ID of the project classification.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ProjectClassification = null;

    /**
     * The code of the project that will be created. Mandatory for Action = 3.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ProjectCode = null;

    /**
     * The description of the project that will be created. Mandatory for Action = 3.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ProjectDescription = null;

    /**
     * The ID of the project that will be linked to the quotation. Mandatory for Action = 4.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ProjectID = null;

    /**
     * The invoicing date of the project. Mandatory for ProjectInvoicingAction = 2.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|\DateTimeInterface $ProjectInvoiceDate = null;

    /**
     * The project invoicing action. 0 = None (Default), 1 = Create invoice terms, 2 = As quoted.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $ProjectInvoicingAction = null;

    /**
     * The prepaid type. Mandatory for ProjectType = 5. 1 = Retainer, 2 = Hour type bundle.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $ProjectPrepaindTypes = null;

    /**
     * PriceAgreement.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|float $ProjectPriceAgreement = null;

    /**
     * Contains information if the project was successfully created.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ProjectSuccess = null;

    /**
     * The type of the project that will be created. 2 = Fixed price (Default), 3 = Time and Material, 4 = Non billable, 5 = Prepaid.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $ProjectType = null;

    /**
     * The ID of the WBS deliverable part of. Only needed when Action = 4 and CreateProjectWBS = True.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ProjectWBSPartOf = null;

    /**
     * Reason why the quotation was accepted.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ReasonCode = null;

    /**
     * Contains information if the sales invoice was successfully created.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SalesInvoiceSuccess = null;

    /**
     * Contains information if the sales order was successfully created.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SalesOrderSuccess = null;

    /**
     * Description of the subscription.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SubscriptionDescription = null;

    /**
     * Start date of the subscription.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|\DateTimeInterface $SubscriptionStartDate = null;

    /**
     * Contains information if the subscription was successfully created.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SubscriptionSuccess = null;

    /**
     * The ID of the subscription condition. Mandatory for Action = 5.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SubscriptionType = null;

    /**
     * Contains information if the quotation was successfully accepted.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SuccessMessage = null;

    /**
     * Update project budget, price agreement and hours. Only needed when Action = 4. Default = True.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|bool $UpdateProjectBudgetAndPriceAgreement = null;

    /**
     * The number by which this quotation is identified by the order account
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $YourRef = null;
}