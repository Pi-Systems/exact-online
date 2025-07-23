<?php

namespace PISystems\ExactOnline\Builder\Compiler;

use PISystems\ExactOnline\Enum\HttpMethod;

class BuildFileMeta
{
    public function __construct(
        public ?string $namespace = null,
        public ?string $department = null,
        /**
         * @var object[] \Attribute::TARGET_CLASS objects.
         */
        public array   $attributes = [],
        public ?string $fqcn = null,
        public ?string $class = null,
        public ?string $endpoint = null,
        public ?string $description = null,
        public ?string $resource = null,
        public ?string $path = null,
        public ?string $scope = null,
        /**
         * @var HttpMethod[]
         */
        public array   $methods = [],
        /**
         * @var array
         */
        public array   $properties = [],
    )
    {

    }

}