<?php

namespace PISystems\ExactOnline\Builder\Edm;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class DateTimeOffset extends DateTime
{
    const string ODATA_DATE_FORMAT = \DateTimeInterface::ATOM;

    public static function getEdmType(): string
    {
        return 'Edm.DateTimeOffset';
    }

    public static function description(): ?string
    {
        return '';
    }
}
