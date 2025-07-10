<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Exact;
use PISystems\ExactOnline\Model\Event;

abstract class AbstractConfiguredEvent extends Event
{
    public function __construct(
        public readonly Exact $exact
    )
    {

    }
}