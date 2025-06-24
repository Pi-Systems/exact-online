<?php

namespace PISystems\ExactOnline\Polyfill;

use Psr\Http\Message\StreamInterface;

class SimpleStream implements StreamInterface
{
    private ?int $length = null;
    private int $position = 0;
    private array $meta = [];

    public function __construct(
        public string $body,
        public bool $readonly = false
    )
    {

    }

    public function setMetaData(string $key, mixed $value): static
    {
        $this->meta[$key] = $value;
        return $this;
    }

    public function deleteMetaData(string $key): static
    {
        unset($this->meta[$key]);
        return $this;
    }

    public function __toString(): string
    {
        return $this->body;
    }

    public function close(): void
    {
        // Needs to do nothing
    }

    public function detach()
    {
        // Needs to do nothing
    }

    public function getSize(): ?int
    {
        return $this->length ??= strlen($this->body);
    }

    public function tell(): int
    {
        return $this->position;
    }

    public function eof(): bool
    {
        if ($this->position >= $this->getSize()) {
            return true;
        }
        return false;
    }

    public function isSeekable(): bool
    {
        return true;
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        switch ($whence) {
            case SEEK_SET:
                $this->position = max(0,min($offset, $this->getSize()));
            break;
            case SEEK_CUR:
                $this->seek($this->position + $offset);
                break;
            case SEEK_END:
                // This one makes no damn sense, but it's required by the interface.
                $this->seek($this->getSize() + $offset);
                break;
        }
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function isWritable(): bool
    {
        return !$this->readonly;
    }

    public function write(string $string): int
    {
        if (!$this->isWritable()) {
            throw new \RuntimeException('Cannot write to a readonly stream');
        }
        return $this->length = strlen(
            $this->body = substr_replace(
                $this->body,
                $string,
                $this->position,
                strlen($string)
            )
        );
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read(int $length): string
    {
         $content = substr($this->body, $this->position, $length);
         $this->position += strlen($content);
         return $content;
    }

    public function getContents(): string
    {
        return substr($this->body, $this->position);
    }

    public function getMetadata(?string $key = null)
    {
        return $key ? $this->meta[$key] ?? null : $this->meta;
    }
}