<?php

namespace PISystems\ExactOnline\Model\Expr;

use Doctrine\Common\Collections\Criteria as BaseCriteria;
use Doctrine\Common\Collections\Expr\Expression;
use PISystems\ExactOnline\Model\DataSource;
use PISystems\ExactOnline\Model\DataSourceMeta;
use PISystems\ExactOnline\Model\ExactMetaDataLoader;


/**
 * Warning: Logical exclusion
 *          One cannot use both an expression and the expansion property at the same time.
 *          It is not viable for this class to filter this out during construction.
 *          Ensure this logical exclusion is dealt with BEFORE using it to construct the final query.
 *
 *          See rules at https://support.exactonline.com/community/s/knowledge-base#All-All-DNO-Simulation-query-string-options
 *
 * Warning: Logical trap
 *          One cannot use maxResults greater than 1 if no selection was made.
 *
 *          This rule is not really made explicit in the docs, but it is a logica result of other
 *          stated rules...
 *          This is a seriously frustrating rule.
 *          One can bypass it by using $selection = ['*'], but this will destroy performance.
 *          Use at your own risk.
 */
class Criteria extends BaseCriteria
{
    public ExpressionBuilder $expression;

    private static ?\WeakMap $expressionBuilders = null;
    private static ExpressionBuilder|null $expressionBuilder = null;

    public array $selection = [] {
        get => $this->selection;
        set {
            $this->selection = $this->toValidPropertyStack($value);
        }
    }

    public array $expansion = [] {
        get => $this->expansion;
        set {
            $this->expansion = $this->toValidPropertyStack($value);
        }
    }

    private ?DataSourceMeta $meta = null;

    /**
     * Set to true to allow using setFirstResult without crashing out.
     * We do not (yet) have a comprehensive list of all endpoints that still supports this.
     * So you're on your own for using it.
     * Know that exact can (and in the past, has) killed this option for existing endpoints.
     * @var bool
     */
    public bool $allowSkipVariable = false;


    public function __construct(
        ?Expression                           $expression = null,
        /**
         * Optional, but if passed, ensures property safety.
         * Note: Do not use if working with subentries. (Eg: Account/Owner eq ...)
         */
        null|string|DataSource|DataSourceMeta $source = null,
        /**
         * Note: If nothing is added here, the variable $top=1 is automatically added.
         * (This is not our restriction)
         */
        array $selection = ['*'],
        ?array         $expansion = [],
        ?array         $orderings = null,
        /**
         * Exact does not support offsetting by a number (Due to 'we dont know how to optimize) reasons.
         */
        public ?string $skipToken = null {
            get => $this->skipToken;
            set {
                if ($this->meta && !$this->meta->keyColumn) {
                    throw new \LogicException("Cannot set skip token to an entry without a valid identifier.");
                }
                $this->skipToken = $value;
            }
        },
        /**
         * Add the $inlinecount=allpages entry to any one call
         * @var bool
         */
        public bool    $inlineCount = false
    )
    {
        $this->meta = $source ? ExactMetaDataLoader::meta($source) : null;
        $this->selection = $selection;
        $this->expansion = $expansion;

        // The first result being null is deprecated in doctrine.
        // However, it is explicitly NOT deprecated here.
        // Make sure to follow $this->allowSkipVariable before reading it out.
        parent::__construct($expression, $orderings, 0, null);
    }

    /**
     * This doesn't do much, if empty it is ignored by everything.
     * If filled, calling it with the wrong meta passed in matching() or any of the find methods will error it out.
     * @param string|DataSource|DataSourceMeta|null $source
     * @return $this
     */
    public function from(
        null|string|DataSource|DataSourceMeta $source = null
    ) : static
    {
        $this->meta = ExactMetaDataLoader::meta($source);
        return $this;
    }

    public function isFrom(
        string|DataSource|DataSourceMeta $source
    ): bool
    {
        if (null === $this->meta) { return true; } // This criteria matches everything, so of-course we match

        return ExactMetaDataLoader::meta($source)->name === $this->meta->name;
    }

    public static function create(
        null|string|DataSource|DataSourceMeta $source = null,
    ): Criteria
    {
        return new self(null, $source);
    }


    public function expression(): ExpressionBuilder
    {
        return static::expr($this->meta);
    }

    /**
     * @param string|DataSource|DataSourceMeta|null $source
     * @return ExpressionBuilder
     */
    public static function expr(
        null|string|DataSource|DataSourceMeta $source = null,
    ): ExpressionBuilder
    {
        $meta = $source ? ExactMetaDataLoader::meta($source) : null;

        if (null === $meta) {
            return self::$expressionBuilder ??= new ExpressionBuilder();

        }

        static::$expressionBuilders ??= new \WeakMap();

        return static::$expressionBuilders[$meta] ??= new ExpressionBuilder(
            $meta
        );
    }

    protected function toValidPropertyStack(string|iterable $properties, bool $allowSubEntry = false) : array
    {
        if (is_string($properties)) {
            $properties = [$properties];
        }

        $s = [];

        foreach ($properties as $property) {
            if ($property === '*') {
                $s = ['*'];
                break;
            }

            if (str_contains($property, '/')) {

                if (!$allowSubEntry) {
                    throw new \LogicException(
                        "Cannot (yet) use property subentries while a meta is attached.\n" .
                        "See https://support.exactonline.com/community/s/knowledge-base#All-All-DNO-Simulation-query-string-options"
                    );
                }

                $first = explode('/', $property);
                if ($this->meta && !$this->meta->hasProperty($first[0])) {
                    throw new \InvalidArgumentException(
                        "Property {$first[0]} does not exist in {$first[1]} (Loaded from argument {$property}."
                    );
                }
                $s[] = $property;
                continue;
            }


            if ($this->meta && !$this->meta->hasProperty($property)) {
                throw new \InvalidArgumentException("Property {$property} does not exist within {$this->meta->name}");
            }
            $s[] = $property;

        }
        return $s;
    }

    /**
     * @param string|iterable $properties
     * @return Criteria
     */
    public function select(string|iterable $properties) : self
    {
        $this->selection = $this->toValidPropertyStack($properties, true);
        return $this;
    }

    public function expand(string|iterable $properties): self
    {
        $this->expansion = $this->toValidPropertyStack($properties);
        return $this;
    }

    public function setFirstResult(?int $firstResult): Criteria
    {
        if (
            0 === $firstResult ||
            null === $firstResult ||
            $this->allowSkipVariable
        ) {
            return parent::setFirstResult($firstResult);
        }

        throw new \RuntimeException(
            "All endpoints after march 1st 2017 Exact no longer support offsetting by a number.\n" .
            "If you're sure this endpoint still supports it, set \$allowSkipVariable to true in the criteria.\n".
            "See https://support.exactonline.com/community/s/knowledge-base#All-All-DNO-Simulation-query-string-options"
        );
    }

    public function orderBy(array $orderings): Criteria
    {
        foreach ($orderings as $property => $ordering) {
            if ($this->meta && !$this->meta->hasProperty($property)) {
                throw new \InvalidArgumentException("Property {$property} does not exist within {$this->meta->name}");
            }

            $ordering = strtolower($ordering);
            if (!in_array($ordering, ['asc', 'desc'])) {
                throw new \InvalidArgumentException(
                    "Ordering direction {$ordering} is not possible. (asc, desc)"
                );
            }
        }
        return parent::orderBy($orderings);
    }
}