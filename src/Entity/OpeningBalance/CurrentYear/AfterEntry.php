<?php

namespace PISystems\ExactOnline\Entity\OpeningBalance\CurrentYear;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * This service returns opening balance amounts for G/L accounts. Only balance sheet accounts with amounts other than zero are returned. See also service Financial/ReportingBalance to get debit and credit amounts by period.
 * CurrentYear/AfterEntry considers all entries for the current year, if you only want to get final processed entries use CurrentYear/Processed instead.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=OpeningBalanceCurrentYearAfterEntry
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/openingbalance/CurrentYear/AfterEntry", "CurrentYear/AfterEntry")]
#[Exact\Method(HttpMethod::GET)]
class AfterEntry extends DataSource
{

    /**
     * Division code.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Division = null;

    /**
     * The balance sheet account.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLAccount = null;

    /**
     * The reporting year of the opening balance.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ReportingYear = null;

    /**
     * The opening balance amount of the G/L account.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $Amount = null;

    /**
     * Indicates whether the G/L account is a debit or credit account. D = Debit, C = Credit.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $BalanceSide = null;

    /**
     * The code of the G/L account.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLAccountCode = null;

    /**
     * The description of the G/L account.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $GLAccountDescription = null;
}