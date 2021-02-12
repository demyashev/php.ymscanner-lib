<?php

namespace YMScanner\Model;

use YMScanner\Model;

class Brand extends Model {

    public function __construct($object)
    {
        parent::__construct($object);
    }

    public function getName() : string
    {
        return (string) $this->name;
    }

    public function getBrandId() : int
    {
        return (int) $this->brand_id;
    }

    public function getModelsQuantity() : int
    {
        return (int) $this->models_quantity;
    }

    public function getLogo() : string
    {
        return (string) $this->logo;
    }
}