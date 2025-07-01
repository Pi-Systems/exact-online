<?php

namespace PISystems\ExactOnline\Polyfill;

use Psr\Cache\CacheItemInterface;

/**
 * Cache files for this class are formatted thus:
 *
 * > 64byte - TTL
 * > 64byte - Size in bytes
 * > * byte - Content (Size depends on content size)
 *
 */
class SimpleFileCacheItem implements CacheItemInterface
{
    private const int HEADER_SIZE = 144;  // 128 chars, + 2 x unsigned long long
    private ?array $_header = null;
    private mixed $_content = null;

    /**
     * @param string $key Warning: Key is truncated at 128 characters
     * @param string $file
     * @param \DateTimeInterface $expiry
     * @param bool $ignoreTtl
     */
    protected function __construct(
        public string                           $key {
            set {
                $this->key = substr($value, 0, 128);
            }
            get {
                return $this->key;
            }
        },
        private string                          $file {
            set {
                if (!file_exists($value)) {
                    if (!is_writable(dirname($value))) {
                        throw new \RuntimeException("File {$value} does not exist, nor does the directory allow it's creation.");

                    }
                } else {
                    if (!is_readable($value)) {
                        throw new \RuntimeException("File {$value} is not readable.");
                    }
                }

                $this->file = $value;
            }
            get {
                return $this->file;
            }
        },
        private \DateTimeInterface $expiry,
        private readonly bool $ignoreTtl
    )
    {
        $this->key = substr($key, 0, 128);
    }

    public static function create(
        string $key,
        string $file,
        \DateTimeInterface|int    $ttl,
        bool $ignoreTtl = false
    ): static
    {
        if (is_int($ttl)) {
            $ttl = \DateTimeImmutable::createFromTimestamp(time() + $ttl);
        }

        return new static($key, $file, $ttl, $ignoreTtl);
    }

    public function createFromExisting(string $file): static
    {
        $headers = $this->readHeader();
        if (!$headers) {
            throw new \RuntimeException("File {$file} does not exist, or could not be read.");
        }

        return new static($headers['key'], $file, $headers['expires'], $headers['size']);
    }

    private function readHeader(): ?array
    {
        if (!file_exists($this->file)) {
            return null;
        }

        return $this->_header ??= (function ()  {

            $handle = fopen($this->file, 'rb');
            $header = fread($handle, self::HEADER_SIZE);
            fclose($handle);
            $header = unpack('a128key/Pexpires/Psize', $header);

            $key = $header['key'];
            $size = $header['size'];
            $expires = $header['expires'];

            $created = filectime($this->file);
            $expired = !(0 === $expires) && $expires < time();

            return ['key'=>$key, 'size' => $size, 'created' => $created, 'expires' => $expires, 'expired' => $expired];
        })();
    }

    public function write(): void
    {
        $expiry = $this->expiry;
        if ($expiry instanceof \DateTimeInterface) {
            $expiry = $expiry->getTimestamp();
        }

        $content = serialize($this->_content??'');

        $len = strlen($content);
        $packed = pack('a128PP', $this->key, $expiry??0, $len);
        $handle = fopen($this->file, 'w+b');
        fwrite($handle, $packed);
        fwrite($handle, $content);
        fclose($handle);

        chmod($this->file, 0640); // wr-
    }



    public function getKey(): string
    {
        return $this->key;
    }

    public function get(): mixed
    {
        if (null === $this->readHeader()) {
            return null;
        }

        $handle = fopen($this->file, 'rb');
        fseek($handle, self::HEADER_SIZE); // Skip over header, 128 chars, + 2 x unsigned long long
        $content = fread($handle, $this->_header['size']);

        $content = unserialize($content);
        fclose($handle);

        return $content;
    }

    public function isHit(): bool
    {
        if (!file_exists($this->file)) {
            return false;
        }

        // Header must be loaded even if we ignore the timeout, we still need to know the content-length
        $header = self::readHeader();

        if ($this->ignoreTtl) {
            return true;
        }

        if ($header['expired']) {
            return false;
        }

        return true;
    }

    public function set(mixed $value): static
    {
        $this->_content = $value;
        return $this;
    }

    public function expiresAt(?\DateTimeInterface $expiration): static
    {
        $this->expiry = \DateTimeImmutable::createFromInterface($expiration);
        return $this;
    }

    public function expiresAfter(\DateInterval|int|null $time): static
    {
        if ($time instanceof \DateInterval) {
            $expiration = new \DateTimeImmutable();
            $expiration = $expiration->add($time);
            $this->expiry = $expiration;
            return $this;
        }

        $expiration = time() + ($time ?? 60*60*24*365);

        $this->expiry = \DateTimeImmutable::createFromTimestamp($expiration);
        return $this;
    }
}
