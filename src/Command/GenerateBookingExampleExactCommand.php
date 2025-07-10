<?php

namespace PISystems\ExactOnline\Command;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Model\Exact\Crm\Accounts;
use PISystems\ExactOnline\Model\Exact\Financial\GLAccounts;
use PISystems\ExactOnline\Model\Exact\Salesentry\SalesEntries;
use PISystems\ExactOnline\Model\Exact\Salesentry\SalesEntryLines;
use PISystems\ExactOnline\Model\Expr\Criteria;
use PISystems\ExactOnline\Polyfill\Validation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateBookingExampleExactCommand extends Command
{
    private array $accounts = [];

    public function __construct(
        private readonly Exact $exact,
    )
    {
        parent::__construct('exact:generate-booking');
    }

    protected function configure()
    {
        $this->addArgument('number', InputArgument::REQUIRED, 'What invoice number should we attach this to?');
        $this->addArgument('journal', InputArgument::REQUIRED, 'What journal should we attach this to?');
        $this->addArgument('customer', InputArgument::REQUIRED, 'What customer should we attach this to? (code)');
        $this->addArgument('csv', InputArgument::REQUIRED, 'Read line data from (this) csv file (See CommandExamples.md#csv for formatting, csv on purpose to allow \'easy\' changes.)');
        $this->addOption('Description', 'd', InputOption::VALUE_REQUIRED, 'The invoice description');
        $this->addOption('type', 't', InputOption::VALUE_REQUIRED, 'Type of line', 20);
        $this->addOption('date', null, InputOption::VALUE_REQUIRED, 'ISO8601/ATOM format, Date of the transaction', date('c'));
        $this->addOption('payment-condition', 'p', InputOption::VALUE_REQUIRED, 'Use this payment condition.', '00');
        $this->addOption('csv-separator', 's', InputOption::VALUE_REQUIRED, 'CSV is separated (;)', ';');
        $this->addOption('ref', 'r', InputOption::VALUE_REQUIRED, 'The internal reference to this invoice/booking (Defaults to number if left blank)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $number = (int)$input->getArgument('number');
        $journal = $input->getArgument('journal');
        $type = (int)$input->getOption('type') ?: 20;
        $customer = (int)$input->getArgument('customer');
        $separator = $input->getOption('csv-separator');
        $date = $input->getOption('date');
        $ref = $input->getOption('ref') ?: $number;

        try {
            $date = \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $date);
        } catch (\Exception $e) {
            $output->writeln("Could not parse given date element {$date}, please ensure the date follows ISO8601/ATOM format.");
        }
        $description = $input->getOption('Description') ?: "Test booking using PISystems\ExactOnline on ".$date->format(\DateTimeInterface::ATOM);

        $csv = $input->getArgument('csv');
        if (!is_file($csv) || !is_readable($csv)) {
            $output->writeln("<error>File {$csv} not exists.</error>");
            return self::INVALID;
        }

        $paymentCondition = $input->getOption('payment-condition') ?: '00';

        $customerGuid = $this->exact->findOneBy(
            Accounts::class,
            Criteria::create()->where(
                Criteria::expr()->eq('Code', $customer),
            )
                ->select('ID')
        )?->ID;

        if (!$customerGuid) {
            $output->writeln("<error>Account with account code {$customer} not found (This example does not create one).</error>");
            return self::INVALID;
        }

        $entry = new SalesEntries();
        $entry->EntryID = $this->exact->uuid();
        $entry->EntryDate = $date;
        $entry->ReportingPeriod = $date->format('m');
        $entry->ReportingYear = $date->format('Y');
        $entry->Journal = $journal;
        $entry->PaymentCondition = $paymentCondition;
        $entry->InvoiceNumber = $number;
        $entry->Type = $type;
        $entry->Created = $date;
        $entry->Customer = $customerGuid;
        $entry->Description = $description;
        $entry->SalesEntryLines = [];
        $entry->YourRef = $ref;

        // Read lines from csv
        $reader = fopen($csv, 'r');
        $i = 0;
        $total = 0.0;
        while (($row = fgetcsv($reader, separator: $separator)) !== false) {
            if ($i++ === 0 || empty($row)) {
                continue;
            } // Skip header
            [$vatCode, $GLAccount, $Description, $Amount, $GLType] = $row;

            if (!Validation::is_guid($GLAccount)) {
                $GLAccount = $this->accounts[$GLAccount] ??= (fn() => $this->exact->findOneBy(
                    GLAccounts::class,
                    Criteria::create()->where(
                        Criteria::expr()->eq('Code', $GLAccount),
                    )
                        ->select('ID')
                )?->ID)();
            }

            $fAmount = (float)$Amount;
            $line = new SalesEntryLines();
            $line->EntryID = $entry->EntryID;
            $line->LineNumber = $i;
            $line->VATCode = $vatCode;
            $line->GLAccount = $GLAccount;
            $line->Description = $Description;
            $line->AmountFC = $fAmount;

            $total += $fAmount;
            $entry->SalesEntryLines[] = $line;
        }
        fclose($reader);
        $entry->AmountFC = $total;

        $created = $this->exact->create($entry);
        $output->writeln(
            $created
            ? "<info>Entry created</info>"
            : "<error>Entry not created</error>"
        );

        return $created ? self::SUCCESS : self::FAILURE;
    }
}