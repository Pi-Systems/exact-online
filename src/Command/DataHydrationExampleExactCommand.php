<?php

namespace PISystems\ExactOnline\Command;

use PISystems\ExactOnline\Entity\System\Me;
use PISystems\ExactOnline\Enum\HttpMethod;
use PISystems\ExactOnline\Exact;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DataHydrationExampleExactCommand extends Command
{
    public function __construct(private readonly Exact $exact)
    {
        parent::__construct('exact:data-hydration');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        // Set exact to offline, if it ever has to contact the API we know we fucked up.
        $this->exact->offline = true;

        $meta = Me::meta();
// As an example, let's start with a default simple reply from exaxt.
// The answer to a default division call (With UserID added to use as an example a bit later).
// Slightly modified reply from exact ($UserID is not part of the usual CurrentDivision call)
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
        $output->writeln("Passed in hydration: Division {$me->UserID}::{$me->CurrentDivision}");

        /**
         * Hydration does not need to have you create an empty object before hydration.
         * It already knows who it is, it will do it for you.
         */
        /** @var Me $stringMe */
        $stringMe = $meta->hydrate($data);
        $output->writeln("String class hydration: Division {$me->UserID}::{$stringMe->CurrentDivision}");

        /**
         * Serialization works as expected.
         */
        $serialized = serialize($me);
        $output->writeln("Serialization (Serialization length): ".strlen($serialized));

        /**
         * Deserialization works as expected ('allowed_classes' is technically not required, still recommended)
         */
        /** @var Me $stringMe */
        $unserialize = unserialize($serialized, ['allowed_classes' => [Me::class, \DateTimeImmutable::class]]);
        $output->writeln("Unserialization: {$me->UserID}::{$unserialize->CurrentDivision}");

        /**
         * One can call json_encode, and it will cascade ito \JsonSerializable
         */
        $json = json_encode($unserialize);
        $output->writeln("Json encode: (Json length): ".strlen($json));

        /**
         * You do not need to call json_decode before hydrating.
         * If you pass a string, it will simply assume it is json encoded.
         *
         * If you have a serialized string... call unserialize instead of this.
         */
        /** @var Me $jsonMe */
        $jsonMe = $meta->hydrate($json);
        $output->writeln("Json decode (Directly): {$me->UserID}::{$jsonMe->CurrentDivision}");

        /**
         * By default, all exports have every property set.
         * This can (and will) result in monstrously large serialized objects.
         * The 'Me' string is easily 800+ characters long.
         * Serializing null is generally pointless, so tell the deflate/serializer to ignore them.
         */
// Optionally, one can set DataSourceMeta::$deflationSkipsNullByDefault to override the default.
// DataSourceMeta::$deflationSkipsNullByDefault = true;
        $dataOnly = $meta->deflate( $jsonMe, skipNull: true);
        $dataOnlyExport = var_export($dataOnly, true);
        $output->writeln("Data Only (Null Skipped, length): ".strlen($dataOnlyExport));

        /**
         * Hydrating from a limited data set works as expected.
         * Serialize/unserialize will work this way as well, just pointless to show identical behavior.
         */
        /** @var Me $jsonMe */
        $hydrateFromDataOnly = $meta->hydrate($dataOnly);
        $output->writeln("Hydrate from Data Only: {$me->UserID}::{$jsonMe->CurrentDivision}");

        $checkDivisionUptoEnd = $jsonMe->CurrentDivision === 4133185;
        /**
         * Optionally, the deflation process can be told to only save data for a certain method.
         * (Allows for smaller objects to be saved to a cache layer for later processing)
         */
        $deflateOnlyDelete = $meta->deflate($hydrateFromDataOnly, HttpMethod::DELETE);
        $deflateOnlyDeleteExport = var_export($deflateOnlyDelete, true);
        $output->writeln("Delete only (length): ".strlen($deflateOnlyDeleteExport));


        /**
         * This means trouble if a property is not part of that method.
         * @var Me $hydrateFromDelete
         */
        $hydrateFromDelete = $meta->hydrate($deflateOnlyDelete);
        $division = $hydrateFromDelete->CurrentDivision ?? 'CurrentDivision no longer present, truncated by HttpMethod::DELETE';
        $output->writeln("Hydrate from delete only data: {$me->UserID}::{$division}");

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

        $checkDivisionIsNullAtEnd = null === $hydrateFromDelete->CurrentDivision;

        $checkUserId = $me->UserID === '2b5a7b99-e210-46ee-9511-3cac0b66405d';

        if ($checkDivisionUptoEnd && $checkUserId && $checkDivisionIsNullAtEnd) {
            $output->writeln('<info>Data was manipulated as expected.</info>');
            return self::SUCCESS;
        }

        $output->writeln('<error>Data was mangled during manipulation, verify tests & generation code, this error means the entire project is useless.</error>');
        if (!$checkDivisionUptoEnd) {
            $output->writeln("<error>Division (Before DELETE serialization) was not as expected (Expected (int)4133185</error>");
        }
        if (!$checkUserId) {
            $output->writeln("<error>UserID is not as expected</error>");
        }
        if (!$checkDivisionIsNullAtEnd) {
            $output->writeln("<error>Division was not null at the end, it somehow survived being serialized during a DELETE call.</error>");

        }
        return self::FAILURE;
    }

}