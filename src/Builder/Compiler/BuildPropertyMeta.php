<?php

namespace PISystems\ExactOnline\Builder\Compiler;

class BuildPropertyMeta
{
    public function __construct(
        public BuildFileMeta $origin,
        public ?string       $name = null,
        public ?string       $local = null,
        public ?string       $description = null,
        public ?string       $typeDescription = null,
        public ?string       $localType = null,
        public ?string       $default = null,
        /**
         * @var object[] \Attribute::TARGET_PROPERTY objects.
         */
        public array         $attributes = [],
    )
    {

    }
}