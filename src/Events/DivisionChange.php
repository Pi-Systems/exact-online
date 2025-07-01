<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Builder\Exact;

class DivisionChange extends AbstractConfiguredExactEvent
{

    /**
     * @param Exact $exact
     * @param int|null $current
     * @param int|null $new
     */
    public function __construct(
        Exact $exact,
        public readonly ?int $current,
        public readonly ?int $new)
    {
        parent::__construct($exact);
    }
}
