<?php

namespace PISystems\ExactOnline\Builder;

use Psr\Container\ContainerInterface;

class DevContainer implements ContainerInterface
{

    private array $services = [];
    private array $awoken = [];
    private bool $sorted = true;
    private array $recursionList = [];

    public function subscribe(array $services) : void
    {
        $this->sorted = false;
        foreach ($services as $id => $args) {

            $priority = $args[0];

            if (!$priority) {
                throw new \RuntimeException("Priority is required as the first argument for service {$id}.");
            }

            $wakeup = $args[1];

            if (!is_callable($wakeup)) {
                throw new \RuntimeException("Wakeup callback for service {$id} must be callable.");
            }

            $shutdown = $args[2] ?? null;

            if ($shutdown && !is_callable($shutdown)) {
                throw new \RuntimeException(
                    "Shutdown callback for service {$id} must be callable."
                );
            }

            $meta  = $args[3] ?? [];

            if (!is_array($meta)) {
                throw new \RuntimeException(
                    "Meta(Options) entry must be a simple array"
                );
            }


            $this->services[$id] = [$priority, $wakeup, $shutdown??null, $meta??[]];
        }
        $this->sort();
    }

    public function register(int $priority, string $id, \closure $wakeup, ?\closure $shutdown = null, array $meta = [])
    {
        if (!class_exists($id)) {
            throw new \RuntimeException(
                "The service {$id} does not exist, this dev container is to simplistic to allow for complex arguments."
            );
        }

        $this->sorted = true;

        $this->services[$id] = [$priority, $wakeup, $shutdown, $meta];
    }

    public function hasAwoken(string $id) : bool
    {
        return array_key_exists($id, $this->awoken);
    }

    public function get(string $id)
    {
        try {
            $this->sort();
            if (!empty($this->recursionList[$id]) && array_key_exists($id, $this->recursionList[$id])) {
                throw new \LogicException("Recursion detected, aborting call.");
            }
            $this->recursionList[$id] = $id;

            if ($this->has($id)) {

                if (array_key_exists($id, $this->awoken)) {
                    return $this->awoken[$id];
                }

                [, $wakeup, , $meta] = $this->services[$id];

                $args = [];
                if (array_key_exists('args', $meta)) {
                    foreach ($meta['args'] as $arg) {
                        $args[] = $this->get($arg);
                    }

                }

                $args[] = $meta;
                return $this->awoken[$id] = $wakeup(... $args);
            } else {
                throw new \LogicException("Unknown service {$id}.");
            }
        } finally {
            unset($this->recursionList[$id]);
        }
    }

    public function getServicesByTag(string $tag) : iterable
    {
        foreach ($this->services as $id => $service) {
            if (in_array($tag, $service[3]['tags']??[])) {
                yield $id;
            }
        }
    }

    public function has(string $id) : bool
    {
        return array_key_exists($id, $this->services);
    }

    private function sort()
    {
        if ($this->sorted) { return; }
        uasort($this->services, fn($a, $b) => ($b[0] <=> $a[0]));
        $this->sorted = true;
    }


    public function __destruct()
    {
        foreach ($this->awoken as $id => $awoken) {
            if (isset($this->services[$id][2])) {
                $this->services[$id][2]($awoken);
                unset($this->awoken[$id]);
            }
        }
    }
}
