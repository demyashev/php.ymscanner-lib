<?php

namespace YMScanner\Model;

use YMScanner\Model;

class Price extends Model {

    public function __construct($object)
    {
        parent::__construct($object);
    }

    public function getPrice() : int
    {
        return (int) $this->price;
    }

    public function getPriceUpdated() : string
    {
        return (string) $this->price_updated;
    }

    public function getPriceUpdatedTimeStamp() : int
    {
        $timestamp = 0;
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->getPriceUpdated());

        if (false !== $dateTime) {
            $timestamp = $dateTime->getTimestamp();
        }

        return $timestamp;
    }
}