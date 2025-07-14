<?php

namespace PISystems\ExactOnline\Entity\Financial;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * This resource will provide you information on the status of a Journal in a certain period. Are you interested in when a status of a Journal changes? You can get notifications via webhooks.
 *
 * This entity supports webhooks.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=ReadFinancialJournalStatusList
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/read/financial/JournalStatusList", "JournalStatusList")]
#[Exact\Method(HttpMethod::GET)]
class JournalStatusList extends DataSource
{

    /**
     * Reference to Journal
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Journal = null;

    /**
     * Financial period
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Period = null;

    /**
     * Financial year
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Year = null;

    /**
     * Description of Journal
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $JournalDescription = null;

    /**
     * Type of Journal 10=Cash, 12=Bank, 20=Sales, 21=Return invoice, 22=Purchase, 23=Received return invoice, 90=General journal
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $JournalType = null;

    /**
     * Description of JournalType
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $JournalTypeDescription = null;

    /**
     * Journal status for this year and period 0=open, 1=closed
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Status = null;

    /**
     * Description of Status
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $StatusDescription = null;
}