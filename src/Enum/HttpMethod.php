<?php

namespace PISystems\ExactOnline\Enum;

enum HttpMethod : string
{
    /**
     * READ data
     */
    case GET = 'GET';
    /**
     * Alias to GET
     */
    public const HttpMethod READ = self::GET;
    /**
     * CREATE data
     */
    case POST = 'POST';
    /**
     * Alias to POST
     */
    public const HttpMethod CREATE = self::POST;
    /**
     * UPDATE data
     */
    case PUT = 'PUT';
    /**
     * Alias to PUT
     */
    public const HttpMethod UPDATE = self::PUT;
    /**
     * DELETE data
     */
    case DELETE = 'DELETE';
    /**
     * Alias to DELETE
     */
    public const HttpMethod REMOVE = self::DELETE;
}
