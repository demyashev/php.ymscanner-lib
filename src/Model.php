<?php

namespace YMScanner;

class Model extends \stdClass {

    public function __construct($object)
    {
        foreach ($object as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id ? (int) $this->id : 0;
    }
}