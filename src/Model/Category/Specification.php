<?php

namespace YMScanner\Model\Category;

use YMScanner\Model;

class Specification extends Model {

    public function __construct($object)
    {
        parent::__construct($object);
    }

    public function getName() : string
    {
        return (string) $this->name;
    }

    public function getModels() : int
    {
        return (int) $this->models;
    }

    public function getGroupName() : string
    {
        return (string) $this->group_name;
    }
}