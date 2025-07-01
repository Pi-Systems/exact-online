<?php

namespace PISystems\ExactOnline\Polyfill;

use PISystems\ExactOnline\Model\ExactEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

class ExactEventDispatcher implements EventDispatcherInterface, ListenerProviderInterface
{
    private array $listeners = [];
    private bool $locked = false;

    /**
     * This does not deal with priorities, handle those before we get this far.
     *
     * @param string $event
     * @param callable $listener
     * @return $this
     */
    public function addEventListener(string $event, callable $listener) : self
    {
        if ($this->locked) {
            throw new \LogicException("Cannot add listeners, the dispatcher has been locked.");
        }

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
            $listener($event);

            if ($event->isPropagationStopped()) {
                return $event;
            }
        }

        return $event;
    }

    public function hasListenersForEvent(object|string $event): bool
    {
        $class = $event instanceof ExactEvent ? $event::class : $event;
        return null !== $this->listeners[$class] && !empty($this->listeners[$event::class]);
    }

    public function getListenersForEvent(object $event): iterable
    {
        if (!isset($this->listeners[$event::class])) {
            return [];
        }

        return new \ArrayIterator($this->listeners[$event::class]);
    }

    /**
     * Once locked, addEventListener will no longer accept any additions.
     *
     * Lock will be automatically called once exact attaches itself to the ExactRuntimeConfiguration.
     * This is not meant to 'secure' data.
     * But meant to ensure the flow is not suddenly altered during operation.
     *
     * If someone wants to extract the credentials, there will always be a way.
     * *Looking at you, \ReflectionProperty*
     *
     * @return static
     */
    public function lock() : static
    {
        $this->locked = true;
        return $this;
    }

    public function isLocked() : bool
    {
        return $this->locked;
    }
}
