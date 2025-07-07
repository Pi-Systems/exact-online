<?php

namespace PISystems\ExactOnline\Command;

use PISystems\ExactOnline\Builder\Exact;
use Symfony\Component\Console\Command\Command;

class GenerateBookingExampleExactCommand extends Command
{
    public function __construct(
        private readonly Exact $exact,
    )
    {
        parent::__construct('exact:examples:generate-booking');
    }

    protected function configure()
    {
    }


}
