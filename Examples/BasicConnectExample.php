<?php

namespace PISystems;

/**
 * PSR Autoloading, DotEnv loading and basic sanity checking
 */
include "SetupExample.php";

$exact = include "ExactConstructorExample.php";

print "Exact loaded and ready, we're using administration/organization/division ".$exact->getAdministration() . PHP_EOL;
