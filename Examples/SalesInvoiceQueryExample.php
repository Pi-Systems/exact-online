<?php

namespace PISystems;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Model\Exact\Sync\SalesInvoice\SalesInvoices as SalesInvoice;

/** @var Exact $exact */
$exact = include "ExactConstructorExample.php";

$invoices = iterator_to_array($exact->matching(
    SalesInvoice::class,
    'Timestamp gt 5',
    'AmountDC,Creator'
));

$count = count($invoices);
print "We found a total of {$count} SalesInvoices.\n";

/** @var SalesInvoice $invoice */
foreach ($invoices as $invoice) {
    var_dump($invoice);
}
