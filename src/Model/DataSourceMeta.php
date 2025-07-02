<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Builder\Endpoint;
use PISystems\ExactOnline\Builder\ExactAttribute;
use PISystems\ExactOnline\Builder\Key;
use PISystems\ExactOnline\Builder\Method;
use PISystems\ExactOnline\Builder\PageSize;
use PISystems\ExactOnline\Enum\HttpMethod;

final class DataSourceMeta implements \Serializable
{
    public static bool $deflationSkipsNullByDefault = false;

    public array $required {
        get {
            return $this->required ??= (fn() => array_filter(
                $this->properties,
                fn(array $props) => $props['required'] === true
            ))();
        }
    }

    protected function __construct(
        public readonly string $name,
        public readonly string $keyColumn,
        public readonly int    $pageSize,
        public readonly string $endpoint,
        public readonly array  $methods = [],
        public readonly array  $properties = [],
    )
    {
    }

    public static function createFromClass(string $name): self
    {
        $props = ['name' => $name, 'key' => 'ID', 'pageSize' => 60, 'endpoint' => 'NotSet', 'methods' => [], 'properties' => [], 'required' => []];

        if (!is_a($name, DataSource::class, true)) {
            throw new \InvalidArgumentException("DataSourceMeta only works for DataSource objects. (" . $name . ' given)');
        }

        try {
            $reflection = new \ReflectionClass($name);
        } catch (\ReflectionException $e) {
            throw new \RuntimeException("Could not build DataSource object for " . $name . ". (" . $e->getMessage() . ")");
        }

        foreach ($reflection->getAttributes(ExactAttribute::class, \ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            if ($attribute->getName() === PageSize::class) {
                $props['pageSize'] = $attribute->getArguments()[0];
            }
            if ($attribute->getName() === Method::class) {
                $props['methods'][] = $attribute->getArguments()[0];
            }
            if ($attribute->getName() === Endpoint::class) {
                $props['endpoint'] = $attribute->getArguments()[0];
            }
        }

        $properties = [];

        foreach ($reflection->getProperties() as $property) {
            $settings = [
                'required' => false,
                'type' => null,
                'methods' => []
            ];

            foreach ($property->getAttributes(ExactAttribute::class, \ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
                if ($attribute->name === Key::class) {
                    $props['key'] = $property->getName();
                }
                if ($attribute->name === Method::class) {
                    $settings['methods'][] = $attribute->getArguments()[0];
                }

                if (is_a($attribute->name, EdmDataStructure::class, true)) {
                    $settings['type'] = $attribute->newInstance();
                }
            }

            $properties[$property->getName()] = $settings;
        }
        $props['properties'] = $properties;

        return new self(... array_values($props));
    }

    public function supports(HttpMethod $httpMethod): bool
    {
        return in_array($httpMethod, $this->methods, true);
    }

    public function getPrimaryKeyValue(DataSource $object): ?string
    {
        return $object->{$this->keyColumn} ?? null;
    }


    public function getColumnsForMethod(HttpMethod $method): iterable
    {
        return array_keys(array_filter(
            $this->properties,
            fn(array $props) => in_array(HttpMethod::class, $props['methods'], true),
        ));
    }

    /**
     * The same object passed in is outputted.
     * @template T
     * @psalm-param DataSource|class-string<T> $class
     * @psalm-return T|DataSource
     */
    public function hydrate(
        string|array             $data,
        null|DataSource $object = null,
    ): DataSource
    {
        $object ??= new $this->name();

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        foreach ($this->properties as $name => $meta) {
            if (isset($data[$name])) {

                $pData = $data[$name];
                if ($meta['type'] instanceof EdmEncodableDataStructure) {
                    $pData = $meta['type']->encode($pData);
                }
                $object->{$name} = $pData;
            }
        }

        return $object;
    }

    public function deflate(
        DataSource $object,
        HttpMethod $httpMethod = HttpMethod::GET,
        ?bool $skipNull = null,
    ): array
    {
        $skipNull ??= self::$deflationSkipsNullByDefault;

        $data = [];

        foreach ($this->properties as $name => $meta) {
            // Ensure we do not send stuff the rest api does not support.
            if ($httpMethod !== HttpMethod::GET && !in_array($httpMethod, $meta['methods'], true)) {
                continue;
            }

            $value = $object->{$name};

            if (($edm = $meta['type']) instanceof EdmEncodableDataStructure) {
                $value = $edm->encode($value);
            }

            if ($skipNull && $value === null) {
                continue;
            }

            $data[$name] = $value;
        }
        return $data;
    }


    public function serialize()
    {
        return json_encode($this->__serialize());
    }

    public function unserialize(string $data)
    {
        $this->__unserialize(json_decode($data, true));
    }

    public function __serialize(): array
    {
        return [
            'name' => $this->name,
            'endpoint' => $this->endpoint,
            'pageSize' => $this->pageSize,
            'keyColumn' => $this->keyColumn,
            'methods' => $this->methods,
            'properties' => $this->properties,
        ];

    }

    public function __unserialize(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
