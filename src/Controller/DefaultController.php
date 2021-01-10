<?php

namespace App\Controller;

use App\Model\Comment;
use App\Model\CommentThread;
use App\Model\Video;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends AbstractController
{
    private $youtube;
    private $lastCheck;

    /**
     * @Route("/api/get-videos/")
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $accessToken = $this->getParameter('youtubeAccessToken');

        $client = new \Google_Client();
        $client->setDeveloperKey($accessToken);
        $this->youtube = new \Google_Service_YouTube($client);

        $videoIds = $request->query->get('videoIds', []);
        if ($videoIds) {
            $videoIds = json_decode($videoIds, true);
        }
        $videos = $this->getVideos($videoIds);

        return new JsonResponse($videos);
    }

    /**
     * @param array $videoIds
     *
     * @return array
     */
    private function getVideos(array $videoIds)
    {
        if (!$videoIds) {
            return [];
        }

        $onlyVideoIds = array_map(function ($data) {
            return $data['id'];
        }, $videoIds);

        $data = $this->youtube->videos->listVideos('snippet,statistics', ['id' => $onlyVideoIds])->items;

        $videos = [];

        /** @var \Google_Service_YouTube_Video $videoData */
        foreach ($data as $videoData) {
            $snippet = $videoData->getSnippet();
            $videoId = $videoData->getId();

            $this->lastCheck = $this->getLastCheckByVideoId($videoId, $videoIds);

            $video = new Video();
            $video->thumbnail = $snippet->getThumbnails()->getDefault();
            $video->owner = $snippet->getChannelTitle();
            $video->title = $snippet->getTitle();
            $video->id = $videoId;
            $video->numComments = (int) $videoData->getStatistics()->getCommentCount();
            $video->threads = $this->getComments($videoId);

            $video->calculateNewComments();

            $videos[] = $video;
        }

        return $videos;
    }

    /**
     * @param $videoId
     *
     * @return array
     */
    private function getComments($videoId)
    {
        $data = $this->youtube->commentThreads->listCommentThreads('snippet,replies', ['videoId' => $videoId])->items;

        $threads = [];

        /** @var \Google_Service_YouTube_CommentThread $commentThreadData */
        foreach ($data as $commentThreadData) {
            $newCommentsCount = 0;

            /** @var \Google_Service_YouTube_CommentSnippet $snippet */
            $snippet = $commentThreadData->getSnippet()->getTopLevelComment()->getSnippet();

            $topLevelComment = new Comment();
            $topLevelComment->text = $snippet->getTextDisplay();
            $topLevelComment->published = (new \DateTime($snippet->getPublishedAt()))->getTimestamp();
            $topLevelComment->owner = $snippet->getAuthorDisplayName();
            $topLevelComment->thumbnail = $snippet->getAuthorProfileImageUrl();

            if ($this->lastCheck < $topLevelComment->published) {
                $newCommentsCount += 1;
                $topLevelComment->isNew = true;
            }

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
                    $reply->published = (new \DateTime($replySnippet->getPublishedAt()))->getTimestamp();
                    $reply->owner = $replySnippet->getAuthorDisplayName();
                    $reply->thumbnail = $replySnippet->getAuthorProfileImageUrl();

                    if ($this->lastCheck < $reply->published) {
                        $newCommentsCount += 1;
                        $reply->isNew = true;
                    }

                    $replies[] = $reply;
                }

                $commentThread->replies = $replies;
            }

            if ($newCommentsCount > 0) {
                $commentThread->isNew = true;
                $commentThread->newCommentsCount = $newCommentsCount;
            }

            $threads[] = $commentThread;
        }

        return $threads;
    }

    private function convertJSDate($date)
    {
        return (int) round($date / 1000, 0);
    }

    /**
     * @param string $videoId
     * @param array $videoIds
     *
     * @return int|null
     */
    private function getLastCheckByVideoId($videoId, $videoIds)
    {
        foreach ($videoIds as $video) {
            $id = $video['id'] ??  null;
            if ($id === $videoId) {
                return isset($video['lastCheck']) ? $this->convertJSDate($video['lastCheck']) : null;
            }
        }

        return null;
    }
}
