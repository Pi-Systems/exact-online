<?php

namespace PISystems\ExactOnline\Command;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Model\Exact\Documents\DocumentAttachments;
use PISystems\ExactOnline\Model\Exact\Documents\Documents;
use PISystems\ExactOnline\Model\Exact\SalesEntry\SalesEntries;
use PISystems\ExactOnline\Model\Expr\Criteria;
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
        parent::__construct('exact:upload');
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::OPTIONAL, 'The file to upload');
        $this->addOption('document', 'd', InputOption::VALUE_REQUIRED, 'The document/invoice to upload to.');
        $this->addOption('invoice', 'o', InputOption::VALUE_REQUIRED, 'The invoice to use.');
        $this->addOption('file-name', null, InputOption::VALUE_REQUIRED, 'The name of the file to upload (Defaults to the basename of the file)');
        $this->addOption('id', null, InputOption::VALUE_REQUIRED, "The ID of this attachment.");
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->exact->offline = true;
        $documentID = $input->getOption('document');
        $invoiceID = $input->getOption('invoice');

        if ($documentID && !Validation::is_guid($documentID)) {
            throw new \InvalidArgumentException("Document/Invoice {$documentID} is not a valid GUID.");
        }

        if ($invoiceID && !Validation::is_guid($invoiceID)) {
            throw new \InvalidArgumentException("Invoice {$invoiceID} is not a valid GUID.");
        }

        if (!$documentID && !$invoiceID) {
            throw new \InvalidArgumentException("You must specify either a document or invoice.");
        }

        $file = $input->getArgument('file');
        if ($file) {
            $reason = null;
            if (!$this->exact->fileUploadAllowed(new \SplFileInfo($file), $reason)) {
                $output->writeln("<error>File upload denied.\n - {$reason}</error>");
                return self::INVALID;
            }
        }


        // If we were given an invoice ID, resolve it.
        $invoice = null;
        if ($invoiceID) {
            /** @var SalesEntries $invoice */
            $invoice = $this->exact->find(
                SalesEntries::class,
                $invoiceID,
                Criteria::create(SalesEntries::class)
                    ->select(['EntryID', 'Customer', 'Document', 'InvoiceNumber'])
            );

            if (!$invoice) {
                throw new \RuntimeException("Invoice {$invoiceID} not found.");
            }

            if ($invoice->Document) {
                if (!$documentID && $documentID !== $invoice->Document) {
                    throw new \RuntimeException("Invoice {$invoiceID} already has a different document attached, cowardly refusing to upload to a potentially wrong document.");

                }
                $documentID = $invoiceID->Document;
            } elseif (!$documentID) {
                if (!$invoice->EntryID) {
                    throw new \RuntimeException("Invoice {$documentID} has no ID, cannot create document.");
                }

                $document = new Documents();
                $document->Type = 10; // See ListDocumentTypesExactCommand
                $document->Subject = "Invoice {$invoice->InvoiceNumber}";
                $document->Account = $invoice->Customer;

                if (!($id = $this->exact->create($document))) {
                    throw new \RuntimeException("Failed to create document for invoice {$invoice->EntryID}.");
                }

                $output->writeln("<info>Created document {$documentID}.</info>");
                return $id;
            } else {
                if (!$this->exact->exists(Documents::class, $documentID)) {
                    throw new \LogicException("Document {$documentID} does not exist.");
                }
                $output->writeln("<info>Using document {$documentID}.</info>");
            }

        }

        if ($file && $invoice && $documentID) {
            $fileName = $input->getOption('file-name') ?? basename($file);

            $attachment = new DocumentAttachments();
            $attachment->Document = $documentID;
            $attachment->Attachment = base64_encode(file_get_contents($file));
            $attachment->FileName = $fileName;

            if (!$this->exact->create($attachment)) {
                throw new \RuntimeException("Failed to create/upload attachment for document {$documentID} for invoice {$invoice->InvoiceNumber}.");
            }

            // Upload the file to Exact
            $output->writeln("<info>Uploaded file {$file} to Exact document {$documentID} for invoice {$invoice->InvoiceNumber}.</info>");
        }

        $this->exact->offline = false;
        // Attach to invoice if needed
        if ($documentID && $invoiceID) {
            $invoice->Document = $documentID;
            if ($this->exact->update(
                $invoice,
                ['Document']
            )) {
                $output->writeln("<info>Attached document {$documentID} to invoice invoice {$invoice->InvoiceNumber}.</info>");
                return self::SUCCESS;
            } else {
                throw new \RuntimeException("Failed to attach document {$documentID} to invoice {$invoice->InvoiceNumber}.");
            }

        }

        return self::FAILURE;
    }


}