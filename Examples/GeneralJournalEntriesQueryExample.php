<?php
namespace PISystems;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Model\Exact\Generaljournalentry\GeneralJournalEntries;
use PISystems\ExactOnline\Model\Exact\Sync\SalesInvoice\SalesInvoices as SalesInvoice;

/** @var Exact $exact */
$exact = include "ExactConstructorExample.php";

$entries = iterator_to_array($exact->matching(
    GeneralJournalEntries::class,
));

$count = count($entries);
print "We found a total of {$count} JournalEntries.\n";

/** @var SalesInvoice $invoice */
foreach ($entries as $entry) {
    var_dump($entry);
}
