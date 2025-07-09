<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Model\ExactEvent;

class EventDispatcherUnloading extends ExactEvent
{

    public function __construct(
        public readonly \DateTimeImmutable $time = new \DateTimeImmutable()
    )
    {

    }
}