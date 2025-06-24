<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Builder\Exact;

/**
 * Disabling propagation will also stop the value from being overwritten.
 * Use with caution, this can lead to some serious trouble.
 */
class AdministrationChange extends AbstractConfiguredExactEvent
{
    public function __construct(
        Exact $exact,
        public readonly ?int $currentValue,
        public ?int $newValue
    )
    {
        parent::__construct($exact);
    }
}