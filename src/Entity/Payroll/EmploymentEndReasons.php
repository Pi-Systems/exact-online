<?php

namespace PISystems\ExactOnline\Entity\Payroll;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to read employment end reasons.
 *
 * The reason is set when the employment is ended. This API has been deprecated and only returns the employment end reasons that were active before 2020.
 * Use the EmploymentEndReasonsOnFocusDate for payroll year 2020 and onwards.
 * For more information about the employments functionality in Exact Online, see End an employee's contract.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=PayrollEmploymentEndReasons
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/payroll/EmploymentEndReasons", "EmploymentEndReasons")]
#[Exact\Method(HttpMethod::GET)]
class EmploymentEndReasons extends DataSource
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
    public null|int $ID = null;

    /**
     * Employment end reason description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Description = null;
}