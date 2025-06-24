<?php

namespace PISystems\ExactOnline\Polyfill;

use PISystems\ExactOnline\Model\ExactEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

class ExactEventDispatcher implements EventDispatcherInterface, ListenerProviderInterface
{
    private array $listeners = [];

    /**
     * This does not deal with priorities, handle those before we get this far.
     *
     * @param string $event
     * @param callable $listener
     * @return $this
     */
    public function addEventListener(string $event, callable $listener) : self
    {
        if (!is_a($event, ExactEvent::class, true)) {
            throw new \InvalidArgumentException("Event must be an instance of ExactEvent");
        }

       $this->listeners[$event] ??= [];
       $this->listeners[$event][] = $listener;

       return $this;
    }

    public function dispatch(object $event) : object
    {
        if (!$event instanceof ExactEvent) {
            throw new \InvalidArgumentException("Event must be an instance of ExactEvent");
        }

        foreach ($this->getListenersForEvent($event) as $listener) {
            $event = $listener($event);

            if ($event->isPropagationStopped()) {
                return $event;
            }
        }

        return $event;
    }

    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->listeners as $event => $listeners) {
            if (is_a($event, $event, true)) {
                yield from $listeners;
            }
        }
    }
}