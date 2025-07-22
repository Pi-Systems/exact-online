<?php

namespace PISystems\ExactOnline\Builder\Compiler\Interfaces;

interface RemoteDocumentLoaderInterface
{
    public function getPage(string $uri): ?\DOMDocument;
}