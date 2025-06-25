<?php

namespace PISystems\ExactOnline\Model;

use PISystems\ExactOnline\Enum\HttpMethod;

class DataSource
{
    const array METHODS = [HttpMethod::GET];
    const string ENDPOINT = 'https://start.exactonline.nl/api/v1/';
}