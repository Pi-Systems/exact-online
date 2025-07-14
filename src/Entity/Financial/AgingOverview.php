<?php

namespace PISystems\ExactOnline\Entity\Financial;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to get an overview of your suppliers/customers outstanding items amount that is group by their age (e.g. How many days after creation).
 * You can view this information in the Ageing Analysis Report - A/R, Ageing Analysis Report - A/P as well.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=ReadFinancialAgingOverview
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/read/financial/AgingOverview", "AgingOverview")]
#[Exact\Method(HttpMethod::GET)]
class AgingOverview extends DataSource
{

    /**
     * Primary key
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $AgeGroup = null;

    /**
     * Description of AgeGroup
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AgeGroupDescription = null;

    /**
     * Amount payable
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $AmountPayable = null;

    /**
     * Amount receivable
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $AmountReceivable = null;

    /**
     * Code of Currency
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CurrencyCode = null;
}