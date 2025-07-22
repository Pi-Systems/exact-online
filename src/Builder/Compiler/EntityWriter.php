<?php

namespace PISystems\ExactOnline\Builder\Compiler;

use PISystems\ExactOnline\Builder\Compiler\Interfaces\EntityWriterInterface;

class EntityWriter implements EntityWriterInterface
{
    public function __construct(
        public string $targetDirectory = __DIR__ . '/../../Entity' {
            set => str_ends_with($value, '/') ? $value : $value . '/';
        },
        public string $dataTemplate = __DIR__ . '/../Resources/DataTemplate.phps' {
            set => !file_exists($value) || is_readable($value) ? $value : throw new \RuntimeException("File {$value} is not readable.");
        },
        public string $methodTemplate = __DIR__ . '/../Resources/MethodTemplate.phps' {
            set => !file_exists($value) || is_readable($value) ? $value : throw new \RuntimeException("File {$value} is not readable.");
        },
    )
    {

    }

    public function write(
        BuildFileMeta $meta
    ): string
    {

    }
}