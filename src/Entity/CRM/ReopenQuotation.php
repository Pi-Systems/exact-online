<?php

namespace PISystems\ExactOnline\Entity\CRM;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * You can reopen a quotation if it has been accepted or rejected and you need to make changes to it. After reopening the status will be 'Printed'.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=CRMReopenQuotation
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/crm/ReopenQuotation", "ReopenQuotation")]
#[Exact\Method(HttpMethod::POST)]
class ReopenQuotation extends DataSource
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
     * Contains the error message if an error occurred during the reopening of the quotation.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $ErrorMessage = null;

    /**
     * Contains information if the quotation was successfully reopened.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $SuccessMessage = null;
}