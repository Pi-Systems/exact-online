<?php

namespace PISystems\ExactOnline\Builder\Edm;

if (class_exists(Symfony\Component\Validator\Constraint\NotNull::class)) {
    #[\Attribute(\Attribute::TARGET_PROPERTY)]
    class Required extends Symfony\Component\Validator\Constraint\NotNull
    {

    }
} else {
    #[\Attribute(\Attribute::TARGET_PROPERTY)]
    class Required
    {

    }
}