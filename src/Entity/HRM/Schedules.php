<?php

namespace PISystems\ExactOnline\Entity\HRM;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to read Schedules.
 *
 * For more information about the Payroll functionality in Exact Online, see Working with employee's schedules.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=HRMSchedules
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/hrm/Schedules", "Schedules")]
#[Exact\Method(HttpMethod::GET)]
class Schedules extends DataSource
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
     * Obsolete
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Active = null;

    /**
     * Average hours per week in a schedulePlease be aware this property is mandatory if you use ScheduleType 1 or 2.For Time and Billing basic company, when creating a new schedule, the value is set to 0. When schedule entries are created, the BC will set the calculated value for Average hours per week.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $AverageHours = null;

    /**
     * Billability target
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $BillabilityTarget = null;

    /**
     * Schedule code
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Code = null;

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
     * ID of creator
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Creator = null;

    /**
     * Name of the creator
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CreatorFullName = null;

    /**
     * Average days per week in the scheduleFor Time and Billing basic company, when creating a new schedule, the value is set to 0. When schedule entries are created, the BC will set the calculated value for Average days per week.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $Days = null;

    /**
     * Description of the schedule
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
     * Employee ID of the schedule
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Employee = null;

    /**
     * Employee full name of the schedule
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $EmployeeFullName = null;

    /**
     * Employment ID for schedule
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Employment = null;

    /**
     * Employment CLA ID of the schedule
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $EmploymentCLA = null;

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
     * End date of the schedule
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $EndDate = null;

    /**
     * Number of hours per week in a CLA for which the schedule is built
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $Hours = null;

    /**
     * Number of hours which are built up each week for later leaveFor Time and Billing basic company, the value is set to 0.
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $LeaveHoursCompensation = null;

    /**
     * Indication if the schedule is a main schedule for a CLA. 1 = Yes, 0 = No
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Main = null;

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
     * ID of modifier
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Modifier = null;

    /**
     * Name of the modifier
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ModifierFullName = null;

    /**
     * Part-time factor for payroll calculation. Value between 0 and 1
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $PaymentParttimeFactor = null;

    /**
     * The collection of schedule entries
     *
     *
     * @var ?array A collection of Schedules\ScheduleEntries
     */
    #[EDM\Collection(DataSource::class, 'ScheduleEntries')]
    #[Exact\Method(HttpMethod::GET)]
    public ?array $ScheduleEntries = null;

    /**
     * Type of schedule. 1 = Hours and average days, 2 = Hours and specific days, 3 = Hours per day, 4 = Time frames per day1 - Hours and average days, StartWeek will automatically set to 12 - Hours and specific days, StartWeek must be greater or equal to 1, AverageDaysPerWeek will automatically set to 03 - Hours per day, StartWeek must be greater or equal to 1, AverageHoursPerWeek and AverageDaysPerWeek will automatically set to 04 - Time frames per day, Hours per day, StartWeek must be greater or equal to 1, AverageHoursPerWeek and AverageDaysPerWeek will automatically set to 0Note: For Time and Billing basic company, the schedule type is automatically set to value "3 - Hour per day".
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ScheduleType = null;

    /**
     * Description of the schedule type
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ScheduleTypeDescription = null;

    /**
     * Start date of the schedule
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $StartDate = null;

    /**
     * Week to start the schedule from for an employee
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $StartWeek = null;
}