<?php

namespace YMScanner\Model;

use YMScanner\Model;

class Info extends Model {

    public function __construct($object)
    {
        parent::__construct($object);
    }

    public function getName() : string
    {
        return (string) $this->name;
    }

    public function getUrl() : string
    {
        return (string) $this->url;
    }

    public function getCategoryId() : int
    {
        return (int) $this->category_id;
    }

    public function getBrandId() : int
    {
        return (int) $this->brand_id;
    }

    public function getSpecsQuantity() : int
    {
        return (int) $this->specs_quantity;
    }

    public function getReviewsQuantity() : int
    {
        return (int) $this->reviews_quantity;
    }

    public function getPhotosQuantity() : int
    {
        return (int) $this->photos_quantity;
    }

    public function getRating() : float
    {
        return (float) $this->rating;
    }

    public function getModOf() : int
    {
        return (int) $this->modof;
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