<?php

namespace PISystems;

use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Model\Exact\System\Me;
use PISystems\ExactOnline\Model\ExactMetaDataLoader;
use PISystems\ExactOnline\Polyfill\SimpleFileCache;

/**
 * PSR Autoloading, DotEnv loading and basic sanity checking
 */
include "SetupExample.php";

// It is not technically required to have a persistent cache attached.
// Without it, the meta-loaders internal runtime cache will still function.
// It will, however, throw a user notice which will halt dev builds.
//
// The cache persistor is set in this example,
// Note: When using the library, this is done automatically upon construction of the ConnectionManager.
$cache = new SimpleFileCache(sys_get_temp_dir() . '/exact-online-cache');
ExactMetaDataLoader::$cache = $cache;
$meta = Me::meta();
// An slightly modified reply from exact ($UserID is not part of the usual CurrentDivision call)
// Normally speaking, the data retrieved is already parsed to leave only the 'result'.
// For transparency purposes, this is skipped.
$data = json_decode(<<<EOF
{
  "d" : {
    "results": [
      {
        "__metadata": {
          "uri": "https://start.exactonline.nl/api/v1/current/Me(guid'2b5a7b99-e210-46ee-9511-3cac0b66405d')", "type": "Exact.Web.Api.Models.System.Me"
        }, "CurrentDivision": 4133185, "UserID": "2b5a7b99-e210-46ee-9511-3cac0b66405d"
      }
    ]
  }
}
EOF, true)['d']['results'][0];

/**
 * Starting with the basic case, just having a simple Me object, with the above data
 * This is the basic method that most hydration uses.
 */
$me = new Me();
$meta->hydrate($data, $me);
print "Passed in hydration: Division {$me->UserID}::{$me->CurrentDivision}\n";

/**
 * Hydration does not need to have you create an empty object before hydration.
 * It already knows who it is, it will do it for you.
 */
/** @var Me $stringMe */
$stringMe = $meta->hydrate($data);
print "String class hydration: Division {$me->UserID}::{$stringMe->CurrentDivision}\n";

/**
 * Serialization works as expected.
 */
$serialized = serialize($me);
print "Serialization (Serialization length): ".strlen($serialized).PHP_EOL;

/**
 * Deserialization works as expected ('allowed_classes' is technically not required, still recommended)
 */
/** @var Me $stringMe */
$unserialize = unserialize($serialized, ['allowed_classes' => [Me::class, \DateTimeImmutable::class]]);
print "Unserialization: {$me->UserID}::{$unserialize->CurrentDivision}\n";

/**
 * One can call json_encode, and it will cascade ito \JsonSerializable
 */
$json = json_encode($unserialize);
print "Json encode: (Json length): ".strlen($json).PHP_EOL;

/**
 * You do not need to call json_decode before hydrating.
 * If you pass a string, it will simply assume it is json encoded.
 *
 * If you have a serialized string... call unserialize instead of this.
 */
/** @var Me $jsonMe */
$jsonMe = $meta->hydrate($json);
print "Json decode (Directly): {$me->UserID}::{$jsonMe->CurrentDivision}\n";

/**
 * By default, all exports have every property set.
 * This can (and will) result into monstrously large serialized objects.
 * The 'Me' string is easily 800+ characters long.
 * Serializing null is generally pointless, so tell the deflate/serializer to ignore them.
 */
// Optionally, one can set DataSourceMeta::$deflationSkipsNullByDefault to override the default.
// DataSourceMeta::$deflationSkipsNullByDefault = true;
$dataOnly = $meta->deflate( $jsonMe, skipNull: true);
$dataOnlyExport = var_export($dataOnly, true);
print "Data Only (Null Skipped, length): ".strlen($dataOnlyExport).PHP_EOL;

/**
 * Hydrating from a limited data set works as expected.
 * Serialize/unserialize will work this way as well, just pointless to show identical behavior.
 */
/** @var Me $jsonMe */
$hydrateFromDataOnly = $meta->hydrate($dataOnly);
print "Hydrate from Data Only: {$me->UserID}::{$jsonMe->CurrentDivision}\n";

/**
 * Optionally, the deflation process can be told to only save data for a certain method.
 * (Allows for smaller objects to be saved to a cache layer for later processing)
 */
$deflateOnlyDelete = $meta->deflate($hydrateFromDataOnly, HttpMethod::DELETE);
$deflateOnlyDeleteExport = var_export($deflateOnlyDelete, true);
print "Delete only (length): ".strlen($deflateOnlyDeleteExport).PHP_EOL;

/**
 * This means trouble if a property is not part of that method.
 */
$hydrateFromDelete = $meta->hydrate($deflateOnlyDelete);
$division = $hydrateFromDelete->CurrentDivision ?? 'CurrentDivision no longer present, truncated by HttpMethod::DELETE';
print "Hydrate from delete only data: {$me->UserID}::{$division}\n";

/**
 * Output:
 *
 * Passed in hydration: Division 2b5a7b99-e210-46ee-9511-3cac0b66405d::4133185
 * String class hydration: Division 2b5a7b99-e210-46ee-9511-3cac0b66405d::4133185
 * Serialization (Serialization length): 993
 * Unserialization: 2b5a7b99-e210-46ee-9511-3cac0b66405d::4133185
 * Json encode: (Json length): 859
 * Json decode (Directly): 2b5a7b99-e210-46ee-9511-3cac0b66405d::4133185
 * Data Only (Null Skipped, length): 95
 * Hydrate from Data Only: 2b5a7b99-e210-46ee-9511-3cac0b66405d::4133185
 * Delete only (length): 9
 * Hydrate from delete only data: 2b5a7b99-e210-46ee-9511-3cac0b66405d::CurrentDivision no longer present, truncated by HttpMethod::DELETE
 */
