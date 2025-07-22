<?php

namespace PISystems\ExactOnline\Builder\Compiler\Interfaces;

use PISystems\ExactOnline\Builder\Compiler\BuildFileMeta;

interface EntityWriterInterface
{
    public function write(
        BuildFileMeta $meta
    ): string;
}