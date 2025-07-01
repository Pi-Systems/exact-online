<?php

namespace PISystems\ExactOnline\Polyfill;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * This is not optimized for mass caching of thousands.
 * Heck, there is nothing 'optimized' about this at all.
 * This is just to ensure we can call and cache arbitrary info during DevReader without having to inject
 * many 'heavy' packages.
 *
 * This does the job, and allows a user to overwrite it should they wish.
 */
class SimpleFileCache implements CacheItemPoolInterface
{
    private array $schedule = [];
    private array $scheduleDeletes = [];

    /**
     * If set, $hit will always return true as long as the actual cache file is found.
     * Timeout will no longer influence it.
     * (Useful for creating caches that should not expire, but you still want a general idea of how old something is)
     * @var bool
     */
    public bool $ignoreTimeout = false;

    public function __construct(
        public readonly string $path,
        public readonly string $pool = 'default',
        public readonly string $prefix = '',
        public readonly int $defaultTtl = 3600,
        public readonly int $fileMode = 0644,
        public readonly int $dirMode = 0755,
    )
    {
        if (preg_match('/[^a-zA-Z0-9.\-_]/', $this->pool)) {
            throw new \LogicException('Cache pool may only contain natural numbers, letters, dashes, dots and underscores.');
        }
        if (str_ends_with($this->path, '/')) {
            throw new \LogicException('Cache path must not end with a slash.');
        }
    }

    protected function getPoolPath(): string {

        $path = $this->path . '/' . $this->pool;

        if (is_file($path)) {
            throw new \RuntimeException('Cache path is a file, it must be a directory, or a path where a directory may be created by this process..');
        }

        if (is_dir($path) && is_writable($path)) {
            return $path;
        }
        
        if (!is_dir($path) && !mkdir($path, $this->dirMode, true) && !is_dir($path)) {
            throw new \RuntimeException(sprintf('Cache Pool Directory "%s" could not be created.', $path));
        }
        
        return $path;
    }

    public function keyToFileName(string $key): string
    {
        return sha1($this->prefix . $key) . '.cache';
    }

    public function getItem(string $key): CacheItemInterface
    {
        $name = $this->keyToFileName($key);
        $file = $this->getPoolPath() . '/' . $name;

        return SimpleFileCacheItem::create($key, $file, $this->defaultTtl, $this->ignoreTimeout);

    }

    public function getItems(array $keys = []): iterable
    {
        return array_map([$this, 'getItem'], $keys);
    }

    public function hasItem(string $key): bool
    {
        $this->getItem($key)->isHit();
        return true;
    }

    public function clear(): bool
    {
        $d = dir($this->getPoolPath());

        while (false !== ($entry = $d->read())) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            if (!str_ends_with($entry, '.cache')) {
                continue;
            }

            unlink($entry);
        }

        $d->close();
        return true;
    }

    public function deleteItem(string $key): bool
    {
        $this->scheduleDeletes[] = $key;
        return false;
    }

    public function deleteItems(array $keys): bool
    {
        foreach ($keys as $key) {
            $this->deleteItem($key);
        }
        return true;
    }

    public function save(CacheItemInterface $item): bool
    {
        $this->schedule[spl_object_id($item)] = $item;
        return true;
    }

    public function saveDeferred(CacheItemInterface $item): bool
    {
        $this->schedule[spl_object_id($item)] = $item;
        return true;
    }

    public function commit(): bool
    {
        foreach ($this->schedule as $item) {
            if ($item instanceof SimpleFileCacheItem) {
                $item->write();
            }
        }
        $this->schedule = [];

        foreach ($this->scheduleDeletes as $key) {
            $file = $this->getPoolPath() . '/' . $this->keyToFileName($key);
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $this->scheduleDeletes = [];
        return true;
    }
}
