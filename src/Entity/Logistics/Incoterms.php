<?php

namespace PISystems\ExactOnline\Entity\Logistics;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to read incoterms.
 *
 * Retrieve all available incoterms.
 *
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=LogisticsIncoterms
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/logistics/Incoterms", "Incoterms")]
#[Exact\Method(HttpMethod::GET)]
class Incoterms extends DataSource
{

    /**
     * ID of Property
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ID = null;

    /**
     * Code of Incoterm
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Code = null;

    /**
     * Description of Incoterm
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Description = null;

    /**
     * Version of Incoterm
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Version = null;
}