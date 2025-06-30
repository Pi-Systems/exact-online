<?php

namespace PISystems\ExactOnline\Polyfill;

/**
 * Levels taken from Monolog\Logger constants
 */
class SimpleClosureLogger extends SimpleAbstractLogger
{
    public function __construct(
        protected \Closure $handler
    ) {

    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        [$level, $message] = static::formatSimpleMessage($level, $message, $context);
        call_user_func_array($this->handler, [$level, $message]);
    }
}
