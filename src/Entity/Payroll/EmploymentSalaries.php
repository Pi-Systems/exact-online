<?php

namespace PISystems\ExactOnline\Entity\Payroll;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to read employment salaries.
 *
 * The employment salary contains the basic information about the employee's salary, like full and parttime salary, hourly wage and scale.
 * For more information about the employment salaries functionality in Exact Online, see Modify an employee's salary or rate.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=PayrollEmploymentSalaries
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/payroll/EmploymentSalaries", "EmploymentSalaries")]
#[Exact\Method(HttpMethod::GET)]
class EmploymentSalaries extends DataSource
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
     * Salary Section: Salary typeValue: 0 - Gross, 1 - Net.
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $AmountType = null;

    /**
     * Salary Section: Salary type descriptionWhen AmountType value is 0, return 'Gross'When AmountType value 1, return 'Net'
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $AmountTypeDescription = null;

    /**
     * Confirmation: Create Auto CorrectionWhen the 'payroll run' already finalized and the update is made to the employment salary amount, the system will first block the PUT action with the message below.    With the 'PayrollCorrection' right, the error message below will be thrown:                     This change will lead to a recalculation of previous periods. A correction request will be created with the following data:              Activation date: 01-01-2019              Payroll year: 2019              Period: 1             If you are confirmed want to proceed, set the [AutoCorrection] to True and re-submit the request.                Without the 'PayrollCorrection' right, the error message below will be thrown:         You do not have rights to change data that can influence processed payroll transactions.    Note : If you delete this salary in an already calculated period, auto corrections will be created for this employee.
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $AutoCorrection = null;

    /**
     * The average number of contract days that an employee works per week
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $AverageDaysPerWeek = null;

    /**
     * The average number of contract hours that an employee works per week
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $AverageHoursPerWeek = null;

    /**
     * Schedule Section: Billability target
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $BillabilityTarget = null;

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
     * General section: Custom description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Description = null;

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
     * Employee ID
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
     * Employee number
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EmployeeHID = null;

    /**
     * Employment
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
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
     * Salary type of employment. 1 - Periodical (fixed), 2 - Per hour (variable)
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EmploymentSalaryType = null;

    /**
     * Salary type description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $EmploymentSalaryTypeDescription = null;

    /**
     * Salary record end dateNote : This property only supported for successor.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $EndDate = null;

    /**
     * Rate Section: External rate
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $ExternalRate = null;

    /**
     * Frequency: 1 - Yearly, 2 - Quarterly, 3 - Monthly, 4 - 4-weekly, 5 - Weekly, 11 - Yearly (Pro forma), 12 - Quarterly (Pro forma), 13 - Monthly (Pro forma), 14 - 4-Weekly (Pro forma), 15 - Weekly (Pro forma)
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Frequency = null;

    /**
     * Payroll period frequency description
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $FrequencyDescription = null;

    /**
     * Salary when working fulltime
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $FulltimeAmount = null;

    /**
     * Hourly wage
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $HourlyWage = null;

    /**
     * Rate Section: Intercompany rate
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $IntercompanyRate = null;

    /**
     * Internal rate for time & billing or professional service user
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $InternalRate = null;

    /**
     * Employee job level in context of a wage scale
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $JobLevel = null;

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
     * Salary when working parttime
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $ParttimeAmount = null;

    /**
     * Contract hours / Fulltime contract hours
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $ParttimeFactor = null;

    /**
     * Salary Section: Salary based on.Value: 0 - Manual entry, 1 - Wagescale, 2 - Minimum wage, 3 - Minimum hourly wage
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $SalaryBasedType = null;

    /**
     * When SalaryBasedType value is 0, return 'Manual entry'When SalaryBasedType value 1, return 'Wage scale'When SalaryBasedType value 2, return 'Minimum wage'When SalaryBasedType value 3, return 'Minimum hourly wage'
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SalaryBasedTypeDescription = null;

    /**
     * Employee wage scale
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Scale = null;

    /**
     * Employment schedule
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Schedule = null;

    /**
     * Employment schedule code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ScheduleCode = null;

    /**
     * Description of employment schedule
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ScheduleDescription = null;

    /**
     * Salary record start dateNote : This property only supported for successor.
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $StartDate = null;

    /**
     * Salary Section: Wagescale ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $WageScale = null;

    /**
     * Salary Section: Period for automatic step increase
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $WageScalePeriod = null;

    /**
     * Salary Section: Wagescale Step Code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $WageScaleStep = null;
}