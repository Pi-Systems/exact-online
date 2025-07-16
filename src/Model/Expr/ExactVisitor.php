<?php

namespace PISystems\ExactOnline\Model\Expr;

use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\Common\Collections\Expr\ExpressionVisitor;
use Doctrine\Common\Collections\Expr\Value;
use Infinite\FormBundle\Tests\CheckboxGrid\Entity\Salesman;
use Override;
use PISystems\ExactOnline\Model\DataSourceMeta;
use PISystems\ExactOnline\Model\FilterEncodableDataStructure;
use PISystems\ExactOnline\Model\TypedValue;

class ExactVisitor extends ExpressionVisitor
{
    public function __construct(
        private readonly DataSourceMeta $meta,
        /**
         * If disabled, values will not be passed through the encodeForFilter encoder.
         *
         * It is not required nor recommended to disable this if all you need is to disable it for calling odata specific
         * functions.
         * Should a function be present in the field body, the filter is not called by default.
         *
         * Example, to achieve:
         * http://host/service/Employees?$filter=day(BirthDate) eq 8
         *
         * One would do: ->eq('day(BirthDate)', 8);
         *
         * Only when _ALL_ values are supposed to be raw would one set this to true.
         *
         */
        public bool                     $rawValues = false,
        public int                      $precision = 2
    )
    {

    }

    #[Override] public function walkComparison(Comparison $comparison): string
    {
        $field = $comparison->getField();

        // Skip everything, the user knows what they're doing (We hope).
        if (ExactComparison::RAW === $comparison->getOperator()) {
            return $field;
        }

        $value = $comparison->getValue()->getValue();

        // Field may be a function. (See https://docs.oasis-open.org/odata/odata/v4.0/odata-v4.0-part2-url-conventions.html)
        $property = $field;
        $matches = [];
        if ($isFunction = preg_match('/^(?:[a-zA-Z0-9_]+)\(([^\)]+)\)$/', $field, $matches)) {
            $property = $matches[1];
        }

        // If we were given a function, DO NOT CALL encodeForFilter! (They're on their own!)
        // Also do not block just because the meta does not know the property, virtual/hidden fields are a thing.
        if (!$isFunction && $this->meta->hasProperty($property)) {
            $prop = $this->meta->properties[$property];
            $edm = $prop['type'] ?? null;
            if ($edm instanceof FilterEncodableDataStructure) {
                if (is_iterable($value)) {
                    $value = iterator_to_array($value);
                    array_walk($value, fn($item) => $edm->encodeForFilter($item));
                } else {
                    $value = $edm->encodeForFilter($value);
                }
            }
        }

        return match ($op = $comparison->getOperator()) {
            Comparison::EQ, => $this->expression('eq', $field, $value),
            Comparison::NEQ => $this->expression('ne', $field, $value),
            Comparison::LT => $this->numerical('lt', $field, $value),
            Comparison::LTE => $this->numerical('le', $field, $value),
            Comparison::GT => $this->numerical('gt', $field, $value),
            Comparison::GTE => $this->numerical('ge', $field, $value),
            Comparison::IN => $this->in($field, $value),
            Comparison::NIN => $this->andExpressions([$this->in($field, $value)]) . ' eq false',
            Comparison::CONTAINS => $this->andExpressions([$this->function('indexof', $field, $value)]) . ' gt -1',
            Comparison::STARTS_WITH => $this->function('startswith', $field, $value),
            Comparison::ENDS_WITH => $this->function('endswith', $field, $value),
            ExactComparison::LOWER => $this->function('tolower', $field, $value),
            ExactComparison::UPPER => $this->function('toupper', $field, $value),
            ExactComparison::SUBSTRING => $this->substring($field, $value),
            ExactComparison::ICONTAINS => $this->andExpressions([
                    $this->function('indexof', 'tolower(' . $field . ')', $value)
                ]) . ' gt -1',
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

    public function andExpressions(array $expressions): string
    {
        return '(' . implode(' and ', $expressions) . ')';
    }

    public function orExpressions(array $expressions): string
    {
        return '(' . implode(' or ', $expressions) . ')';
    }

    public function notExpression(array $expressions): string
    {
        return $this->andExpressions($expressions) . ' eq false';
    }

    public function substring(string $field, mixed $value): string
    {
        if ($value instanceof TypedValue) {
            $value = $value->value;
        }

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

        if ($value instanceof TypedValue) {
            $value = $value->getEncoded();
        }

        if ($start < 1) {
            return $this->expression('eq', $field, $value); // How utterly pointless
        }

        if ($length > 0) {
            return sprintf('substring(%s, %d, %d)', $field, $start, $length) . ' eq ' . $value;

        }
        return sprintf('substring(%s, %d)', $field, $start) . ' eq ' . $value;
    }

    public function function (string $name, string $field, mixed $value): string
    {
        if ($value instanceof TypedValue) {
            $value = $value->getEncoded();
        }
        return sprintf('%s(%s, %s)', $name, $field, $value);
    }

    public function in(string $field, string|iterable $value): string
    {
        if (is_string($value)) {
            $value = [$value];
        }
        $array = iterator_to_array($value);

        $entries = [];
        foreach ($array as $item) {
            if ($item instanceof TypedValue) {
                $item = $item->getEncoded();
            }

            if (!is_scalar($item)) {
                throw new \InvalidArgumentException("Filter type IN only supports iterables that return a scalar value,");
            }

            $entries[] = $item;
        }
        return sprintf('%s in (%s)',
            $field,
            implode(', ', $entries),
        );
    }

    public function numerical(string $type, string $field, mixed $value): string
    {
        if ($value instanceof TypedValue) {
            $value = $value->getEncoded();
        }
        if (!is_int($value) && !is_float($value)) {
            if (is_string($value) && is_numeric($value)) {
                $value = (float)$value;
            } else {
                throw new \InvalidArgumentException("Filter type {$type} only supports numbers.");
            }
        }

        return implode(' ', [
            $field,
            $type,
            is_int($value) ? $value : number_format($value, $this->precision, '.', ''),
        ]);
    }

    public function expression(string $type, string $field, mixed $value): string
    {
        if ($value instanceof TypedValue) {
            $value = $value->getEncoded();
        }

        return implode(' ', [
            $field,
            $type,
            $value,
        ]);
    }

}