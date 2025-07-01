<?php

namespace PISystems\ExactOnline\Model;

use Psr\Http\Message\StreamInterface;

interface AddableStreamInterface extends StreamInterface
{
    public function add(array $elements): int;
}
