<?php

namespace PISystems\ExactOnline\Polyfill;

use PISystems\ExactOnline\Model\AddableStreamInterface;
use PISystems\ExactOnline\Model\RequestAwareStreamInterface;
use Psr\Http\Message\RequestInterface;

class FormStream extends SimpleStream implements AddableStreamInterface, RequestAwareStreamInterface
{
    public const string CONTENT_TYPE = 'application/x-www-form-urlencoded';

    private array $formElements = [];

    public function __construct(array $elements = [], bool $readonly = false)
    {
        if (!empty($elements) && array_is_list($elements)) {
            throw new \InvalidArgumentException('FormStream expects an associative array');
        }

        $this->formElements = $elements;

        parent::__construct(http_build_query($elements), $readonly);
    }

    public function add(array $elements): int
    {
        if ($this->readonly) {
            throw new \RuntimeException('Stream is read only, refusing to write once constructed.');
        }
        return $this->write($elements);
    }

    public function write(array|string $string): int
    {
        if ($this->readonly) {
            throw new \RuntimeException('Stream is read only, refusing to write once constructed.');
        }

        if (is_string($string)) {
            parent::write($string);
        }

        $this->formElements += $string;

        $this->reset();
        return parent::write( http_build_query($this->formElements) );
    }

    public function configureRequest(RequestInterface $request): RequestInterface
    {
        return $request->withHeader('Content-Type', self::CONTENT_TYPE);
    }
}
