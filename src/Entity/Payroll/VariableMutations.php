<?php

namespace PISystems\ExactOnline\Entity\Payroll;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to :
 *   Create a new variable mutation entry. It is mandatory to provide the value for PayrollYear, PayrollPeriod, EmployeeHID, and Type.If the Type is payroll component, then PayrollComponent property is also mandatory.
 *   Get the details of variable mutation entry. Filters can be used to reduce the amount of data retrieved.
 *   Update a variable mutation entry. It is mandatory to provide the ID of the entry to update.
 *
 *
 * Note: To access the API using OAuth 2.0 authentication process, see Using OAuth 2.0 to access Exact Online API.
 * You can find examples for setting up each API request in Make the request - REST
 *
 *
 * For more information about the  functionality in Exact Online, see Payroll through variable mutations.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=PayrollVariableMutations
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/payroll/VariableMutations", "VariableMutations")]
#[Exact\Method(HttpMethod::GET)]
#[Exact\Method(HttpMethod::POST)]
#[Exact\Method(HttpMethod::PUT)]
class VariableMutations extends DataSource
{

    /**
     * Primary key
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $ID = null;

    /**
     * Description for the payroll component entry
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Description = null;

    /**
     * Numeric number of Employee
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|int $EmployeeHID = null;

    /**
     * Employee ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $EmployeeID = null;

    /**
     * Entry field types: 1 = Quantity, 2 = Amount, 3 = Percentage
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|int $EntryFieldType = null;

    /**
     * Notes for the payroll component entry
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $Notes = null;

    /**
     * Payroll component code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|string $PayrollComponent = null;

    /**
     * Payroll component ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PayrollComponentID = null;

    /**
     * Payroll period
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|int $PayrollPeriod = null;

    /**
     * Payroll year
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|int $PayrollYear = null;

    /**
     * Type of the entry:1 = Days worked, 2 = Hours worked, 3 = Days ill, 4 = Hours ill, 5 = Days leave, 6 = Hours leave, 7 = Payroll component, 8 = Days care leave, 9 = Hours care leave, 10 = Days extended partner leave, 11 = Hours extended partner leave, 12 = Days Unpaid Leave, 13 = Hours Unpaid Leave, 14 = Days Paid Parental Leave, 15 = Hours Paid Parental Leave
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|int $Type = null;

    /**
     * Value of the entry
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    #[Exact\Method(HttpMethod::PUT)]
    public null|float $Value = null;
}