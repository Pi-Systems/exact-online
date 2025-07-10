<?php

namespace PISystems\ExactOnline\Util;

use PISystems\ExactOnline\Entity\Bulk\CRM\Accounts as BulkCRMAccounts;
use PISystems\ExactOnline\Entity\CRM\Accounts as CRMAccounts;
use PISystems\ExactOnline\Entity\Sales\SalesPriceListLinkedAccounts;
use PISystems\ExactOnline\Entity\Sync\CRM\Accounts as SyncCRMAccounts;
use PISystems\ExactOnline\Model\ExactAttributeOverridesInterface;


class AttributeOverrides implements ExactAttributeOverridesInterface
{
    /**
     * By default, the existing keys are:
     *  - edm               Only on properties
     *  - collection        Only on properties
     *  - exact_web         Only on properties
     *  - key               Only on classes
     *  - pagesize          Only on classes
     *  - endpoint          Only on classes
     *  - method::get
     *  - method::post
     *  - method::put
     *  - method::delete
     *
     * Anything registered here REPLACES existing, or ADDS if the key does not exists.
     *
     * @return array[]
     */
    private function overrides(): array
    {
        $cs = ['edm' => 'EDM\\UTF8CodeString'];
        return [
            CRMAccounts::class . '::$Code' => $cs,
            BulkCRMAccounts::class . '::$Code' => $cs,
            SyncCRMAccounts::class . '::$Code' => $cs,
            SalesPriceListLinkedAccounts::class . '::$Code' => $cs,
        ];
    }

    public function hasOverrides(string $point): bool
    {
        return array_key_exists($point, $this->overrides());
    }

    public function override(string $point, array $existing): array
    {
        $override = $this->overrides()[$point] ?? null;
        if (!$override) {
            throw new \RuntimeException("Overrides for  '$point' do not exist, (Check hasOverrides first).");
        }

        return array_merge($existing, $override);
    }
}