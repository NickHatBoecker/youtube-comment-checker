<?php

namespace App\Model;

class Video
{
    public $id;
    public $thumbnail;
    public $title;
    public $owner;
    public $numComments;
    public $hasNewComments = false;
}
