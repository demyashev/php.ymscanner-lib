<?php

namespace YMScanner\Model;

use YMScanner\Model;

class Product extends Model
{
    public function __construct($object)
    {
        parent::__construct($object);
    }

    public function getName(): string
    {
        return $this->name ?? '';
    }
}