<?php

namespace PISystems\ExactOnline\Builder;

if (class_exists('Symfony\\Component\\Validator\\Constraint\\NotNull')) {
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