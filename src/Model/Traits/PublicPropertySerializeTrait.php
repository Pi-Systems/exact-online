<?php

namespace PISystems\ExactOnline\Model\Traits;

trait PublicPropertySerializeTrait
{
    public function jsonSerialize(): array
    {
        return $this->__serialize();
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
        return self::meta()->deflate($this);
    }

    public function __unserialize(array $data): void
    {
        self::meta()->hydrate($data, $this);
    }
}
