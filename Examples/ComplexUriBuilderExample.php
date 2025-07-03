<?php

namespace PISystems;

use PISystems\ExactOnline\Builder\Exact;
use PISystems\ExactOnline\Model\Exact\Financialtransaction\TransactionLines;
use PISystems\ExactOnline\Model\Expr\Criteria;

/**
 * This query is complete nonsense.
 * But it does show all options.
 */

/** @var Exact $exact */
$exact = include "ExactConstructorExample.php";

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

$uri = $exact->criteriaToUri(TransactionLines::meta(), $criteria);

print $uri;
