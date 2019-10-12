<?php

namespace Laratube\Http\Controllers;

use Illuminate\Http\Request;
use Laratube\Channel;
use Laratube\Video;
use Laratube\Jobs\Videos\ConvertForStreaming;
use Laratube\Jobs\Videos\CreateVideoThumbnail;

class UploadVideoController extends Controller
{
    public function index(Channel $channel)
    {
        return view('front.pages.channels.upload', compact('channel'));
    }
    
    public function store(Channel $channel, Request $request)
    {

        $video = $channel->videos()->create([
            'title' => $request->title,
            'path' => $request->video->store("channels/{$channel->id}/videos")
        ]);

        $this->dispatch(new CreateVideoThumbnail($video));
        $this->dispatch(new ConvertForStreaming($video));

        return $video;
    }

    public function show(Video $video)
    {
        if (request()->wantsJson()) {
            return $video;
        }

        return view('front.pages.videos.index', compact('video'));
    }
}
