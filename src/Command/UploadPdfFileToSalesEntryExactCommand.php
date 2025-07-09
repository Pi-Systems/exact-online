<?php

namespace PISystems\ExactOnline\Command;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Model\Exact\Documents\Documents;
use PISystems\ExactOnline\Polyfill\Validation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UploadPdfFileToSalesEntryExactCommand extends Command
{
    public function __construct(
        private readonly Exact $exact,
    )
    {
        parent::__construct('exact:examples:generate-booking');
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'The file to upload');
        $this->addOption('id', 'i', InputOption::VALUE_REQUIRED, 'The ID of the file to upload (If left blank, one will be generated)');
        $this->addOption('finance-transaction-id', 't', InputOption::VALUE_REQUIRED, 'The financial transaction id to attach this to. (SalesInvoice API).');
        $this->addOption('sales-reference', 'sr', InputOption::VALUE_REQUIRED, 'The EXACT REFERENCE number of the SalesEntry to upload the file to (If not submitted, the file will still be uploaded, but not attached.)');
        $this->addOption('ctime', null, InputOption::VALUE_REQUIRED, 'Use this date instead of ctime (INT OR ATOM).');
        $this->addOption('folder', 'f', InputOption::VALUE_REQUIRED, 'The folder to upload the file to (Defaults to \'Sales\')');
        $this->addOption('category', 'c', InputOption::VALUE_REQUIRED, 'The category to upload the file to (Defaults to \'Sales\')');
        $this->addOption('overwrite', 'o', InputOption::VALUE_NONE, 'Overwrite the file if it already exists.', false);
    }


    protected function execute(InputInterface $input, OutputInterface $output): bool
    {
        $file = $input->getArgument('file');
        $id = $input->getOption('id') ?? $this->exact->uuid();
        $transactionId = $input->getOption('finance-transaction-id');

        $salesRef = $input->getOption('sales-reference');
        $folder = $input->getOption('folder');
        $category = $input->getOption('category');
        $overwrite = (bool)$input->getOption('overwrite');

        if (!file_exists($file)) {
            throw new \InvalidArgumentException("File {$file} does not exist.");
        }

        if (!is_readable($file)) {
            throw new \InvalidArgumentException("File {$file} is not readable.");
        }

        $ctime = $input->getOption('ctime') ?? new \SplFileInfo($file)->getCTime();

        if (is_numeric($ctime)) {
            $ctime = \DateTimeImmutable::createFromTimestamp((int)$ctime);
        }
        if (is_string($ctime)) {
            $ctime = \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $ctime);
        }

        $ctime??= new \DateTimeImmutable();

        $document = new Documents();
        $document->ID = $id;
        $document->Type  = 1; // TODO check options (Document/DocumentTypeCategories)
        $document->Body = base64_encode(file_get_contents($file));
        $document->Subject = basename($file);
        $document->DocumentDate = $ctime;
        $document->SalesInvoiceNumber = $salesRef;
        $document->FinancialTransactionEntryID = $transactionId;

        if ($folder) {
            if (!Validation::is_guid($folder)) {
                throw new \InvalidArgumentException("Folder {$folder} is not a valid GUID.");
            }
            $document->DocumentFolder = $folder;
        }

        if ($category) {
            if (!Validation::is_guid($category)) {
                throw new \InvalidArgumentException("Category {$category} is not a valid GUID.");
            }
            $document->Category = $category;
        }


        // Before we upload, check if the file already exists

        if($this->exact->exists(Documents::class, $id)) {
            $output->writeln('File already known by Exact.');

            if (!$overwrite) {
                $output->writeln('<error>--overwrite is not enabled, skipping.</error>');
                return self::INVALID;
            }

            return $this->exact->update($document) ? self::SUCCESS : self::FAILURE;;
        }


        return $this->exact->create($document) ? self::SUCCESS : self::FAILURE;
    }


}