<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Model\DataSource;
use Psr\Http\Message\RequestInterface;

class DataRequest extends AbstractConfiguredExactEvent
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