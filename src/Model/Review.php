<?php

namespace YMScanner\Model;

use YMScanner\Model;

class Review extends Model {

    public function __construct($object)
    {
        parent::__construct($object);
    }

    public function getUid() : int
    {
        return (int) $this->uid;
    }

    public function getAuthor() : string
    {
        return (string) $this->author;
    }

    public function getAvatar() : string
    {
        return (string) $this->avatar;
    }

    public function getRating() : float
    {
        return (float) $this->rating;
    }

    public function getPluses() : string
    {
        return (string) $this->pluses;
    }

    public function getMinuses() : string
    {
        return (string) $this->minuses;
    }

    public function getComment() : string
    {
        return (string) $this->comment;
    }

    public function getPostDate() : string
    {
        return (string) $this->postdate;
    }

    public function getDPub() : string
    {
        return (string) $this->dpub;
    }

    public function getDPubTimestamp() : int
    {
        $timestamp = 0;
        $datetime = \DateTime::createFromFormat('Y-m-d', $this->getDPub());

        if (false !== $datetime) {
            $timestamp = $datetime->getTimestamp();
        }

        return $timestamp;
    }

    public function getPictures() : array
    {
        /**
         * Яндекс.Маркет до сих пор не понимает сам, как и куда крепить картинки,
         *  поэтому здесь массив *чего-то*. Как с этим работать не понятно.
         *
         * @todo via Model
         */
        return (array) $this->pictures;
    }

    public function getSubcomments() : array
    {
        $_subcomments = (array) $this->subcomments;
        $subcomments = [];

        foreach ($_subcomments as $_subcomment) {
            $subcomments[] = new Subcomment($_subcomment);
        }

        return $subcomments;
    }
}