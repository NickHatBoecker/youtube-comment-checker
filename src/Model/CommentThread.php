<?php

namespace App\Model;


class CommentThread
{
    public $topLevelComment;
    public $replies;
    public $isNew = false;
    public $newCommentsCount = 0;
}
