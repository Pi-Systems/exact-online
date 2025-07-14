<?php

namespace PISystems\ExactOnline\Model\Expr;

use Doctrine\Common\Collections\Criteria as BaseCriteria;
use Doctrine\Common\Collections\Expr\Expression;
use PISystems\ExactOnline\Model\DataSource;
use PISystems\ExactOnline\Model\DataSourceMeta;
use PISystems\ExactOnline\Util\MetaDataLoader;


/**
 * The criteria follow the base Doctrine Criteria and support nearly all normal doctrine settings.
 * With a notable exception towards `firstResult`.
 *
 * Example:
 * ```
 *  // Select everything in the `Exact\Me` entity.
 *  Criteria::create(Exact\Me::class);
 *
 *  // Select only the `CurrentDivision` in the `Exact\Me` entity.
 *  Criteria::create(Exact\Me::class)->select('CurrentDivision');
 *
 *  // Select `ID`,`Account` and `AmountFC` from the top 5 `Exact\TransactionLines` entries.
 *  // Where `Account` is equal to 1235
 *  // Ordered by `DocumentNumber` in descending order.
 *  Criteria::create(Exact\TransactionLines::class)
 *   ->select(['ID','Account','AmountFC'])
 *   ->where(
 *      Criteria::expr()->eq('Account', 1235)
 *   )
 *  ->setMaxResult(5)
 *  ->orderBy(['DocumentNumber'=>'DESC'])
 * ```
 *
 * Deprecation/Disallowed:
 *
 * `->firstResult`
 *
 * This (core) criteria is (mostly) not available.
 * Only very select endpoints (Those made BEFORE March 1st 2017) have this enabled.
 * Even then, only if the customer is still authorized to use this parameter.
 *
 * To prevent BC breaks, we opted to keep this entry in but add a clear warning that this should not be used.
 * Instead, the $skipToken was added to the Criteria as a local variable.
 * Please note, $skipToken can only be used on elements that have a primary key.
 *
 *
 * Limitations:
 *
 * *`->where(...)->expand(...)`* are mutually exclusive.
 *
 * One cannot use both an expression and the expansion property at the same time.
 * The criteria itself will not aid in detecting this error.
 *
 * See the rules at
 * https://support.exactonline.com/community/s/knowledge-base#All-All-DNO-Simulation-query-string-options
 *
 * General warning:
 *
 * *`->maxResults(>1)`*
 *
 * *SHOULD* be accompanied by a `->select([...])`.
 * The criteria will still work without (as `->select()` will default to `*`); However.
 * The stress placed on the Exact server can easily go beyond its computational limit and error out.
 *
 * It is highly recommended to only use `->maxResults(>1)` when also only selecting a few fields.
 */
class Criteria extends BaseCriteria
{
    public ExpressionBuilder $expression;

    /**
     * Sets what columns/attributes should be returned while retrieving the data.
     * This is crucial in keeping the amount of data low / limiting the amount of `useless` data being transferred.
     * Defaults to: ['*']
     * Try to fill this for any entity that returns more than ~5-10 attributes.
     * It can (and will) significantly increase transfer/query speed.
     */
    public array $selection = [] {
        get => $this->selection;
        set {
            $this->selection = $this->toValidPropertyStack($value);
        }
    }

    /**
     * oData expansion allows one to ask the result to have expanded collections.
     * There is no check on this during criteria creation, as it cannot know all the rules for said collection.
     * Use in combination with the meta-Hydration functionality to load subcollections without having to call for
     * those collections separately.
     */
    public array $expansion = [] {
        get => $this->expansion;
        set {
            $this->expansion = $this->toValidPropertyStack($value);
        }
    }


    /**
     * Set to true to allow using setFirstResult without crashing out.
     * We do not (yet) have a comprehensive list of all endpoints that still supports this.
     * So you're on your own for using it.
     * Know that exact can (and in the past, has) killed this option for existing endpoints.
     * @var bool
     */
    public bool $allowSkipVariable = false;

    /** @noinspection PhpUnusedFieldDefaultValueInspection NOT redundant, inspection is wrong. */
    private ?DataSourceMeta $meta = null;
    private static ?\WeakMap $expressionBuilders = null;
    private static ExpressionBuilder|null $expressionBuilder = null;

