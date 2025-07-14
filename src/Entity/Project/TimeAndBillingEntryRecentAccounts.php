<?php

namespace PISystems\ExactOnline\Entity\Project;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * This endpoint enables users to retrieve a list of Accounts used by an employee for hour and cost entries. The list is ordered by the most recently used first.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=ReadProjectTimeAndBillingEntryRecentAccounts
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/read/project/TimeAndBillingEntryRecentAccounts", "TimeAndBillingEntryRecentAccounts")]
#[Exact\Method(HttpMethod::GET)]
class TimeAndBillingEntryRecentAccounts extends DataSource
{

    /**
     * Guid ID of the account used for hour entries
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountId = null;

    /**
     * Name of account
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AccountName = null;

    /**
     * The last date that the account has been used for hour entry
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $DateLastUsed = null;
}