<?php

namespace PISystems\ExactOnline\Entity\Inventory;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to process assembly order.
 *
 * Finishing assembly orders means that you can receive the assembly order. When an assembly order is received, the stock of the assembled
 * item will increase and the stock for the parts it is built from will decrease.
 *
 * When using the BatchNumbers or SerialNumbers, the ParentID property for the part items (StockTransactionType = 165 or StockTransactionType = 166 (disassembly)) must be the same
 * as the ID property for assembled item (StockTransactionType = 160 or StockTransactionType = 161 (disassembly)).
 * The ID or ParentID for StockBatchNumbers or
 * StockSerialNumbers will be regenerated during finishing assembly order.
 *
 * Example of JSON body:
 *
 *
 *
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=InventoryFinishAssemblyOrder
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/inventory/FinishAssemblyOrder", "FinishAssemblyOrder")]
#[Exact\Method(HttpMethod::POST)]
class FinishAssemblyOrder extends DataSource
{

    /**
     * A guid that is the unique identifier of the assembly order
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $AssemblyOrder = null;

    /**
     * Storage location of assembled item
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $AssembledItemStorageLocation = null;

    /**
     * Date of the assembly order is initiated
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|\DateTimeInterface $AssemblyDate = null;

    /**
     * The collection of batch numbers that belongs to the assembled and part items in the assembly order
     *
     *
     * @var ?array A collection of FinishAssemblyOrder\BatchNumbers
     */
    #[EDM\Collection(BatchNumbers::class, 'BatchNumbers')]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public ?array $BatchNumbers = null;

    /**
     * Description of assembly order
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
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
     * Finish order quantity of assembly order
     *
     *
     * @var null|float Double
     */
    #[EDM\Double]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|float $FinishOrderQuantity = null;

    /**
     * Notes of assembly order
     *
     *
     * @var null|string
     */
    #[EDM\UTF8String]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $Notes = null;

    /**
     * The collection of serial numbers that belongs to the assembled and part items in the assembly order
     *
     *
     * @var ?array A collection of FinishAssemblyOrder\SerialNumbers
     */
    #[EDM\Collection(SerialNumbers::class, 'SerialNumbers')]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public ?array $SerialNumbers = null;
}