<?php

namespace PISystems\ExactOnline\Command;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Model\Exact\Financialtransaction\TransactionLines;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FinancialTransactionLinesQueryExampleExactCommand extends Command
{
    public function __construct(private readonly Exact $exact)
    {
        parent::__construct('exact:financial-transaction-lines-query');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entries = iterator_to_array($this->exact->matching(
            TransactionLines::class,
        ));

        $count = count($entries);
        $output->writeln("We found a total of {$count} Lines.");

        /** @var TransactionLines $entry */
        foreach ($entries as $entry) {
            $output->writeln(
                sprintf(
                    '[%s:%s] %s (%s)',
                    $entry->ID,
                    $entry->InvoiceNumber,
                    $entry->AmountDC,
                    $entry->Creator
                )
            );
        }

        return self::SUCCESS;
    }
}