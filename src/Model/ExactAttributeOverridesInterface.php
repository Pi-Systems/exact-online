<?php

namespace PISystems\ExactOnline\Model;

interface ExactAttributeOverridesInterface
{
    public function hasOverrides(string $point) : bool;

    /**
     * @param string $point
     * @param array $existing
     * @return array
     */
    public function override(string $point, array $existing) : array;
}
