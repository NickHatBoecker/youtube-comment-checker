<?php

namespace App\Controller;

use App\Model\Video;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends AbstractController
{
    /**
     * @Route("/api/get-videos/")
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $videoIds = $request->query->get('videoIds', []);
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

        $accessToken = $this->getParameter('youtubeAccessToken');

        $client = new \Google_Client();
        $client->setDeveloperKey($accessToken);

        $youtube = new \Google_Service_YouTube($client);
        $data = $youtube->videos->listVideos('snippet,statistics', ['id' => $videoIds])->items;

        $videos = [];

        /** @var \Google_Service_YouTube_Video $videoData */
        foreach ($data as $videoData) {
            $snippet = $videoData->getSnippet();

            $video = new Video();
            $video->thumbnail = $snippet->getThumbnails()->getDefault();
            $video->owner = $snippet->getChannelTitle();
            $video->title = $snippet->getTitle();
            $video->id = $videoData->getId();
            $video->numComments = (int) $videoData->getStatistics()->getCommentCount();

            $videos[] = $video;
        }

        return $videos;
    }
}
