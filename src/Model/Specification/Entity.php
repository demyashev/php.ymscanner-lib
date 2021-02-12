<?php

namespace YMScanner\Model\Specification;

use YMScanner\Model;

class Entity extends Model {

    public function __construct($object)
    {
        parent::__construct($object);
    }

    public function getName() : string
    {
        return (string) $this->name;
    }

    public function getValue() : string
    {
        return (string) $this->value;
    }
}