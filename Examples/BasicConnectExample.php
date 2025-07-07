<?php

namespace PISystems;

use PISystems\ExactOnline\Builder\Exact;

/**
 * PSR Autoloading, DotEnv loading and basic sanity checking
 */
include "SetupExample.php";

/** @var Exact $exact */
$exact = include "ExactConstructorExample.php";

print "Exact loaded and ready, we're using administration/organization/division ".$exact->getDivision() . PHP_EOL;
