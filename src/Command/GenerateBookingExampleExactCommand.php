<?php

namespace PISystems\ExactOnline\Command;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\Exact\Crm\Accounts;
use PISystems\ExactOnline\Model\Exact\Financialtransaction\TransactionLines;
use PISystems\ExactOnline\Model\Exact\Salesentry\SalesEntries;
use PISystems\ExactOnline\Model\Expr\Criteria;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
        $this->addArgument('number', InputArgument::REQUIRED, 'What invoice number should we attach this to?');
        $this->addArgument('journal', InputArgument::REQUIRED, 'What journal should we attach this to?');
        $this->addArgument('account', InputArgument::REQUIRED, 'What account should we attach this to? (code)');
        $this->addArgument('csv', InputArgument::REQUIRED, 'Read line data from (this) csv file (See CommandExamples.md#csv for formatting, csv on purpose to allow \'easy\' changes.)');
        $this->addOption('type', 't',  InputOption::VALUE_REQUIRED, 'Type of line', 20 );
        $this->addOption('date', null, InputOption::VALUE_REQUIRED, 'ISO8601/ATOM format, Date of the transaction', date('c'));
        $this->addOption('payment-condition', 'p', InputOption::VALUE_REQUIRED, 'Use this payment condition.');
        $this->addOption('csv-separator', 's', InputOption::VALUE_REQUIRED, 'CSV is separated (;)', ';');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $meta = TransactionLines::meta();

        $number = $input->getArgument('number');
        $journal = $input->getArgument('journal');
        $csv = $input->getArgument('csv');
        $type = (int)$input->getOption('type') ?: 20;
        $account = $input->getArgument('account');
        $separator = $input->getOption('csv-separator');

        if (!is_file($csv) || !is_readable($csv)) {
            $output->writeln("<error>File {$csv} not exists.</error>");
            return self::INVALID;
        }


        $date =$input->getOption('date');
        try {
            $date = \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $date);
        } catch (\Exception $e) {
            $output->writeln("Could not parse given date element {$date}, please ensure the date follows ISO8601/ATOM format.");
        }

        $paymentCondition = $input->getOption('payment-condition') ?: 0;

        /** @var Accounts|null $customer */
        $customer = $this->exact->findOneBy(
            Accounts::class,
            Criteria::create()
                ->from(Accounts::class)
                ->select('ID')
                ->where(
                    Criteria::expr()->eq('Code', $account),
                )
        )?->ID;

        if (!$customer) {
            $output->writeln("<error>Account with account code {$account} not found (This example does not create one).</error>");
        }


        $lines = [$line0 = new SalesEntries()];

        $line0->EntryID = $this->exact->uuid();
        $line0->InvoiceNumber = $number;
        $line0->PaymentReference = $paymentCondition;
        $line0->Type = $type;
        $line0->Created = $date;
        $line0->Customer = $customer;
//        $line0->LineType = 0;
//        $line0->Date = $date;
//        $line0->LineNumber = 0;
//        $line0->AccountCode = $accountCode;
//        $line0->AccountName = $accountName;

        // Read lines from csv
        $reader = fopen($csv, 'r');
        $i = 0;
        $total = 0.0;
        while (($row = fgetcsv($reader, separator: $separator)) !== false) {
            if ($i++ === 0) { continue; } // Skip header
            [$vatType,$GLAccount,$Description,$Amount] = $row;
            $fAmount = (float)$Amount;
            $line = new TransactionLines();
            $line->InvoiceNumber = $number;
            $line->LineNumber = $i;
            $line->VATType = $vatType;
            $line->GLAccount = $GLAccount;
            $line->Description = $Description;
            $line->AmountFC = $fAmount;
            $line->AmountVATBaseFC = $fAmount;
            $line->Currency = 'EUR';
            $line->Type = $type;
            $line->Date = $date;

            $total+= $fAmount;
            $lines[] = $line;
        }
        fclose($reader);
        $line0->AmountFC = $total;

        foreach ($lines as $k => $line) {
            $output->writeln(sprintf("[%d] %s (%f)", $k, $line->InvoiceNumber, $line->AmountFC));
            var_dump($meta->deflate($line, HttpMethod::PUT, true));
        }

        return self::SUCCESS;
    }


}
