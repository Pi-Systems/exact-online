<?php

namespace PISystems\ExactOnline\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use PISystems\ExactOnline\Builder\Compiler\Compiler;
use PISystems\ExactOnline\Builder\Compiler\RemoteDocumentLoader;
use PISystems\ExactOnline\Polyfill\SimpleFileCache;
use PISystems\ExactOnline\Util\AttributeOverrides;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildCommand extends Command
{
    const int DEFAULT_TTL = 60 * 60 * 24 * 30;

    public function __construct(private readonly LoggerInterface $logger)
    {
        parent::__construct('exact:build');
    }

    protected function configure(): void
    {
        $this->addOption('filter', 'f', InputOption::VALUE_REQUIRED, 'Only run build for this filter.');
        $this->addOption('online', 'o', InputOption::VALUE_NONE, 'Use online mode');
        $this->addOption('methodTemplate', null, InputOption::VALUE_REQUIRED, 'Use this template instead of the default one.');
        $this->addOption('dataTemplate', null, InputOption::VALUE_REQUIRED, 'Use this template instead of the default one.');
        parent::configure();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cache = new SimpleFileCache(__DIR__.'/../Resources/ExactDocumentationCache', defaultTtl: self::DEFAULT_TTL);
        $cache->ignoreTimeout = true;
        $reader = new Compiler(
            $cache,
            $this->logger,
            new RemoteDocumentLoader($cache, new HttpFactory(), $this->logger, new Client()),
            attributeOverrides: new AttributeOverrides()
        );

        if ($dataTemplate = $input->getOption('methodTemplate')) {
            if (!is_file($dataTemplate) || !is_readable($dataTemplate)) {
                throw new \InvalidArgumentException("The template file '{$dataTemplate}' does not exist or is not readable.");
            }
            $reader->methodTemplate = $dataTemplate;
        }

        if ($dataTemplate = $input->getOption('dataTemplate')) {
            if (!is_file($dataTemplate) || !is_readable($dataTemplate)) {
                throw new \InvalidArgumentException("The template file '{$dataTemplate}' does not exist or is not readable.");
            }
            $reader->dataTemplate = $dataTemplate;
        }

        $reader->localOnly = !$input->getOption('online');

        try {
            $reader->build($input->getOption('filter'));
        } catch (ClientExceptionInterface $e) {
            print $e->getMessage();
        }

        return self::SUCCESS;
    }


}