<?php

namespace PISystems\ExactOnline\Model\Expr;

use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\ExpressionBuilder as BaseExpressionBuilder;
use PISystems\ExactOnline\Model\DataSourceMeta;

class ExpressionBuilder extends BaseExpressionBuilder
{
    public function __construct(
        protected readonly ?DataSourceMeta $meta = null
    )
    {

    }

    protected function assertFieldExists(string $field): void
    {
        if ($this->meta) {
            if (!$this->meta->hasProperty($field)) {
                throw new \InvalidArgumentException("Field '$field' does not exist");
            }
        }
    }

    public function lower(string $field, string $value): ExactComparison
    {
        $this->assertFieldExists($field);
        return new ExactComparison($field, ExactComparison::LOWER, $value);
    }

    public function upper(string $field, string $value): ExactComparison
    {
        $this->assertFieldExists($field);
        return new ExactComparison($field, ExactComparison::UPPER, $value);
    }

    public function substring(string $field, string $value, int $position, ?int $length = null): ExactComparison
    {
        $this->assertFieldExists($field);
        return new ExactComparison($field, ExactComparison::SUBSTRING, [$position, $length ?? -1, $value]);
    }

    public function eq(string $field, mixed $value): Comparison
    {
        $this->assertFieldExists($field);
        return parent::eq($field, $value);
    }

    public function gt(string $field, mixed $value): Comparison
    {
        $this->assertFieldExists($field);
        return parent::gt($field, $value);
    }

    public function lt(string $field, mixed $value): Comparison
    {
        $this->assertFieldExists($field);
        return parent::lt($field, $value);
    }

    public function gte(string $field, mixed $value): Comparison
    {
        $this->assertFieldExists($field);
        return parent::gte($field, $value);
    }

    public function lte(string $field, mixed $value): Comparison
    {
        $this->assertFieldExists($field);
        return parent::lte($field, $value);
    }

    public function neq(string $field, mixed $value): Comparison
    {
        $this->assertFieldExists($field);
        return parent::neq($field, $value);
    }

    public function isNull(string $field): Comparison
    {
        $this->assertFieldExists($field);
        return parent::isNull($field);
    }

    public function isNotNull(string $field): Comparison
    {
        $this->assertFieldExists($field);
        return parent::isNotNull($field);
    }

    public function in(string $field, array $values): Comparison
    {
        $this->assertFieldExists($field);
        return parent::in($field, $values);
    }

    public function notIn(string $field, array $values): Comparison
    {
        $this->assertFieldExists($field);
        return parent::notIn($field, $values);
    }

    public function contains(string $field, mixed $value): Comparison
    {
        $this->assertFieldExists($field);
        return parent::contains($field, $value);
    }

    /**
     * Case-Insensitive version of contains
     */
    public function icontains(string $field, mixed $value): Comparison
    {
        $this->assertFieldExists($field);
        return new ExactComparison($field, ExactComparison::ICONTAINS, $value);
    }

    public function memberOf(string $field, mixed $value): Comparison
    {
        $this->assertFieldExists($field);
        return parent::memberOf($field, $value);
    }

    public function startsWith(string $field, mixed $value): Comparison
    {
        $this->assertFieldExists($field);
        return parent::startsWith($field, $value);
    }

    public function endsWith(string $field, mixed $value): Comparison
    {
        $this->assertFieldExists($field);
        return parent::endsWith($field, $value);
    }


}