<?php

namespace PISystems\ExactOnline\Model\Traits;

trait PublicPropertySerializeTrait
{
    public function jsonSerialize(): string
    {
        return json_encode($this->__serialize());
    }

    public function serialize()
    {
        return $this->jsonSerialize();
    }

    public function unserialize(string $data)
    {
        $this->__unserialize(
            json_decode($data, true)
        );
    }

    public function __serialize(): array
    {
        $reflect = new \ReflectionClass($this);
        $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        $data = [];
        foreach ($properties as $property) {
            $data[$property->getName()] = $property->getValue($this);
        }
        return $data;
    }

    public function __unserialize(array $data): void
    {
        foreach ($data as $property => $value) {
            $this->{$property} = $value;
        }
    }
}
