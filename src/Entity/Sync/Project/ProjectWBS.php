<?php

namespace PISystems\ExactOnline\Entity\Sync\Project;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * The ProjectWBS sync api returns WBS deliverable, activity and expense. To filter out deliverable, activity or expense you need to filter only on property Type. The API does not allow to filter on these fields. Filtering need to be done after receiving the records.
 *
 *
 * The sync api's have the goal to keep the data between Exact Online and a 3rd party application the same.
 *
 *
 * The sync api's are all based on row versioning and because of that it is guaranteed to be unique. Every time an existing record is changed or a new record is inserted, the row versioning value is higher than the highest available value at that time. When retrieving records via these api's also a timestamp value is returned. The highest timestamp value of the records returned should be stored on client side. Next time records are retrieved, the timestamp value stored on client side should be provided as parameter. The api will then return only the new and changed records. Using this method is more reliable than using modified date, since it can happen that multiple records have the same modified date and therefore same record can be returned more than once. This will not happen when using timestamp.
 *
 *
 * The sync api's are also developed to give best performance when retrieving records. Because of performance and the intended purpose of the api's, only the timestamp field is allowed as parameter.
 *
 *
 * The single and bulk apiâs are designed for a different purpose. They provide ability to retrieve specific record or a set of records which meet certain conditions.
 *
 *
 * In case the division is moved to another database in Exact Online the timestamp values will be reset. Therefore, after a division is moved all data needs to be synchronized again in order to get the new timestamp values. To see if a division was moved, the /api/v1/{division}/system/Divisions can be used. The property DivisionMoveDate indicated at which date a division was moved and this date can be used to determine if it is needed to synchronize all data again.
 *
 *
 * The API has two important key fields, the Timestamp and the ID. The ID should be used to uniquely identify the record and will never change
 * . The Timestamp is used to get new or changed records in an efficient way and will change for every change made to the record.
 *
 *
 * The timestamp value returned has no relation with actual date or time. As such it cannot be converted to a date\time value. The timestamp is a rowversion value.
 *
 *
 * When you use the sync or delete api for the first time for a particular division, filter on timestamp greater than 1.
 *
 *
 * Or use the SyncTimestamp API to GET a timestamp from a certain date.
 *
 *
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SyncProjectProjectWBS
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/sync/Project/ProjectWBS", "Project/ProjectWBS")]
#[Exact\Method(HttpMethod::GET)]
class ProjectWBS extends DataSource
{

    /**
     * Timestamp
     *
     *
     * @var null|int Int64
     */
    #[EDM\Int64]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Timestamp = null;

    /**
     * A boolean field to indicated if time and cost entries is blocked
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $BlockEntry = null;

    /**
     * A boolean field to indicated if rebilling is blocked. Only for expense
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $BlockRebilling = null;

    /**
     * Budget cost of the WBS activity and expense
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $BudgetedCost = null;

    /**
     * Budget hours of the WBS. Only for activity
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $BudgetedHours = null;

    /**
     * Budget quantity of the WBS. Only for expense
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $BudgetedQuantity = null;

    /**
     * Revenue of the WBS. Only for activity and expense
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $BudgetedRevenue = null;

    /**
     * BudgetOverrunHours: 10-Allowed, 20-Not Allowed. Only for activity
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $BudgetOverrunHours = null;

    /**
     * A boolean field to indicated if the project WBS deliverable is completed.
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Completed = null;

    /**
     * The date and time when the WBS was created
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Created = null;

    /**
     * The ID of the user that created the WBS
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Creator = null;

    /**
     * The full name of the user that created the WBS
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CreatorFullName = null;

    /**
     * Custom field endpoint. Provided only for the Exact Online Premium users.
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $CustomField = null;

    /**
     * Default item to used for timecost entry
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DefaultItem = null;

    /**
     * To indicated if only default item is allowed. Only for activity
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $DefaultItemIsMandatory = null;

    /**
     * Description of the project WBS
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
     * EndDate of the WBS. If it is a deliverable then EndDate is the lastest EndDate of its children
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $EndDate = null;

    /**
     * Primary key
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ID = null;

    /**
     * The invoice date of the WBS when invoice term is generated
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $InvoiceDate = null;

    /**
     * The invoice method of the WBS. For activity: null = According to parent, 2 = Fixed price, 3 = Time and Material, 4 = Non billable, 5 = Prepaid. For expense: null = According to parent, 6 = Fixed, 7 = Rebill, 8 = Non re-billable
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $InvoiceMethod = null;

    /**
     * To indicated if additional invoice is allowed on this WBS.     * Additional invoice can only be set when the project type is fixed price or prepaid and the project allow additional invoices.     * Only for activity and expense. (Obsolete)
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $InvoiceSeparately = null;

    /**
     * Markup percentage for WBS expense
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $MarkupPercentage = null;

    /**
     * To indicated if this is a milestone for WBS deliverable
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Milestone = null;

    /**
     * Last modified date and time
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $Modified = null;

    /**
     * The Guid ID of the user that modified the records
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Modifier = null;

    /**
     * The full name of the user that modified the WBS
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ModifierFullName = null;

    /**
     * For additional information about wbs
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Notes = null;

    /**
     * ID of the parent WBS
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PartOf = null;

    /**
     * Description of part of
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PartOfDescription = null;

    /**
     * ID of the project that linked to WBS
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Project = null;

    /**
     * Description of the project that linked to WBS
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ProjectDescription = null;

    /**
     * ID of invoice term that linked to the WBS deliverable. Invoice term can only be linked when the project is using billing milestones and the deliverable is set as a milestone
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ProjectTerm = null;

    /**
     * Description of invoice term that linked to the WBS deliverable
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ProjectTermDescription = null;

    /**
     * Purchase price of the item. Only for expense
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $PurchasePrice = null;

    /**
     * Sequence number of the WBS. Last sequence will be selected if not specified
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $SequenceNumber = null;

    /**
     * Startdate of the WBS. If it is a deliverable then StartDate is the earliest StartDate of its children
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $StartDate = null;

    /**
     * Supplier of the item. Only for expense
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Supplier = null;

    /**
     * Alert when exceeding this time quantity for WBS activity
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Method(HttpMethod::GET)]
    public null|float $TimeQuantityToAlert = null;

    /**
     * The type of project WBS. E.g: 1 = Deliverable, 2 = Activity, 3 = Expense
     *
     *
     * @var null|int Int16
     */
    #[EDM\Int16]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Type = null;
}