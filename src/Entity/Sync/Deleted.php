<?php

namespace PISystems\ExactOnline\Entity\Sync;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * The sync api's and the delete api should be used together to keep the data between Exact Online and a 3rd party application the same.
 *
 *
 * The sync api's are all based on row versioning and because of that it is guaranteed to be unique. Every time an existing record is changed or a new record is inserted, the row versioning value is higher than the highest available value at that time. When retrieving records via these api's also a timestamp value is returned. The highest timestamp value of the records returned should be stored on client side. Next time records are retrieved, the timestamp value stored on client side should be provided as parameter. The api will then return only the new and changed records. Using this method is more reliable than using modified date, since it can happen that multiple records have the same modified date and therefore same record can be returned more than once. This will not happen when using timestamp.
 *
 *
 * The sync api's are also developed to give best performance when retrieving records. Because of performance and the intended purpose of the api's, only the timestamp field is allowed as parameter.
 *
 *
 * The sync api's however do not show deleted records. Therefore the deleted api is introduced. Via the delete api you can get the deleted records per entity. By using the delete api and the sync api together you can keep the data the same between Exact Online and a 3rd party application.
 * In case the division is moved to another database in Exact Online the timestamp values will be reset. Therefore, after a division is moved all data needs to be synchronized again in order to get the new timestamp values. To see if a division was moved, the /api/v1/{division}/system/Divisions can be used. The property DivisionMoveDate indicated at which date a division was moved and this date can be used to determine if it is needed to synchronize all data again.
 *
 *
 * The API has two important key fields, the Timestamp and the ID. The ID should be used to uniquely identify the record and will never change. The Timestamp is used to get new or changed records in an efficient way and will change for every change made to the record.
 *
 *
 * Note: the log of records deleted is saved for 2 months. Data older than 2 months is automatically deleted and therefore also not available anymore via the deleted API.
 *
 *
 * The timestamp value returned has no relation with actual date or time. As such it cannot be converted to a date\time value. The timestamp is a rowversion value.
 *
 *
 * When you use the sync or delete api for the first time for a particular division, filter on timestamp greater than 1.
 *
 *
 *
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SyncDeleted
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/sync/Deleted", "Deleted")]
#[Exact\Method(HttpMethod::GET)]
class Deleted extends DataSource
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
     * UserID of person who deleted record
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $DeletedBy = null;

    /**
     * Deleted date
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    public null|\DateTimeInterface $DeletedDate = null;

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
     * Entity key
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $EntityKey = null;

    /**
     * Entity Types:    1= TransactionLines     * 2= Accounts     * 3= Addresses     * 4= Attachments     * 5= Contacts     * 6= Documents     * 7= GLAccounts     * 8= ItemPrices     * 9= Items     * 10= PaymentTerms     * 12= SalesOrders (This entity is going to be removed. Please refer to the new entity SalesOrderHeaders, SalesOrderLines.)     * 13= SalesInvoices     * 14= TimeCostTransactions     * 15= StockPositions     * 16= GoodsDeliveries     * 17= GoodsDeliveryLines     * 18= GLClassifications     * 19= ItemWarehouses     * 20= StorageLocationStockPositions     * 21= Projects     * 22= PurchaseOrders     * 23= Subscriptions     * 24= SubscriptionLines     * 25= ProjectWBS     * 26= ProjectPlanning     * 27= LeaveAbsenceHoursByDay     * 28= SerialBatchNumbers     * 29= StockSerialBatchNumbers     * 30= ItemAccounts     * 31= DiscountTables     * 32= SalesOrderHeaders     * 33= SalesOrderLines     * 34= QuotationHeaders     * 35= QuotationLines     * 36= ShopOrders     * 37= ShopOrderMaterialPlans     * 38= ShopOrderRoutingStepPlans     * 39= Schedules     * 40= ScheduleEntries     * 41= ItemStorageLocations     * 42= Employees     * 43= Employments     * 44= EmploymentContracts     * 45= EmploymentOrganizations     * 46= EmploymentCLAs     * 47= EmploymentSalaries     * 48= BankAccounts     * 49= EmploymentTaxAuthoritiesGeneral     * 50= ShopOrderPurchasePlanning     * 51= ShopOrderSubOrders     * 53= RequirementIssues     * 54= BillOfMaterialMaterials     * 55= BillOfMaterialVersions
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $EntityType = null;

    /**
     * Primary key
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $ID = null;
}