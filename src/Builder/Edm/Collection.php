<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\DataSourceMeta;
use PISystems\ExactOnline\Model\EdmEncodableDataStructure;
use PISystems\ExactOnline\Model\ExactMetaDataLoader;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class Collection extends EdmEncodableDataStructure
{
    private ?DataSourceMeta $targetMeta = null;

    public function __construct(
        public string $target,
        public string $globalName,
        public bool $deflateSkipNull = true
    )
    {

    }
    public static function getEdmType(): string
    {
        return 'Edm.Collection';
    }

    public static function getLocalType(): string
    {
        return '?array';
    }

    public static function description(): ?string
    {
        return 'An collection/array of linked DataSource entries.';
    }

    function validate(mixed $value): bool
    {
        return is_iterable($value);
    }

    public function encode(mixed $value): array
    {
        $targetMeta = $this->targetMeta ??= ExactMetaDataLoader::meta($this->target);

        $conversion = [];
        foreach ($value as $item) {
            $conversion[] = $targetMeta->deflate($item, skipNull: $this->deflateSkipNull);
        }

        return $conversion;
    }

    public function decode(array|float|bool|int|string|null $value): array
    {
        $targetMeta = $this->targetMeta ??= ExactMetaDataLoader::meta($this->target);

        $conversion = [];
        foreach ($value as $item) {
            $conversion[] = $targetMeta->hydrate($item);
        }

        return $conversion;
    }


}
