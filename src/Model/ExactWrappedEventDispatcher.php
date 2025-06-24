<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Polyfill\ExactEventDispatcher;
use Psr\EventDispatcher\StoppableEventInterface;

class ExactWrappedEventDispatcher extends ExactEventDispatcher
{
    public function __construct(
        private readonly ExactEventDispatcher $wrappedDispatcher
    )
    {
    }

    public function dispatch(object $event) : object
    {
        $this->wrappedDispatcher->dispatch($event);

        if (
            $event instanceof StoppableEventInterface && $event->isPropagationStopped()
        ) {
            return $event;
        }

        return parent::dispatch($event);
    }
}