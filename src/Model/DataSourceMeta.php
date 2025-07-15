<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Builder\Endpoint;
use PISystems\ExactOnline\Builder\ExactAttribute;
use PISystems\ExactOnline\Builder\Key;
use PISystems\ExactOnline\Builder\Method;
use PISystems\ExactOnline\Builder\PageSize;
use PISystems\ExactOnline\Enum\HttpMethod;

final class DataSourceMeta
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

    public static function createFromArray(array $data): self
    {
        $required = ['name', 'keyColumn', 'pageSize', 'endpoint', 'methods', 'properties'];
        if (!($diff = array_intersect($required, array_keys($data)))) {
            throw new \InvalidArgumentException("The array you passed in is not valid. (" . implode(', ', $diff) . ' missing)');
        }
        if (!is_iterable($data['methods'])) {
            throw new \InvalidArgumentException("The array you passed in is not valid. (methods is not iterable)");
        }

        if (!is_iterable($data['properties'])) {
            throw new \InvalidArgumentException("The array you passed in is not valid. (properties is not iterable)");
        }

        foreach ($data['methods'] as $k => $method) {
            if (is_string($method)) {
                $method = $data['methods'][$k] = HttpMethod::from($method);
            }
            if (!($method instanceof HttpMethod)) {
                throw new \InvalidArgumentException("The array you passed in is not valid. (entry {$k} was neither a HttpMethod nor a string that could be casted to HttpMethod)");
            }
        }

        foreach ($data['properties'] as $k => &$property) {
            $required = ['required', 'type', 'methods'];
            if (!($diff = array_intersect($required, array_keys($property)))) {
                throw new \InvalidArgumentException("The array you passed in is not valid. (entry {$k} is missing required keys " . implode(', ', $diff) . ")");
            }

            foreach ($property['methods'] as $i => $method) {
                if (is_string($method)) {
                    $method = $property['methods'][$k] = HttpMethod::from($method);
                }

                if (!($method instanceof HttpMethod)) {
                    throw new \InvalidArgumentException("The array you passed in for property {$k}['methods'] is not valid. (entry {$i} was neither a HttpMethod nor a string that could be casted to HttpMethod)");
                }
            }

            if (null !== $property['type']) {
                $args = $property['type']['arguments'] ?? [];
                $property['type'] = new $property['type']['class'](... $args);

                if (!$property['type'] instanceof EdmDataStructure) {
                    throw new \InvalidArgumentException("The array you passed in for property {$k}['type'] is not valid. (class is not a EdmDataStructure)");
                }
            }

        }

        return new self(
            $data['name'],
            $data['keyColumn'],
            $data['pageSize'],
            $data['endpoint'],
            $data['methods'],
            $data['properties'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'keyColumn' => $this->keyColumn,
            'pageSize' => $this->pageSize,
            'endpoint' => $this->endpoint,
            'methods' => array_map(fn(HttpMethod $method) => $method->value, $this->methods),
            'properties' => array_map(fn($property) => [
                'required' => $property['required'],
                'type' => $property['type'] instanceof EdmDataStructure
                    ? [
                        'class' => get_class($property['type']),
                        'arguments' => $property['type']->arguments,
                    ] : null
                ,
                'methods' => array_map(fn(HttpMethod $method) => $method->value, $property['methods']),
            ], $this->properties)
        ];
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

    public function hasProperty(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->properties);
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
            fn(array $props) => in_array($method, $props['methods'], true),
        ));
    }

    /**
     * The same object passed in is outputted
     * If no object is passed in, it will create one for you.
     *
     * @template T
     * @psalm-param DataSource|class-string<T> $class
     * @psalm-return T|DataSource
     */
    public function hydrate(
        string|array $data,
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
                /** @var EdmEncodableDataStructure $edm */
                if (($edm = $meta['type']) instanceof EdmEncodableDataStructure) {
                    $pData = $edm->decode($pData);
                }
                $object->{$name} = $pData;
            }
        }

        return $object;
    }

    public function deflate(
        DataSource $object,
        HttpMethod $httpMethod = HttpMethod::GET,
        /**
         * This is an additional restriction on-top of $httpMethod.
         * If set, each field must within the HttpMethod above, or this will throw a \InvalidArgumentException.
         */
        ?array $limitFieldsTo = null,
        ?bool $skipNull = null,
    ): array
    {
        $skipNull ??= self::$deflationSkipsNullByDefault;

        $data = [];

        if ($limitFieldsTo) {
            $lc = count($limitFieldsTo);
            $intersect = array_intersect($limitFieldsTo, array_keys($this->properties));
            if (count($intersect) !== $lc) {
                throw new \InvalidArgumentException("The fields you specified are not valid for this object. (" . implode(', ', $intersect) . ' given)');
            }
        }

        foreach ($this->properties as $name => $meta) {
            // Ensure we do not send stuff the rest api does not support.
            if ($httpMethod !== HttpMethod::GET && !in_array($httpMethod, $meta['methods'], true)) {
                continue;
            }

            if ($limitFieldsTo && !in_array($name, $limitFieldsTo, true)) {
                continue;
            }

            $value = $object->{$name} ?? null;

            /** @var EdmEncodableDataStructure $edm */
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

}