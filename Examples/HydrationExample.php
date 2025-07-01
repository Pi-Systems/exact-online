<?php

namespace PISystems;

use PISystems\ExactOnline\Model\Exact\System\Me;
use PISystems\ExactOnline\Model\ExactMetaDataLoader;
use PISystems\ExactOnline\Polyfill\SimpleFileCache;

/**
 * PSR Autoloading, DotEnv loading and basic sanity checking
 */
include "SetupExample.php";

$cache = new SimpleFileCache(sys_get_temp_dir() . '/exact-online-cache');
ExactMetaDataLoader::$cache = $cache;
$meta = Me::meta();
$data = json_decode(<<<EOF
{
  "d" : {
    "results": [
      {
        "__metadata": {
          "uri": "https://start.exactonline.nl/api/v1/current/Me(guid'2b5a7b99-e210-46ee-9511-3cac0b66405d')", "type": "Exact.Web.Api.Models.System.Me"
        }, "CurrentDivision": 4133185
      }
    ]
  }
}
EOF, true)['d']['results'][0];

var_dump($data);
$me = new Me();
$meta->hydrate($me, $data);
var_dump($me->CurrentDivision);