    public function __construct(
        /**
         * Base expression (Doctrine base class accepted.)
         */
        ?Expression                           $expression = null,
        /**
         * Lets the criteria know what DataSource it is filtering for.
         * This ensures column selections are possible.
         * But more importantly, allows `$filter` to be written correctly.
         * Without this the criteria is still usable, but will require values to already be transformed.
         * With this set, one may simply use php scalar (primitive) values, and the criteria will deal with it.
         * Alternatively, One may also set this during the `ExactVisitor` constructor, though one loses column validation
         * during criteria creation.
         */
        null|string|DataSource|DataSourceMeta $source = null,
        /**
         * Sets what columns/attributes should be returned while retrieving the data.
         * This is crucial in keeping the amount of data low / limiting the amount of `useless` data being transferred.
         * Defaults to: ['*']
         * Try to fill this for any entity that returns more than ~5-10 attributes.
         * It can (and will) significantly increase transfer/query speed.
         */
        array $selection = ['*'],
        /**
         * oData expansion allows one to ask the result to have expanded collections.
         * There is no check on this during criteria creation, as it cannot know all the rules for said collection.
         * Use in combination with the meta-Hydration functionality to load subcollections without having to call for
         * those collections separately.
         */
        ?array         $expansion = [],
        /**
         * No different from Doctrine base.
         * `[$key => $type].`
         *
         * Limited $type to `ASC|DESC`
         */
        ?array         $orderings = null,
        /**
         * Alternative for `firstResult` which Exact no longer really supports.
         * Skips all entries up-to key and starts printing from then.
         *      Note: This *does* cause instability during calls, as the token will not snapshot the `modified` date of an
         *      entity.
         *      Instead, it is re-evaluated every call.
         *      This means; If the entity is changed between being given the token, and by request time, the resulting
         *      page may be completely different.
         *      There is no real solution to this without having a full snapshot of the result table on the first request.
         *      Deal with it, it should not really pose an issue.
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
         * Flags that the `$inlinecount=allpages` flag should be enabled.
         * This flag does nothing for most calls as the hydrator will not care.
         * It is only useful during 'manual' parsing of the result data.
         */
        public bool    $inlineCount = false
    )
    {
        $this->meta = $source ? MetaDataLoader::meta($source) : null;
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
        $this->meta = MetaDataLoader::meta($source);
        return $this;
    }

    /**
     * Checks if criteria is compatible with the supplied DataSource.
     *
     * @param string|DataSource|DataSourceMeta $source
     * @return bool
     */
    public function isFrom(
        string|DataSource|DataSourceMeta $source
    ): bool
    {
        if (null === $this->meta) {
            return true;
        } // Criteria matches everything, so of-course we match

        return MetaDataLoader::meta($source)->name === $this->meta->name;
    }

    /**
     * @param string|DataSource|DataSourceMeta|null $source
     * @return Criteria
     *@see AbstractCriteria
     */
    public static function create(
        null|string|DataSource|DataSourceMeta $source = null,
    ): Criteria
    {
        return new self(null, $source);
    }

    public static function fromDoctrine(?BaseCriteria $base, null|string|DataSource|DataSourceMeta $source = null): Criteria
    {
        $criteria = Criteria::create($source);

        if (!$base) {
            return $criteria;
        }

        $criteria->where($base->getWhereExpression());
        $criteria->orderBy($base->orderings());
        $criteria->setMaxResults($base->getMaxResults());
        $criteria->setFirstResult($base->getFirstResult());
        return $criteria;
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
        $meta = $source ? MetaDataLoader::meta($source) : null;

        if (null === $meta) {
            return self::$expressionBuilder ??= new ExpressionBuilder();

        }

        static::$expressionBuilders ??= new \WeakMap();

        return static::$expressionBuilders[$meta] ??= new ExpressionBuilder(
            $meta
        );
    }

    /**
     * Sets what columns/attributes should be returned while retrieving the data.
     * This is crucial in keeping the amount of data low / limiting the amount of `useless` data being transferred.
     * Defaults to: ['*']
     * Try to fill this for any entity that returns more than ~5-10 attributes.
     * It can (and will) significantly increase transfer/query speed.
     */
    public function select(string|iterable $properties) : self
    {
        $this->selection = $this->toValidPropertyStack($properties, true);
        return $this;
    }

    /**
     * oData expansion allows one to ask the result to have expanded collections.
     * There is no check on this during criteria creation, as it cannot know all the rules for said collection.
     * Use in combination with the meta-Hydration functionality to load subcollections without having to call for
     * those collections separately.
     */
    public function expand(string|iterable $properties): self
    {
        $this->expansion = $this->toValidPropertyStack($properties);
        return $this;
    }

    /**
     * This (core) criteria is (mostly) not available.
     * Only very select endpoints (Those made BEFORE March 1st 2017) have this enabled.
     * Even then, only if the customer is still authorized to use this parameter.
     *
     * To prevent BC breaks, we opted to keep this entry in but add a clear warning that this should not be used.
     * Instead, the $skipToken was added to the Criteria as a local variable.
     * Please note, $skipToken can only be used on elements that have a primary key.
     */
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

    /**
     * No different from Doctrine base.
     *  `[$key => $type].`
     *
     *  Limited $type to `ASC|DESC`
     *
     * @var array{ string : 'ASC'|'DESC' } $orderings
     */
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

    protected function toValidPropertyStack(string|iterable $properties, bool $allowSubEntry = false): array
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
}