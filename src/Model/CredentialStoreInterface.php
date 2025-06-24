<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Builder\Exact;
use Psr\Http\Message\RequestInterface;

interface CredentialStoreInterface
{
    public function hasClientId() : bool;

    public function hasClientSecret() : bool;

    public function hasAuthorizationCode(Exact $exact) : bool;
    public function hasRefreshCodeFor(Exact $exact) : bool;

    public function isTokenValid(Exact $exact) : bool;

    public function obtainRequest(Exact $exact) : RequestInterface;

    public function refreshRequest(Exact $exact) : RequestInterface;
}