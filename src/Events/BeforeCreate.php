<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Model\ExactEvent;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;

class BeforeCreate extends ExactEvent
{

    public function __construct(
        public ?int $administration = null,
        public CacheItemPoolInterface    $cache,
        public RequestFactoryInterface   $requestFactory,
        public ClientInterface           $client,
        public LoggerInterface           $logger,

    )
    {

    }

}