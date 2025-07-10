<?php

namespace PISystems\ExactOnline\Model;

use Psr\EventDispatcher\StoppableEventInterface;

class Event implements StoppableEventInterface
{
    /** @noinspection PhpGetterAndSetterCanBeReplacedWithPropertyHooksInspection No, it can't, it's part of the interface. */
    protected bool $propagationStopped = false;

    public function stopPropagation() : self
    {
        $this->propagationStopped = true;
        return $this;
    }

    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }
}