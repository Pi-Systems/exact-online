<?php

namespace PISystems\ExactOnline\Util;

use Symfony\Component\Dotenv\Dotenv;

class SanityCheck
{
    public static function checkEnv(string $source, string $env = 'dev', ?bool $throw = null): void
    {
        $throw = $throw ?? self::shouldThrow();
        if (!str_contains($actualEnv = strtolower($_ENV['APP_ENV'] ?? 'prod'), $env)) {

            $msg = "Running the environment in {$actualEnv} is not recommended.\n" .
                "It is really recommended to run the code from ({$source}) only in {$env} environments.\n" .
                "If you are running in a {$env} env, then ensure that \$_ENV['APP_ENV'] contains '{$env}' in its value\n" .
                (
                !class_exists(Dotenv::class)
                    ? "We recommend using symfony Dotenv (https://github.com/symfony/dotenv) or similar tools to manage these properties.\n"
                    : "Dotenv from symfony likely has the wrong env loaded (This impacts more than just this compiler)\n"
                );

            if ($throw) {
                throw new \RuntimeException($msg);
            }
            trigger_error(
                $msg,
                E_USER_WARNING
            );
        }
    }

    protected static function shouldThrow(): bool
    {
        static $throw = (bool)($_ENV['EXACT_SANITY_CHECK_THROWS'] ?? false);
        return $throw;
    }
}