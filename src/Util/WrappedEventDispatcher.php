<?php

namespace PISystems\ExactOnline\Util;

use PISystems\ExactOnline\Model\Event;
use PISystems\ExactOnline\Polyfill\ExactEventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * Warning!
 *
 * Using the wrapper exposes a trivial `->lock()` bypass, as they now just have to attach to
 * the wrapped listener.
 * Use this only if you trust the wrapped dispatcher.
 *
 * Note: The wrapped dispatcher event handlers are called BEFORE the internal exact events handlers.
 *       This may cause certain events to not trigger properly if those handlers stop propagation early.
 */
class WrappedEventDispatcher extends ExactEventDispatcher
{
    private array $mocks = [];

    public function __construct(
        private readonly EventDispatcherInterface $wrappedDispatcher
    )
    {
        parent::__construct();
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

    public function hasListenersForEvent(object|string $event): bool
    {
        if (parent::hasListenersForEvent($event)) {
            return true;
        }

        if (is_string($event)) {

            // We cannot verify this, don't even try.
            if (!is_a($event, Event::class, true)) {
                return false;
            }

            try {
                // All events should survive instantiation without constructor.
                // Most dispatchers will only call get_class() or ::class, this this *SHOULD* be fine.
                $class = $this->mocks[$event] ??= (function() use ($event) {
                    $class = new \ReflectionClass($event);
                    return $class->newInstanceWithoutConstructor();

                })();

                if (!empty($this->wrappedDispatcher->getListenersForEvent($class))) {
                    return true;
                }
            } catch (\ReflectionException) {}

            return false;
        }

        if (!empty($this->wrappedDispatcher->getListenersForEvent($event))) {
            return true;
        }

        return false;
    }
}