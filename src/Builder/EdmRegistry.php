<?php

namespace PISystems\ExactOnline\Builder;

class EdmRegistry
{
    public const array EDM_CLASSES =
        [
            Edm\Binary::class,
            Edm\Boolean::class,
            Edm\Byte::class,
            Edm\DateTime::class,
            Edm\Collection::class,
            Edm\DateTimeOffset::class,
            Edm\Decimal::class,
            Edm\Double::class,
            Edm\Guid::class,
            Edm\Int32::class,
            Edm\Int64::class,
            Edm\Int16::class,
            Edm\SByte::class,
            Edm\Single::class,
            Edm\Time::class,
            Edm\UTF8CodeString::class,
            Edm\UTF8String::class,
        ];

    public static function map(): array {
        $types = array_map(fn($class) =>
             call_user_func($class. '::getEdmType')
        , self::EDM_CLASSES);

        $classes = array_map(fn(string $class) =>
            [$class, call_user_func($class. '::getLocalType'), call_user_func($class. '::description')],
            self::EDM_CLASSES
        );

        return array_combine($types, $classes);
    }
}
