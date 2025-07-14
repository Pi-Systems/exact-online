<?php

namespace PISystems\ExactOnline\Entity\Sync\Financial;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
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
 * Note: This endpoint does not support query { $select=* }. Please query with a selection of one or more of the resource properties.
 *
 *
 *
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=SyncFinancialGLClassifications
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/sync/Financial/GLClassifications", "Financial/GLClassifications")]
#[Exact\Method(HttpMethod::GET)]
class GLClassifications extends DataSource
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
     * Abstract elements are only used in presentation linkbases to group other elements. They are not supposed to be used in instance documents
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $Abstract = null;

    /**
     * Only used for amount concepts: in that case either 'debit' or 'credit'
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Balance = null;

    /**
     * The Code is unique
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
     * Description of the element. Note that this description is only used for division-specific taxonomies (or reporting schemes).
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Description = null;

    /**
     * Division is optional. For taxonomies of Taxonomies.Type = 0 (general taxonomies), the Division is empty. For division specific taxonomies it is mandatory
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Division = null;

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
     * Obsolete
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $IsTupleSubElement = null;

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
     * The Name is unique in the namespace
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Name = null;

    /**
     * Determines whether usage of the element is mandatory in an instance document
     *
     *
     * @var null|bool
     */
    #[EDM\Boolean]
    #[Exact\Method(HttpMethod::GET)]
    public null|bool $Nillable = null;

    /**
     * Parent element for reporting schemes. In a reporting scheme, an element can have only one parent. This column is only used for reporting schemes. Note that in a real taxonomy, elements can have multiple parents.
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Parent = null;

    /**
     * Only used for item concepts: either 'instant' or 'duration'. 'instant' is used for balance types of concepts, 'duration' is used for P&L types of concepts
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $PeriodType = null;

    /**
     * Mostly import sutstitutiongroup is xbrli:tuple, which makes the element a tuple concept. The default substitutiongroup for item concepts is xbrli:item
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $SubstitutionGroup = null;

    /**
     * Namespace of the element
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $TaxonomyNamespace = null;

    /**
     * Description of TaxonomyNamespace
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $TaxonomyNamespaceDescription = null;

    /**
     * Type of the element
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    public null|string $Type = null;
}