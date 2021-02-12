<?php

namespace YMScanner\Model;

use YMScanner\Model;
use YMScanner\Model\Specification\Entity as SpecificationEntity;

class Specification extends Model {

    public function __construct($object)
    {
        parent::__construct($object);
    }

    public function getName() : string
    {
        return (string) $this->name;
    }

    public function getSubspecs() : array
    {
        $_subspecs = (array) $this->subspecs;
        $subspecs = [];

        foreach ($_subspecs as $_subspec) {
            $subspecs[] = new SpecificationEntity($_subspec);
        }

        return $subspecs;
    }
}