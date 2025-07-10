<?php

namespace PISystems\ExactOnline\Polyfill;

use PISystems\ExactOnline\Events\FileUpload;
use PISystems\ExactOnline\Model\Event;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

class ExactEventDispatcher implements EventDispatcherInterface, ListenerProviderInterface
{
    private array $listeners;
    private bool $locked = false;

    public readonly array $extensions;
    public readonly bool $blacklist;

    public function __construct()
    {
        $this->listeners = $this->getDefaultEvents();
        $validationList = $_ENV['EXACT_FILE_EXTENSIONS'] ?? '';

        if (!empty($validationList)) {
            if (str_starts_with($validationList, '!')) {
                $this->blacklist = true;
                $validationList = substr($validationList, 1);
            } else {
                $this->blacklist = false;
            }

            if (!empty($validationList)) {
                $this->extensions = array_map('trim', explode(',', $validationList));
                return;
            }
        } else {
            $this->blacklist = false;
        }
        $this->extensions = [];
    }

    public function getDefaultEvents(): array
    {
        return [
            FileUpload::class => [
                $this->validateFileExtension(...),
                $this->validateFileSize(...)
            ],
        ];
    }

    public function validateFileExtension(FileUpload $event): void
    {
        if (empty($this->extensions)) {
            if ($this->blacklist) {
                return;
            }
        }

        $found = in_array(
            $event->file->getExtension(),
            $this->extensions,
        );

        if ($this->blacklist ? $found : !$found) {
            if ($this->blacklist) {
                $event->deny("File extension is not allowed. Disallowed extensions are: " . implode(", ", $this->extensions) . ".");
                return;
            }

            if (empty($this->extensions)) {
                $event->deny("File extension is not allowed, no file extensions are whitelisted.");
                return;
            }
            $event->deny("File extension is not allowed. Allowed extensions are: " . implode(", ", $this->extensions) . ".");
        }


    }

    public function validateFileSize(FileUpload $event): void
    {
        $max = $_ENV['EXACT_FILE_MAX_SIZE'] ?? 0;

        // Disabled
        if ($max < 0) {
            return;
        }

        if ($event->file->getSize() > $max) {
            $event->deny("File size is too large. Maximum size is {$max} bytes.");
        }
    }

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

        if (!is_a($event, Event::class, true)) {
            throw new \InvalidArgumentException("Event must be an instance of ExactEvent");
        }

       $this->listeners[$event] ??= [];
       $this->listeners[$event][] = $listener;

       return $this;
    }

    public function dispatch(object $event) : object
    {
        if (!$event instanceof Event) {
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
        $class = $event instanceof Event ? $event::class : $event;
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