<?php

namespace PISystems\ExactOnline\Entity\Subscription;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * This endpoint enables users to retrieve the types of subscription lines.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SubscriptionSubscriptionLineTypes
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/subscription/SubscriptionLineTypes", "SubscriptionLineTypes")]
#[Exact\Method(HttpMethod::GET)]
class SubscriptionLineTypes extends DataSource
{

    /**
     * Primary key
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
     * Description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Description = null;
}