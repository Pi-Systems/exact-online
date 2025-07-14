<?php

namespace PISystems\ExactOnline\Entity\Manufacturing;

use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\DataSource;

/**
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=ManufacturingManufacturingSettings
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
#[Exact\PageSize(60)]
#[Exact\Endpoint("/api/v1/{division}/manufacturing/ManufacturingSettings", "ManufacturingSettings")]
#[Exact\Method(HttpMethod::GET)]
class ManufacturingSettings extends DataSource
{

    /**
     * This division.
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Key]
    #[Exact\Required]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $Division = null;

    /**
     * What is the division's main inventory method? Standard=3,Average=4
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $InventoryMainMethod = null;

    /**
     * What is the division's sub inventory method? Perpetual=1,NonPerpetual=2,AngloSaxon=3
     *
     *
     * @var null|int Int32
     */
    #[EDM\Int32]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $InventorySubMethod = null;

    /**
     * Does the current division allow negative stock?
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $NegativeStockIsAllowed = null;

    /**
     * Are serial numbers mandatory in this division?
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $SerialNumbersAreMandatory = null;

    /**
     * This property is obsolete. Should ShopOrderMaterialPlans with Backflush=True be shown within Smart Shop Floor?
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ShowBackflushMaterials = null;

    /**
     * This property is obsolete. Should ShopOrderMaterialPlans linked to a SubOrder be shown within Smart Shop Floor?
     *
     *
     * @var null|int A single byte of data.
     */
    #[EDM\Byte]
    #[Exact\Method(HttpMethod::GET)]
    public null|int $ShowSubOrderMaterials = null;
}