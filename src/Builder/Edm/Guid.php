<?php

namespace PISystems\ExactOnline\Builder\Edm;

use PISystems\ExactOnline\Model\EdmDataStructure;
use PISystems\ExactOnline\Model\FilterEncodableDataStructure;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class Guid extends EdmDataStructure implements  FilterEncodableDataStructure
{
    public static function getEdmType(): string
    {
        return 'Edm.Guid';
    }

    public static function description(): ?string
    {
        return 'Basic GUID type';
    }

    public static function getLocalType(): string
    {
        return 'string';
    }


    function validate(mixed $value): bool
    {
        if (!preg_match('/[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}/', (string)$value)) {
            return false;
        }
        return true;
    }


    public function encodeForFilter(mixed $value): ?string
    {
        if (null === $value) {
            return null;
        }
        return sprintf("guid'{$value}'");
    }
}
