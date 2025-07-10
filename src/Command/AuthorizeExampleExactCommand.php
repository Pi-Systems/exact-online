<?php

namespace PISystems\ExactOnline\Command;

use PISystems\ExactOnline\Exact;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AuthorizeExampleExactCommand extends Command
{
    public function __construct(private readonly Exact $exact)
    {
        parent::__construct('exact:authorize');
    }

    protected function configure(): void
    {
        $this->addArgument(
            'authorization_code',
            InputArgument::OPTIONAL,
            "This is acquired through the oAuth loop, it is a string that usually formatted as such:\n".
            "stamp{country}{country_code).{encrypted_token}\n".
            "Example: stampNL0001.Hoc3ETC\n".
            "One can also throw in the entire url, as long as it contains the code in the query string.\n" .
            "\n".
            "Warning: The code/url is only valid for upto a minute, so don't be to slow."
        );

        $this->addOption('refresh', 'r', InputOption::VALUE_NONE, 'Refresh the division, do not rely on the cache.');
        $this->addOption('logout', null, InputOption::VALUE_NONE, 'Removes all login data and exits.');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('logout')) {
            if ($this->exact->logout()) {
                $output->writeln("Logged out (Reset all login data, no matter their state).");
                return self::SUCCESS;
            } else {
                $output->writeln("Failed to log out, could not clear the persisted data.");
                return self::FAILURE;
            }
        }


        $authCode = $input->getArgument('authorization_code');

        if (filter_var($authCode, FILTER_VALIDATE_URL)) {
            $query = parse_url($authCode, PHP_URL_QUERY);
            $params = [];
            parse_str($query, $params);
            $authCode = $params['code'] ?? null;

            if (!$authCode) {
                $output->writeln("No code found in the query string.");
                return self::FAILURE;
            }

            $authCode = urldecode($authCode);
        }


        if ($authCode) {
            if ($this->exact->isAuthorized()) {
                throw new \LogicException("Setting an authorization code while already authorized makes no sense, call this command with --logout before calling this again.");
            }
            $this->exact->setOrganizationAuthorizationCode(
                urldecode($authCode),
            );
        }

        if (!$this->exact->isAuthorized()) {
            $oauth = $this->exact->oAuthUri();

            $output->write('Authorization Code Requested, please visit the below link.'
                . PHP_EOL
                . PHP_EOL
                . 'WARNING: This token expires FAST! (Around a minute tops)'
                . PHP_EOL
                . "Please call the command again with the oAuth return value as it's only argument. (Ensure to wrap the argument in single quotes, urldecode is not needed. but it cannot contain anything other than the code.)"
                . PHP_EOL
                . PHP_EOL
                . $oauth
                . PHP_EOL
                . PHP_EOL
            );
            return self::FAILURE;
        }

        $output->writeln(
            "Exact loaded and ready, we're using administration/organization/division <info>" . $this->exact->getDivision(!$input->getOption('refresh')) . "</info>"
        );
        return self::SUCCESS;
    }


}