<?php

namespace PISystems\ExactOnline\Polyfill;

class Validation
{
    public static function is_guid(
        string $uid
    ) : bool
    {
        if (function_exists('\is_guid')) {
            return \is_guid($uid);
        }

        return preg_match(
            '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/',
            strtolower($uid)
        );
    }
}
