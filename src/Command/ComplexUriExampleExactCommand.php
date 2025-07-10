<?php

namespace PISystems\ExactOnline\Command;

use PISystems\ExactOnline\Entity\FinancialTransaction\TransactionLines;
use PISystems\ExactOnline\Exact;
use PISystems\ExactOnline\Model\Expr\Criteria;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ComplexUriExampleExactCommand extends Command
{
    public function __construct(private readonly Exact $exact)
    {
        parent::__construct('exact:complex-uri');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Set exact to offline, if it ever has to contact the API we know we fucked up.
        $this->exact->offline = true;

        $meta = TransactionLines::meta();
        $criteria = Criteria::create($meta);
        $e = $criteria->expression();

        $criteria->where(
            $e->andX(
                $e->eq('Account', 12345),
                $e->neq('AccountCode', 'John'),
                $e->lt('AmountDC', 9999),
                $e->lte('DocumentNumber', 8888),
                $e->gt('AmountDC', 110.0),
                $e->gte('DocumentNumber', 1000),
                $e->in('GLAccount', [1, 2, 3, 4]),
                $e->notIn('GLAccount', [6, 7, 8]),
                $e->contains('Description', 'Some Description'),
                $e->startsWith('Description', 'Some'),
                $e->endsWith('Description', 'Description'),
                $e->lower('Description', 'some description'),
                $e->upper('Description', 'some description'),
                $e->substring('Description', 'ome', 1, 3),
                $e->orX(
                    $e->eq('Creator', 666),
                    $e->eq('Creator', 420),
                ),
                $e->andX(
                    $e->eq('Description', 'Some Description'),
                    $e->neq('Description', 'Not This Description'),
                )
            )
        );

        $uri = $this->exact->criteriaToUri($meta, $criteria);

        $output->writeln($uri);

        return self::SUCCESS;
    }


}