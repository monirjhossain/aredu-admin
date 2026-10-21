<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class WebsiteVideoController extends Controller
{
    public function index()
    {
        $videos = WebsiteVideo::query()->orderBy('sort')->orderBy('id')->get();

        return view('admin.website_videos.index', [
            'videos' => $videos,
        ]);
    }

    public function create()
    {
        return view('admin.website_videos.form', [
            'video' => new WebsiteVideo([
                'sort' => (int) WebsiteVideo::query()->max('sort') + 1,
                'is_active' => true,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        WebsiteVideo::create($this->validated($request));
        Session::flash('success', 'Video added.');

        return redirect()->route('video-gallery.index');
    }

    public function edit(WebsiteVideo $website_video)
    {
        return view('admin.website_videos.form', [
            'video' => $website_video,
        ]);
    }

    public function update(Request $request, WebsiteVideo $website_video)
    {
        $website_video->update($this->validated($request, $website_video));
        Session::flash('success', 'Video updated.');

        return redirect()->route('video-gallery.index');
    }

    public function destroy(WebsiteVideo $website_video)
    {
        $website_video->delete();
        Session::flash('success', 'Video removed.');

        return redirect()->route('video-gallery.index');
    }

    private function validated(Request $request, ?WebsiteVideo $existing = null): array
    {
        $data = $request->validate([
            'youtube_url' => 'required|string|max:500',
            'sort' => 'nullable|integer|min:0|max:9999',
        ]);

        $youtubeId = WebsiteVideo::extractYoutubeId($data['youtube_url']);
        if (!$youtubeId) {
            throw ValidationException::withMessages([
                'youtube_url' => 'Paste a YouTube Shorts or watch link.',
            ]);
        }

        $sort = $data['sort'];
        if ($sort === null || $sort === '') {
            $sort = $existing ? $existing->sort : ((int) WebsiteVideo::query()->max('sort') + 1);
        }

        return [
            'title' => $youtubeId,
            'tag' => null,
            'youtube_url' => trim($data['youtube_url']),
            'sort' => (int) $sort,
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
