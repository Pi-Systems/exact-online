<?php

namespace PISystems\ExactOnline\Polyfill;

use Psr\Log\LoggerInterface;

/**
 * Levels taken from Monolog\Logger constants
 */
class SimpleClosureLogger implements LoggerInterface
{
    public function __construct(
        protected \Closure $handler
    ) {

    }

    /**
     * Detailed debug information
     */
    public const int DEBUG = 100;

    /**
     * Interesting events
     *
     * Examples: User logs in, SQL logs.
     */
    public const int INFO = 200;

    /**
     * Uncommon events
     */
    public const int NOTICE = 250;

    /**
     * Exceptional occurrences that are not errors
     *
     * Examples: Use of deprecated APIs, poor use of an API,
     * undesirable things that are not necessarily wrong.
     */
    public const int WARNING = 300;

    /**
     * Runtime errors
     */
    public const int ERROR = 400;

    /**
     * Critical conditions
     *
     * Example: Application component unavailable, unexpected exception.
     */
    public const int CRITICAL = 500;

    /**
     * Action must be taken immediately
     *
     * Example: Entire website down, database unavailable, etc.
     * This should trigger the SMS alerts and wake you up.
     */
    public const int ALERT = 550;

    /**
     * Urgent alert.
     */
    public const int EMERGENCY = 600;

    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->log(self::EMERGENCY, $message, $context);
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->log(self::ALERT, $message, $context);
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->log(self::CRITICAL, $message, $context);
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->log(self::ERROR, $message, $context);
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->log(self::WARNING, $message, $context);
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->log(self::NOTICE, $message, $context);
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->log(self::INFO, $message, $context);
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->log(self::DEBUG, $message, $context);
    }

    public static function toLogicalLevel(int $level) : int
    {
        return match(true) {
            $level >= self::EMERGENCY => self::EMERGENCY,
            $level >= self::ALERT => self::ALERT,
            $level >= self::CRITICAL => self::CRITICAL,
            $level >= self::ERROR => self::ERROR,
            $level >= self::WARNING => self::WARNING,
            $level >= self::NOTICE => self::NOTICE,
            $level >= self::INFO => self::INFO,
            default => self::DEBUG
        };
    }

    public static function toLogLevel(int $level) : string {
        return match(self::toLogicalLevel($level)) {
            self::EMERGENCY => 'EMERGENCY',
            self::ALERT => 'ALERT',
            self::CRITICAL => 'CRITICAL',
            self::ERROR => 'ERROR',
            self::WARNING => 'WARNING',
            self::NOTICE => 'NOTICE',
            self::INFO => 'INFO',
            default => 'DEBUG'
        };
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        if (!is_int($level) && !is_string($level)) {
            throw new \InvalidArgumentException('Level must be an integer or a string');
        }

        if (!empty($context) && !array_is_list($context)) {
            $message = str_replace($message, array_keys($context), array_values($context));
        }

        // We only support int from this point on.
        if (is_string($level)) {
            try {
                $level = constant(self::class . '::' . strtoupper($level));
            } catch (\Exception) {
                $this->log(self::INFO, "Unknown log level used, defaulting message into the info channel.");
                $level = self::INFO;
            }
        } else {
            $level = self::toLogicalLevel($level);
        }

        call_user_func_array($this->handler, [$level, $message]);
    }
}