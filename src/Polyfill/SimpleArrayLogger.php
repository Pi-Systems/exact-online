<?php

namespace PISystems\ExactOnline\Polyfill;

class SimpleArrayLogger extends SimpleAbstractLogger
{
    protected array $logs = [];

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        [$level, $message] = static::formatSimpleMessage($level, $message, $context);
        $this->logs[$level] ??= [];
        $this->logs[$level][] = [$message, $context];
    }

    public function getLogs($level): iterable {
        $level ??= self::DEBUG;
        if (is_string($level)) {
            try {
                $level = constant(self::class . '::' . strtoupper($level));
            } catch (\Exception) {
                $level = self::INFO;
            }
        } else {
            $level = self::toLogicalLevel($level);
        }


        return new \ArrayIterator($this->logs[$level] ?? []);
    }

    public function getAllLogs() : iterable
    {
        return new \ArrayIterator($this->logs);
    }

    public function clear() : void
    {
        $this->logs = [];
    }
}
