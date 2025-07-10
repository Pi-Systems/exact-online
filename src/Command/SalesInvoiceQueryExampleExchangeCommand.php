<?php

namespace PISystems\ExactOnline\Command;

use PISystems\ExactOnline\Entity\Sync\SalesInvoice\SalesInvoices as SalesInvoice;
use PISystems\ExactOnline\Exact;
use PISystems\ExactOnline\Model\Expr\Criteria;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SalesInvoiceQueryExampleExchangeCommand extends Command
{
    public function __construct(private readonly Exact $exact)
    {
        parent::__construct('exact:sales-invoice-query');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $invoices = iterator_to_array($this->exact->matching(
            SalesInvoice::class,
            Criteria::create()->where(
                Criteria::expr()->gt('Timestamp', 5)
            )->select(['AmountDC','Creator'])
        ));

        $count = count($invoices);
        $output->writeln("We found a total of {$count} SalesInvoices.");

        /** @var SalesInvoice $invoice */
        foreach ($invoices as $k =>  $invoice) {
            $output->writeln(sprintf("[%s] %s (%s)", $k, $invoice->AmountDC, $invoice->Creator));
        }

        return self::SUCCESS;
    }


}