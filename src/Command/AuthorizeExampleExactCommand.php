<?php

namespace PISystems\ExactOnline\Command;

use PISystems\ExactOnline\Builder\Exact;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AuthorizeExampleExactCommand extends Command
{
    public function __construct(private readonly Exact $exact)
    {
        parent::__construct('exact:examples:authorize');
    }

    protected function configure()
    {
        $this->addArgument(
            'authorization_code',
            InputArgument::OPTIONAL,
            "This is acquired through the oAuth loop, it is a string that usually formatted as such:\n".
            "stamp{country}{country_code).{encrypted_token}\n".
            "Example: stampNL0001.Hoc3ETC\n".
            "\n".
            "This entry is volatile, the code (message) it contains is only valid for upto 1-2 minutes at best.\n".
            "This example cannot really help in managing this properly, one would need to implement a webserver that can accept\n".
            "the callback from exact to make this automatic.\n".
            "For now, call this command back with this code as the only argument.\n"
        );
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $authCode = $input->getArgument('authorization_code');

        if ($authCode) {
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
            "Exact loaded and ready, we're using administration/organization/division <info>".$this->exact->getDivision(). "</info>"
        );
        return self::SUCCESS;
    }


}
