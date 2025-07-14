<?php

namespace PISystems\ExactOnline\Command;

use PISystems\ExactOnline\Entity\Documents\DocumentTypes;
use PISystems\ExactOnline\Exact;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListDocumentTypesExactCommand extends Command
{
    public function __construct(private readonly Exact $exact)
    {
        parent::__construct('exact:document-types');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table->setHeaders([
            'ID',
            'Description',
            'Category',
            'Viewable',
            'Creatable',
            'Updatable',
            'Deletable',
        ]);

        $meta = DocumentTypes::meta();
        $query = $this->exact->matching($meta);
        $c = 0;
        /** @var DocumentTypes $type */
        foreach ($query as $type) {
            $c++;
            $table->addRow([
                $type->ID,
                $type->Description,
                $type->TypeCategory,
                $type->DocumentIsViewable ? '✔️' : '❌',
                $type->DocumentIsCreatable ? '✔️' : '❌',
                $type->DocumentIsUpdatable ? '✔️' : '❌',
                $type->DocumentIsDeletable ? '✔️' : '❌',
            ]);

            if ($c > 0 && $c % $meta->pageSize === 0) {
                $table->render();
            }
        }

        $table->render();
        $output->writeln("Found {$c} document types.");

        return self::SUCCESS;
    }
}