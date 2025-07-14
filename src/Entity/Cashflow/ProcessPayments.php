<?php

namespace PISystems\ExactOnline\Entity\Cashflow;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to process a payment.
 * For more information about processing payment you can go to the following help files Payment process in Exact Online, Process payments.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=CashflowProcessPayments
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/cashflow/ProcessPayments", "ProcessPayments")]
#[Exact\Method(HttpMethod::POST)]
class ProcessPayments extends DataSource
{

    /**
     * Primary key.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ID = null;

    /**
     * This is a URL to get the documents that were created after the payments were successfully processed. These documents have to be sent to the bank in order to do the payments.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $BankExportDocumentsUrl = null;

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
     * Contains the error message if an error occurred during the processing of the payment(s).
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ErrorMessage = null;

    /**
     * Use this field to pass a collection of GUIDs representing the IDs of the payments that have to be processed.
     *
     *
     * @var ?array A collection of ProcessPayments\PaymentIDs
     */
    #[EDM\Collection(DataSource::class, 'PaymentIDs')]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public ?array $PaymentIDs = null;

    /**
     * Contains information if the payments were successfully processed.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SuccessMessage = null;
}