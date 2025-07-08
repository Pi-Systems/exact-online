<?php

namespace PISystems\ExactOnline\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use PISystems\ExactOnline\Builder\ExactDocsReader;
use PISystems\ExactOnline\Model\ExactAttributeOverrides;
use PISystems\ExactOnline\Polyfill\SimpleAbstractLogger;
use PISystems\ExactOnline\Polyfill\SimpleClosureLogger;
use PISystems\ExactOnline\Polyfill\SimpleFileCache;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildCommand extends Command
{
    const int DEFAULT_TTL = 60 * 60 * 24 * 30;

    public function __construct()
    {
        parent::__construct('exact:build');
    }

    protected function configure()
    {
        $this->addOption('ttl', null, InputOption::VALUE_REQUIRED, 'How long for the ttl?', self::DEFAULT_TTL);
        parent::configure();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ttl = (int)$input->getOption('ttl');
        $cache = new SimpleFileCache(__DIR__.'/../Resources/ExactDocumentationCache', defaultTtl: self::DEFAULT_TTL);
        $cache->ignoreTimeout = true;

        $reader = new ExactDocsReader(
            $cache,
            new HttpFactory(),
            new Client(),
            new SimpleClosureLogger(
                function(int $level, string $message) use ($output) {
                    if ($output->isVerbose() || $level > SimpleAbstractLogger::DEBUG) {
                        $output->writeln(sprintf("[%s] %s", SimpleClosureLogger::toLogLevel($level), $message));
                    }
                }
            ),
            $ttl,
            attributeOverrides: new ExactAttributeOverrides()
        );

        $reader->localOnly = true;

        try {
//            $reader->build('/.*\/crm\/.*/');
            $reader->build();
        } catch (ClientExceptionInterface $e) {
            print $e->getMessage();
        }

        return self::SUCCESS;
    }


}
