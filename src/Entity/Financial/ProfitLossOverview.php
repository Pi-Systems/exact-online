<?php

namespace PISystems\ExactOnline\Entity\Financial;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint if you want to know the financial result of the current and previous year.
 * The current period is determined by checking the date of the endpoint execution against the Financial Year/Period setup.
 * E.g.
 * Date of Endpoint execution = 24-oct-2019.
 * In your administration, the financial year 2019 period 10 has the following setup of 01-10-2019 till 31-10-2019.
 * Current year is 2019,  current period is 10.
 * Previous year is 2018. previous period is 10.
 *
 * Only P&amp;L G/L Accounts are considered for this endpoint.
 * Costs are balances of all G/L Account of type Costs.
 * Revenue are balances of all G/L Account of type Revenue.
 * Results are calculated by subtracting cost from revenue.
 *
 *
 * For more information about profit and loss, kindly refer to the following help file Balance Sheet/Profit &amp; Loss.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=ReadFinancialProfitLossOverview
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/read/financial/ProfitLossOverview", "ProfitLossOverview")]
#[Exact\Method(HttpMethod::GET)]
class ProfitLossOverview extends DataSource
{

    /**
     * Primary key, Current year
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $CurrentYear = null;

    /**
     * Total cost for the current year and period
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $CostsCurrentPeriod = null;

    /**
     * Total cost for the current year
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $CostsCurrentYear = null;

    /**
     * Total cost for the previous year
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $CostsPreviousYear = null;

    /**
     * Total cost for the previous year and period
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $CostsPreviousYearPeriod = null;

    /**
     * Currency code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CurrencyCode = null;

    /**
     * Current period
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $CurrentPeriod = null;

    /**
     * Previous year
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $PreviousYear = null;

    /**
     * Period in previous year
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $PreviousYearPeriod = null;

    /**
     * Results of current year and period
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $ResultCurrentPeriod = null;

    /**
     * Results of current year
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $ResultCurrentYear = null;

    /**
     * Results of previous year
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $ResultPreviousYear = null;

    /**
     * Results of previous year and period
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $ResultPreviousYearPeriod = null;

    /**
     * Total revenue for the current year and period
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $RevenueCurrentPeriod = null;

    /**
     * Total revenue for the current year
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $RevenueCurrentYear = null;

    /**
     * Total revenue for the previous year
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $RevenuePreviousYear = null;

    /**
     * Total revenue for the previous year and period
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $RevenuePreviousYearPeriod = null;
}