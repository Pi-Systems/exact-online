<?php

namespace {{namespace}};

use PISystems\ExactOnline\Model\DataSource;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Builder\PageSize;
use PISystems\ExactOnline\Enum\HttpMethod;

/**
 * @see {{resource}}
 */
#[PageSize({{pagesize}})]
class {{class}} extends DataSource {

    public static readonly array $METHODS = [{{methods}}];
    public static readonly string $ENDPOINT = '{{path}}';

    {{properties}}
}