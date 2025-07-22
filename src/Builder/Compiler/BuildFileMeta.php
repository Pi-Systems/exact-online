<?php

namespace PISystems\ExactOnline\Builder\Compiler;

class BuildFileMeta
{
    public function __construct(
        public ?string $namespace = null,
        public ?string $department = null,
        public array   $attributes = [],
        public ?string $class = null,
        public ?string $endpoint = null,
        public ?string $description = null,
        public ?string $resource = null,
        public ?string $path = null,
        public ?string $scope = null,
        public array   $methods = [],
        public array   $properties = [],
    )
    {

    }

}