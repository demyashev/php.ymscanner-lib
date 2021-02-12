<?php

namespace YMScanner\Model\Info;

use YMScanner\Model\Info;

class Search extends Info {

    public function __construct($object)
    {
        parent::__construct($object);
    }

    public function getCategoryName() : string
    {
        return (string) $this->category_name;
    }

    public function getBrandName() : string
    {
        return (string) $this->brand_name;
    }
}