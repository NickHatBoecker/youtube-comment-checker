<?php

namespace App\Controller;

use App\Model\Comment;
use App\Model\CommentThread;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommentsController extends AbstractController
{
    /**
     * @Route("/api/get-comments/{videoId}/")
     *
     * @param string $videoId
     */
    public function index($videoId)
    {
        $accessToken = $this->getParameter('youtubeAccessToken');

        $client = new \Google_Client();
        $client->setDeveloperKey($accessToken);

        $youtube = new \Google_Service_YouTube($client);
        $data = $youtube->commentThreads->listCommentThreads('snippet,replies', ['videoId' => $videoId])->items;

        $threads = [];

        /** @var \Google_Service_YouTube_CommentThread $commentThreadData */
        foreach ($data as $commentThreadData) {
            /** @var \Google_Service_YouTube_CommentSnippet $snippet */
            $snippet = $commentThreadData->getSnippet()->getTopLevelComment()->getSnippet();

            $topLevelComment = new Comment();
            $topLevelComment->text = $snippet->getTextDisplay();
            $topLevelComment->published = new \DateTime($snippet->getPublishedAt());
            $topLevelComment->owner = $snippet->getAuthorDisplayName();
            $topLevelComment->thumbnail = $snippet->getAuthorProfileImageUrl();

            $commentThread = new CommentThread();
            $commentThread->topLevelComment = $topLevelComment;

            if ($commentThreadData->getSnippet()->getTotalReplyCount()) {
                $replies = [];

                /** @var \Google_Service_YouTube_Comment $replyData */
                foreach ($commentThreadData->getReplies() as $replyData) {
                    /** @var \Google_Service_YouTube_CommentSnippet $replySnippet */
                    $replySnippet = $replyData->getSnippet();

                    $reply = new Comment();
                    $reply->text = $replySnippet->getTextDisplay();
                    $reply->published = new \DateTime($replySnippet->getPublishedAt());
                    $reply->owner = $replySnippet->getAuthorDisplayName();
                    $reply->thumbnail = $replySnippet->getAuthorProfileImageUrl();

                    $replies[] = $reply;
                }

                // @TODO is this even necessary?
                usort($replies, [$this, 'sortByPublished']);

                $commentThread->replies = $replies;
            }

            $threads[] = $commentThread;
        }

        return new JsonResponse($threads);
    }

    /**
     * @param Comment $a
     * @param Comment $b
     *
     * @return int
     */
    private function sortByPublished (Comment $a, Comment $b)
    {
        if ($a->published === $b->published) {
            return 0;
        }

        return $a->published < $b->published ? -1 : 1;
    }
}
