<?php

namespace PISystems\ExactOnline\Builder;

use PISystems\ExactOnline\Events\AdministrationChange;
use Psr\Cache\CacheItemPoolInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;

class Exact
{
    public function __construct(
        public ?int $administration = null {
            get => $this->administration;
            set (?int $value) {
                if ($value !== $this->administration) {
                    $administrationSwitchEvent = new AdministrationChange($this, $this->administration, $value);
                    $this->dispatcher->dispatch($administrationSwitchEvent);
                    if ($administrationSwitchEvent->isPropagationStopped()) {
                        return;
                    }
                }
                $this->administration = $value;
            }
        },
        protected CacheItemPoolInterface   $cache,
        protected RequestFactoryInterface  $requestFactory,
        protected ClientInterface          $client,
        protected LoggerInterface          $logger,
        protected EventDispatcherInterface $dispatcher

    )
    {

    }
}