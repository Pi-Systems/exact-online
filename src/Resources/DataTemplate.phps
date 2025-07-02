<?php

namespace {{namespace}};

use PISystems\ExactOnline\Model\DataSource;
use PISystems\ExactOnline\Builder as Exact;
use PISystems\ExactOnline\Builder\Edm;
use PISystems\ExactOnline\Enum\HttpMethod;

/**{{endpointDescriptions}}
 * @see {{resource}}
 * @see https://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem
 */
{{attributes}}
class {{class}} extends DataSource {
    {{properties}}
}
