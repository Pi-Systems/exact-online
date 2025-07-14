<?php

namespace PISystems\ExactOnline\Entity\Financial;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to retrieve all transaction sources. Transaction sources are used in financial entries and provide insight in how an entry is created. Example: 101 - Sales invoice: This entry is created from a sales invoice entered in the sales module.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=FinancialGLTransactionSources
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/financial/GLTransactionSources", "GLTransactionSources")]
#[Exact\Method(HttpMethod::GET)]
class GLTransactionSources extends DataSource
{

    /**
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ID = null;

    /**
     * Find more information here
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Description = null;

    /**
     * Find more information here
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DescriptionSuffix = null;
}