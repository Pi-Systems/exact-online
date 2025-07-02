<?php

namespace PISystems\ExactOnline\Model;

interface SelectionInterface
{
    public function getSelection(string $dataSource) : string;
}
