<?php

namespace PISystems\ExactOnline\Model;

interface UuidProviderInterface
{
    /**
     * @return string
     */
    public function uuid(): string;
}