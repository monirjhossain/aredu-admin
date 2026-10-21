<?php

namespace App\Http\Controllers\API;

use App\Models\WebsiteVideo;

class VideoController extends BaseController
{
    public function index()
    {
        $videos = WebsiteVideo::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('id')
            ->get()
            ->map(function (WebsiteVideo $video) {
                $youtubeId = $video->youtubeId();
                if (!$youtubeId) {
                    return null;
                }

                return [
                    'id' => $youtubeId,
                    'title' => $video->title,
                    'tag' => $video->tag ?: 'Video',
                    'url' => $video->youtube_url,
                ];
            })
            ->filter()
            ->values();

        return $this->sendResponse(['videos' => $videos], 'successfully.');
    }
}
