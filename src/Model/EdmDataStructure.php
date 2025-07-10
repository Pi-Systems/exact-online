<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Builder\ExactAttribute;

abstract class EdmDataStructure extends ExactAttribute
{
    public readonly array $arguments;

    public function __construct()
    {
        $this->arguments = func_get_args();
    }


    /**
     * Supply the full name: Edm.<type>
     * Ensure capitalization is correct
     *
     * Note: null is not an
     * edm type, despite being part of the odata.org sheet.
     *       null is a logical state of a value type.
     */
    abstract public static function getEdmType(): string;

    /**
     * What the local type should be (scalar type, or FQCN (The whole class name))
     * NULL is added by if the docs say it is not default (Hidden from view, 2nd column holds the value)
     * @return string
     */
    abstract public static function getLocalType() : string;


    /**
     * Description data for this type (Can be ommited)
     * @return null|string
     */
    abstract public static function description(): ?string;

    /**
     * Validate function to check if the data contained is valid.
     *
     * @param mixed $value
     * @return bool
     */
    abstract function validate(mixed $value): bool;

}