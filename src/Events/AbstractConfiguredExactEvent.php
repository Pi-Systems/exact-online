<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Model\ExactEvent;

abstract class AbstractConfiguredExactEvent extends ExactEvent
{
    public function __construct(
        public readonly Exact $exact
    )
    {

    }
}