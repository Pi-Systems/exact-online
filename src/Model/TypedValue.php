<?php

namespace PISystems\ExactOnline\Model;

readonly class TypedValue
{
    public function __construct(
        public ?string $type,
        public mixed   $value
    )
    {

    }

    public function getType(): string
    {
        return null === $this->type ?
            (
            $this->value ? gettype($this->value) : 'scalar'
            )
            : $this->type;
    }

    public function getEncoded(): mixed
    {

        $value = $this->value;
        if (is_callable($value)) {
            $value = $value();
        }

        if (null === $this->type) {
            return $value;
        }

        return sprintf('%s\'%s\'', $this->type, $value);
    }

}