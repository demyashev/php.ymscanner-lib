<?php

namespace YMScanner\Model;

use YMScanner\Model;

class Category extends Model {

    public function __construct($object)
    {
        parent::__construct($object);
    }

    public function getName() : string
    {
        return (string) $this->name;
    }
}