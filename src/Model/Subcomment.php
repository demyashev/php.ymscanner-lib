<?php

namespace YMScanner\Model;

use YMScanner\Model;

class Subcomment extends Model
{
    public function __construct($object)
    {
        parent::__construct($object);
    }

    public function getAuthor() : string
    {
        return (string) $this->author;
    }

    public function getComment() : string
    {
        return (string) $this->comment;
    }

    public function getPostDate() : string
    {
        return (string) $this->postdate;
    }

    public function getAvatar() : string
    {
        return (string) $this->avatar;
    }
}