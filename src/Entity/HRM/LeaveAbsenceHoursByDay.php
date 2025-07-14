<?php

namespace PISystems\ExactOnline\Entity\HRM;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to read employee's leave and absence hours by day.
 *
 * The StartTime and EndTime properties will only be populated when the division is linked to Exact Officient.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=HRMLeaveAbsenceHoursByDay
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/hrm/LeaveAbsenceHoursByDay", "LeaveAbsenceHoursByDay")]
#[Exact\Method(HttpMethod::GET)]
class LeaveAbsenceHoursByDay extends DataSource
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
     * Date of leave or absence
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Date = null;

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
     * ID of employee linked to the leave or absence
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Required]
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
     * Employment ID
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Employment = null;

    /**
     * Numeric ID of the employment
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EmploymentHID = null;

    /**
     * End time of leave or absence
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $EndTime = null;

    /**
     * Unique ID from external source. Eg. Exact Online HR
     *
     *
     * @var null|int Int64
     */
    #[EDM\Int64]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ExternalIDInt = null;

    /**
     * Unique leave or absence type from external source. Eg. Exact Online HR
     *
     *
     * @var null|int Int64
     */
    #[EDM\Int64]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ExternalLeaveAbsenceType = null;

    /**
     * Hours of leave or absence
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $Hours = null;

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
     * Start time of leave or absence
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $StartTime = null;

    /**
     * Status, 1 = Submitted, 2 = ApprovedNote: Submitted status is only applicable for Type = Leave
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Status = null;

    /**
     * Type, 0 = Leave, 1 = AbsenceNote: Absence type is only allow for Status = Approved
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Type = null;
}