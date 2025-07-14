<?php

namespace PISystems\ExactOnline\Entity\CRM;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to pass optional quotation line IDs in a POST to AcceptQuotation.
 * Note: It is a sub entity of AcceptQuotation.
 *
 * Quotation lines describe the items that you plan to make it optional in the quotation to your customers.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=CRMOptionalQuotationLineID
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/crm/OptionalQuotationLineID", "OptionalQuotationLineID")]
#[Exact\Method(HttpMethod::POST)]
class OptionalQuotationLineID extends DataSource
{

    /**
     * ID of quotation line.
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
}