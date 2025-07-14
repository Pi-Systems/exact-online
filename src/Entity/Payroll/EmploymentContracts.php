<?php

namespace PISystems\ExactOnline\Entity\Payroll;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to read employment contracts.
 *
 * The employment contract contains the basic information about the employee's contract.
 * For more information about the employment contracts functionality in Exact Online, see Modify an employee's contract.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=PayrollEmploymentContracts
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/payroll/EmploymentContracts", "EmploymentContracts")]
#[Exact\Method(HttpMethod::GET)]
class EmploymentContracts extends DataSource
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
    public null|string $ID = null;

    /**
     * Flexible employment contract phase
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ContractFlexPhase = null;

    /**
     * Flexible employment contract phase description.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ContractFlexPhaseDescription = null;

    /**
     * Confirmation: Create Auto CorrectionWhen the 'payroll run' already finalized and the update is made to the employment contract start date, the system will first block the PUT action with the message below.    With the 'PayrollCorrection' right, the error message below will be thrown:                     This change will lead to a recalculation of previous periods. A correction request will be created with the following data:              Activation date: 01-01-2019              Payroll year: 2019              Period: 1             If you are confirmed want to proceed, set the [CreateAutoCorrection] to True and re-submit the request.                Without the 'PayrollCorrection' right, the error message below will be thrown:         You do not have rights to change data that can influence processed payroll transactions.    Note : If you delete this contract in an already calculated period, auto corrections will be created for this employee.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $CreateAutoCorrection = null;

    /**
     * Creation date
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Created = null;

    /**
     * Confirmation: Create Predecessors For Linked AgenciesThe system will block the POST/PUT action when one of the condition below fulfilled:                        When create (POST) a new successor, the employment contract successor start date is set to an earlier payroll year.            When update (PUT) the existing employment contract start date to an earlier payroll year.                The error message below will be thrown:                             Attention: If you change the start date to an earlier payroll year, predecessors will be created for linked agencies.                 You will have to check if the data of the predecessors is correct.                 If you are confirmed want to proceed, set the [CreatePredecessorsForLinkedAgencies] to True and re-submit the request.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $CreatePredecessorsForLinkedAgencies = null;

    /**
     * User ID of creator
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Creator = null;

    /**
     * Name of creator
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CreatorFullName = null;

    /**
     * Division code
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Division = null;

    /**
     * Document ID of the employment contract
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Document = null;

    /**
     * ID of employee
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Employee = null;

    /**
     * Name of employee
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $EmployeeFullName = null;

    /**
     * Numeric ID of the employee
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EmployeeHID = null;

    /**
     * Type of employee. 1 - Employee, 2 - Contractor, 3 - Temporary, 4 - Student, 5 - Flexworker
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EmployeeType = null;

    /**
     * Employee type description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $EmployeeTypeDescription = null;

    /**
     * Employment ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Employment = null;

    /**
     * Obsolete
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EmploymentHID = null;

    /**
     * Employment number
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EmploymentNumber = null;

    /**
     * End date of employment contract
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $EndDate = null;

    /**
     * Last modified date
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Modified = null;

    /**
     * User ID of modifier
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Modifier = null;

    /**
     * Name of modifier
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ModifierFullName = null;

    /**
     * Obsolete
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Notes = null;

    /**
     * Employment probation end date
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $ProbationEndDate = null;

    /**
     * Employment probation period
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ProbationPeriod = null;

    /**
     * Contract probation period description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ProbationPeriodDescription = null;

    /**
     * Employment contract reason code. 1 - New employment, 2 - Employment change, 3 - New legal employer, 4 - Acquisition 5 - Previous contract expired, 6 - Other
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ReasonContract = null;

    /**
     * Employment contract reason description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ReasonContractDescription = null;

    /**
     * Sequence number
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Sequence = null;

    /**
     * Start date of employment contractNote : Be aware that for PUT, when you use a start date in the past it will also update years in service to this date.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $StartDate = null;

    /**
     * Type of employment contract. 1 - Definite, 2 - Indefinite, 3 - External
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Type = null;

    /**
     * Description of employment contract type
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $TypeDescription = null;
}