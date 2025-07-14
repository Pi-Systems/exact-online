<?php

namespace PISystems\ExactOnline\Entity\Payroll;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to read employment contract flex phases.
 *
 * An employment contract flex phase is used for flexworkers to indicate the phase
 * in which the income ratio is, in context of the 'Wet Flexibiliteit en Zekerheid'.
 *
 * With the tax authority this phase is known as 'Code fase indeling F&amp;Z'.
 *
 *
 * The phase is set in the employment contract, that can be read using the EmploymentContracts endpoint.
 *
 *
 * This API has been deprecated and only returns the employment contract flex phases that were active before 2022.
 * Please Use the EmploymentContractFlexPhasesOnFocusDate for payroll year 2022 and onwards.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=PayrollEmploymentContractFlexPhases
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/payroll/EmploymentContractFlexPhases", "EmploymentContractFlexPhases")]
#[Exact\Method(HttpMethod::GET)]
class EmploymentContractFlexPhases extends DataSource
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
     * Flexible employment contract phase description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Description = null;
}