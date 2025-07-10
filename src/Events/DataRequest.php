<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Exact;
use PISystems\ExactOnline\Model\DataSource;
use Psr\Http\Message\RequestInterface;

class DataRequest extends AbstractConfiguredEvent
{
    public function __construct(
        Exact $exact,
        /**
         * @var RequestInterface
         */
        public RequestInterface $request,
        public ?DataSource $dataSource
    ) {
        parent::__construct($exact);
    }
}