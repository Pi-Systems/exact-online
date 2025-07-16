<?php

namespace PISystems\ExactOnline\Model\Expr;

use Doctrine\Common\Collections\Expr\Comparison;

/**
 * Comparison of a field with a value by the given operator.
 * Note: There are a bunch more methods/function available for oData.
 * These are not implemented here, and may not ever appear here.
 */
class ExactComparison extends Comparison
{
    final public const string LOWER = 'lower';
    final public const string UPPER = 'upper';
    final public const string SUBSTRING = 'substring';
    /** @var string Case-Insentive version of contains, leveraging tolower function */
    final public const string ICONTAINS = 'icontains';
    /** @var string Writes contents of as-is, value is discarded. */
    final public const string RAW = 'raw';
}