<?php

namespace PISystems\ExactOnline\Model;

interface FilterInterface
{
    public function getFilter(string $dataSource) : string;
}