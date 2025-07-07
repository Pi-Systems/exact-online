<?php

namespace PISystems\ExactOnline\Model;

interface SeededUuidProviderInterface
{
    /**
     * @param string|null $existing
     * @return string
     */
    public function uuid(int $division, ?string $existing = null) : string;
}
