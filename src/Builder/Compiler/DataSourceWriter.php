<?php

namespace PISystems\ExactOnline\Builder\Compiler;

use PISystems\ExactOnline\Builder\Compiler\Interfaces\DataSourceWriterInterface;
use PISystems\ExactOnline\Builder\ExactAttribute;

class DataSourceWriter implements DataSourceWriterInterface
{
    public function __construct(
        public string $dataTemplate = __DIR__ . '/../../Resources/DataTemplate.phps' {
            set => file_exists($value) && is_readable($value) ? $value : throw new \RuntimeException("File {$value} is not readable.");
        },
        public string $methodTemplate = __DIR__ . '/../../Resources/MethodTemplate.phps' {
            set => file_exists($value) && is_readable($value) ? $value : throw new \RuntimeException("File {$value} is not readable.");
        },

        public string $destination = __DIR__ . '/../../Entity' {
            set => (is_dir($value) && is_writable($value)) ? $value : throw new \RuntimeException("Directory {$value} does not exist/is not writable.");
        }
    )
    {

    }

    public function write(
        BuildFileMeta $meta
    ): void
    {
        // Load the templates
        static $template = file_get_contents($this->dataTemplate);
        static $methodTemplate = file_get_contents($this->methodTemplate);

        // Keep track of used namespaces
        // Handle the attributes
        [$attributes, $namespaces] = $this->getAttributes($meta->attributes);

        // Write the initial class
        $classContent = str_replace(
            [
                '{{class}}',
                '{{namespace}}',
                '{{endpoint}}',
                '{{endpointDescriptions}}',
                '{{resource}}',
                '{{path}}',
                '{{scope}}',
                '{{attributes}}'
            ],
            [
                $meta->class,
                $meta->namespace,
                $meta->endpoint,
                $meta->description,
                $meta->resource,
                $meta->path,
                $meta->scope,
                implode(PHP_EOL, $attributes)
            ],
            $template
        );

        $properties = [];
        // loop properties
        /** @var BuildPropertyMeta $property */
        foreach ($meta->properties as $property) {
            // Handle the attributes
            [$attributes, $propertyNamespaces] = $this->getAttributes($property->attributes);
            $namespaces = [... $namespaces, ... $propertyNamespaces];

            // Start with the commentary
            $description = implode(PHP_EOL . "     * ", explode(PHP_EOL, $property->description));

            // Write the property method using the method template
            $propertyString = str_replace(
                [
                    '{{name}}',
                    '{{description}}',
                    '{{type}}',
                    '{{localType}}',
                    '{{typeDescription}}',
                    '{{default}}',
                    '{{attributes}}'
                ],
                [
                    $property->name,
                    $description,
                    $property->typeDescription,
                    $property->local,
                    $property->typeDescription,
                    $property->default ? " = {$property->default}" : "",
                    PHP_EOL . "    " . implode(PHP_EOL . "    ", $attributes)
                ],
                $methodTemplate
            );


            $properties[] = $propertyString;
        }

        // Overwrite the properties tag in the class content
        $classContent = str_replace('{{properties}}', implode(PHP_EOL . "    ", $properties), $classContent);

        // Add namespaces
        $namespaces = array_unique($namespaces);
        $namespaces = array_map(fn(string $namespace) => "use {$namespace};", $namespaces);
        $classContent = str_replace('{{namespaces}}', implode(PHP_EOL, $namespaces), $classContent);

        file_put_contents("{$this->destination}/{$meta->department}/{$meta->endpoint}.php", $classContent);
    }

    protected function getAttributes(array $metaAttributes): array
    {
        $attributes = [];
        $namespaces = [];
        foreach ($metaAttributes as $attribute) {
            $parameters = ($attribute instanceof ExactAttribute) ? array_map(fn($mixed) => var_export($mixed, true), $attribute->arguments) : [];

            $reflection = new \ReflectionClass($attribute);
            $namespaces[] = $reflection->getName();

            if (!empty($parameters))
                $attributes[] = sprintf('#[%s(%s)]', $reflection->getShortName(), implode(', ', $parameters));
            else {
                $attributes[] = sprintf('#[%s]', $reflection->getShortName());
            }
        }
        return [$attributes, $namespaces];
    }
}