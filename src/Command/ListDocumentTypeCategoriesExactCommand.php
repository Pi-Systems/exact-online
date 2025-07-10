<?php

namespace PISystems\ExactOnline\Command;

use PISystems\ExactOnline\Entity\Documents\DocumentTypeCategories;
use PISystems\ExactOnline\Exact;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListDocumentTypeCategoriesExactCommand extends Command
{
    public function __construct(private readonly Exact $exact)
    {
        parent::__construct('exact:document-type-categories');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table->setHeaders(['ID', 'Description', 'Created', 'Modified']);

        /** @var DocumentTypeCategories $documentTypeCategory */
        foreach ($this->exact->matching(DocumentTypeCategories::class) as $documentTypeCategory) {

            $table->addRow([
                $documentTypeCategory->ID,
                $documentTypeCategory->Description,
                $documentTypeCategory->Created?->format(\DATE_ATOM),
                $documentTypeCategory->Modified?->format(\DATE_ATOM),
            ]);
        }

        $table->render();

        return self::SUCCESS;
    }


}