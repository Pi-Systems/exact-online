<?php

namespace PISystems\ExactOnline\Entity\Inventory;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * Use this endpoint to process existing draft warehouse transfer.
 * To use this endpoint please take note that:
 *
 * - For professional and premium package, if warehouse is using storage location, please make sure location assigned to all lines
 *
 * - Process warehouse transfer only support normal items, serial and batch item is not supported
 *
 * For more information about the warehouse transfer functionality in Exact Online, see Warehouse Transfer - New.
 *
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=InventoryProcessWarehouseTransfer
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/inventory/ProcessWarehouseTransfer", "ProcessWarehouseTransfer")]
#[Exact\Method(HttpMethod::POST)]
class ProcessWarehouseTransfer extends DataSource
{

    /**
     * A guid that is the unique identifier of the warehouse transfer
     *
     *
     * @var null|string Basic GUID type
     */
    #[EDM\Guid]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|string $TransferID = null;

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
     * Transfer Date
     *
     *
     * @var null|\DateTimeInterface
     */
    #[EDM\DateTime]
    #[Exact\Method(HttpMethod::GET)]
    #[Exact\Method(HttpMethod::POST)]
    public null|\DateTimeInterface $TransferDate = null;
}