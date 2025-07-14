<?php

namespace PISystems\ExactOnline\Entity\CRM;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to create and send the quotation directly to the customer by email to perform online sign off. You can accept or reject a quotation and decide on an extra action to be taken. See the 'Action' property for possible actions. Depending on the action certain properties are required to be set
 * For CRM standalone user, only the following value for Action is allowed:
 * 2 = Create sales invoice.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=CRMEmailWithSignOffQuotation
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/crm/EmailWithSignOffQuotation", "EmailWithSignOffQuotation")]
#[Exact\Method(HttpMethod::POST)]
class EmailWithSignOffQuotation extends DataSource
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
     * If you enter for this field, your customer receives an email with the quotation after approval of the quotation.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $AcceptEmailLayout = null;

    /**
     * The stage of the opportunity after approval of the quotation.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $AcceptOpportunityStage = null;

    /**
     * 0 = No action (Default), 1 = create sales order, 2 = create sales invoice, 3 = create project, 4 = add to existing project, 5 = create subscription.     *      * For CRM standalone:     * If the value is not provided, the default value will set to '2 - create sales invoice'.     * If the value is provided, the value must be '2 - create sales invoice'. Otherwise, the error message will be thrown.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|int $Action = null;

    /**
     * Allow customers to enter their purchase order number.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|bool $AllowProvideYourRef = null;

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
     * Based on this layout a PDF is created and attached to an Exact Online document and an email. In case it is not specified, the default layout is used.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $DocumentLayout = null;

    /**
     * Based on this layout the email text is produced.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $EmailLayout = null;

    /**
     * Contains the error message if an error occurred during the creation of the Email.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ErrorMessage = null;

    /**
     * Extra text that can be added to the printed document and email.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ExtraText = null;

    /**
     * The journal in which the sales invoice will be booked. Mandatory for Action = 2.     *      * For CRM standalone:     * If the value is not provided, the default value will set to 'sales journal'.     * If the value is provided, the value must be 'sales journal'. Otherwise, the error message will be thrown.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $InvoiceJournal = null;

    /**
     * The budget type of the project that will be created. 0 = None (Default), 1 = Hours per hour type, 2 = Work breakdown structure (WBS).
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
     * The ID of the project that will be linked to the quotation. Mandatory for Action = 4. For Action = 5, project will be linked to the subscription created.
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
    public null|int $ProjectPrepaidTypes = null;

    /**
     * Price agreement.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|float $ProjectPriceAgreement = null;

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
     * Date of the quotation printed.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|\DateTimeInterface $QuotationDate = null;

    /**
     * If you enter for this field, your customer receives an email informing them of the rejected quotation.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $RejectEmailLayout = null;

    /**
     * The stage of the opportunity after reject the quotation.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $RejectOpportunityStage = null;

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
     * Subject of the email. If LayoutEmailSubject featureset is enabled, subject in email text layout will be used.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $Subject = null;

    /**
     * The start date of the subscription. Mandatory for Action = 5.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|\DateTimeInterface $SubscriptionDate = null;

    /**
     * The description of the subscription that will be created. Mandatory for Action = 5.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SubscriptionDescription = null;

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
     * Contains information if the quotation was successfully sent.
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
     * The number by which this quotation is identified by the order account.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $YourRef = null;
}