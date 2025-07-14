<?php

namespace PISystems\ExactOnline\Entity\HRM;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to read Leave registrations.
 *
 * Note: In case the division is linked to Exact Officient, new leave registrations will not be available in Exact Online anymore. Instead, the leave hours per day are available. Please refer to /api/v1/{division}/HRM/LeaveAbsenceHoursByDay.
 *
 * To see if a division is linked to Exact Officient, property IsHRDivision in /api/v1/{division}/system/Divisions can be used.
 *
 * For more information about the HRM functionality in Exact Online, see Work with leave and absence registrations.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=HRMLeaveRegistrations
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/hrm/LeaveRegistrations", "LeaveRegistrations")]
#[Exact\Method(HttpMethod::GET)]
class LeaveRegistrations extends DataSource
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
     * Description of leave
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
     * Employee linked to the leave registration
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Employee = null;

    /**
     * Employee full name
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
     * End date of leave
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $EndDate = null;

    /**
     * End time on the last day of leave stored as DateTime, and the date should be ignored
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $EndTime = null;

    /**
     * Total number of leave hours
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $Hours = null;

    /**
     * Hours of leave on the first day
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $HoursFirstDay = null;

    /**
     * Hours of leave on the last day
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $HoursLastDay = null;

    /**
     * Type of leave
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $LeaveType = null;

    /**
     * Code for type of leave
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $LeaveTypeCode = null;

    /**
     * Description for type of leave
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $LeaveTypeDescription = null;

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
     * Extra information for leave
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Notes = null;

    /**
     * Start date of leave
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $StartDate = null;

    /**
     * Start time on the first day of leave stored as DateTime, and the date should be ignored
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $StartTime = null;

    /**
     * Status of leave, 1 = Submitted, 2 = Approved, 3 = Rejected
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Status = null;
}