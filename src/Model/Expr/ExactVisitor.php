<?php

namespace PISystems\ExactOnline\Model\Expr;

use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\Common\Collections\Expr\ExpressionVisitor;
use Doctrine\Common\Collections\Expr\Value;
use Override;
use PISystems\ExactOnline\Model\DataSourceMeta;
use PISystems\ExactOnline\Model\FilterEncodableDataStructure;

class ExactVisitor extends ExpressionVisitor
{
    public function __construct(private readonly DataSourceMeta $meta)
    {

    }

    #[Override] public function walkComparison(Comparison $comparison): string
    {
        $field = $comparison->getField();
        $value = $comparison->getValue()->getValue();

        // Even if a field does not exist, DO NOT BLOCK, exact does have virtual fields that we may not know about.
        // (Example: __next)
        if ($this->meta->hasProperty($field)) {
            $prop = $this->meta->properties[$field];
            $edm = $prop['type'] ?? null;
            if ($edm instanceof FilterEncodableDataStructure) {
                $value = $edm->encodeForFilter($value);
            }
        }

        return match ($op = $comparison->getOperator()) {
            Comparison::EQ, =>  $this->quoted('eq', $field, $value),
            Comparison::NEQ =>  $this->quoted('ne', $field, $value),
            Comparison::LT => $this->numerical('lt', $field, $value),
            Comparison::LTE => $this->numerical('le', $field, $value),
            Comparison::GT => $this->numerical('gt', $field, $value),
            Comparison::GTE => $this->numerical('ge', $field, $value),
            Comparison::IN => $this->in($field, $value),
            Comparison::NIN => $this->andExpressions([$this->in($field, $value)]) . ' eq false',
            Comparison::CONTAINS => $this->function('contains', $field, $value),
            Comparison::STARTS_WITH => $this->function('startswith', $field, $value),
            Comparison::ENDS_WITH =>  $this->function('endswith', $field, $value),
            ExactComparison::LOWER => $this->function('tolower', $field, $value),
            ExactComparison::UPPER => $this->function('toupper', $field, $value),
            ExactComparison::SUBSTRING => $this->substring($field, $value),
            default => throw new \InvalidArgumentException("Filter {$op} is not supported."),
        };
    }

    public function walkValue(Value $value)
    {
        return $value->getValue();
    }

    #[Override] public function walkCompositeExpression(CompositeExpression $expr): string
    {
        $expressionList = [];

        foreach ($expr->getExpressionList() as $child) {
            $expressionList[] = $this->dispatch($child);
        }

        return match ($expr->getType()) {
            CompositeExpression::TYPE_AND => $this->andExpressions($expressionList),
            CompositeExpression::TYPE_OR => $this->orExpressions($expressionList),
            CompositeExpression::TYPE_NOT => $this->notExpression($expressionList),
            default => throw new \RuntimeException('Unknown composite ' . $expr->getType()),
        };
    }

    public function andExpressions(array $expressions) : string
    {
        return '('.implode(' and ', $expressions).')';
    }

    public function orExpressions(array $expressions) : string
    {
        return '('.implode(' or ', $expressions).')';
    }

    public function notExpression(array $expressions) : string
    {
        return $this->andExpressions($expressions) . ' eq false';
    }

    public function substring(string $field, mixed $value)
    {
        $start = 0;
        $length = -1;
        if (is_array($value)) {
            if (count($value) !== 3) {
                throw new \InvalidArgumentException("Filter substring accepts an array as a value only if there are exactly 3 parameters (Position, End, Value),");
            }
            [$start, $length, $value] = $value;

            if (!is_int($start) || $start < 0) {
                throw new \InvalidArgumentException("Filter substring expects argument 1 (start) of the value to be an positive integer.");
            }

            if (!is_int($length)) {
                throw new \InvalidArgumentException("Filter substring expects argument 2 (length) of the value to be an int.");
            }
            if ($length < 0) {
                $length = -1;
            }
        }

        if ($start < 1) {
            return $this->quoted('eq', $field, $value); // How utterly pointless
        }

        if ($length > 0) {
            return sprintf('substring(%s, %d, %d)', $field, $start, $length) . ' eq ' . $this->quote($value);

        }
        return sprintf('substring(%s, %d)', $field, $start) . ' eq ' . $this->quote($value);
    }

    public function function(string $name, string $field, mixed $value): string
    {
        return sprintf('%s(%s, %s)', $name, $field, $this->quote($value));
    }

    public function in(string $field, iterable $value) : string
    {
        $array = iterator_to_array($value);

        $entries = [];
        foreach ($array as $item) {
            if (!is_scalar($item)) {
                throw new \InvalidArgumentException("Filter type IN only supports iterables that return a scalar value,");
            }
            $entries[] = $this->quote($item);
        }
        return sprintf('%s in (%s)',
            $field,
            implode(', ', $entries),
        );
    }

    public function numerical(string $type, string $field, mixed $value): string
    {
        if (!is_int($value) && !is_float($value)) {
            if (is_string($value) && is_numeric($value)) {
                $value = (float)$value;
            } else {
                throw new \InvalidArgumentException("Filter type {$type} only supports numbers.");
            }
        }

        return implode( ' ', [
            $field,
            $type,
            is_int($value) ? $value : number_format($value, 2, '.', ''),
        ]);
    }

    public function quoted(string $type, string $field, mixed $value): string
    {
        return implode( ' ', [
            $field,
            $type,
            $this->quote($value),
        ]);
    }

    public function quote(mixed $value) : string
    {
        if (is_bool($value)) {
            $value =  $value ? 1 : 0;
        }

        if (is_string($value)) {
            $value =  "'{$value}'";
        }

        return (string) $value;
    }
}
