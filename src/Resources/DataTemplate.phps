<?php

namespace {{namespace}};

use PISystems\ExactOnline\Model\DataSource;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;

/**
 * @see {{resource}}
 */
class {{class}} extends DataSource {

    public const array METHODS = [{{methods}}];
    public const string ENDPOINT = '{{path}}';

    {{properties}}
}