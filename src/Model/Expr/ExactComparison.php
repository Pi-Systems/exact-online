<?php

namespace PISystems\ExactOnline\Model\Expr;

use Doctrine\Common\Collections\Expr\Comparison;

/**
 * Comparison of a field with a value by the given operator.
 */
class ExactComparison extends Comparison
{
    final public const string LOWER = 'lower';
    final public const string UPPER = 'upper';
    final public const string SUBSTRING = 'substring';
}
