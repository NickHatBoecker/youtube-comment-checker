<?php

namespace App\Model;

class Video
{
    public $id;
    public $thumbnail;
    public $title;
    public $owner;
    public $numComments;
    public $numNewComments = 0;
    public $threads = [];

    public function calculateNewComments() {
        $count = 0;

        foreach ($this->threads as $thread) {
            /** @var CommentThread $thread */
            $count += $thread->newCommentsCount;
        }

        $this->numNewComments = $count;
    }
}
