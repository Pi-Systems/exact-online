<?php

namespace PISystems\ExactOnline\Builder\Compiler\Interfaces;

use PISystems\ExactOnline\Builder\Compiler\BuildFileMeta;

interface DataSourceWriterInterface
{
    public function write(
        BuildFileMeta $meta
    ): void;
}