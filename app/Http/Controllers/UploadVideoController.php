<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\Video;
use App\Jobs\Videos\ConvertForStreaming;
use App\Jobs\Videos\CreateVideoThumbnail;
use Illuminate\Support\Facades\Storage;

class UploadVideoController extends Controller
{
    public function index(Channel $channel)
    {
        return view('front.pages.channels.upload', compact('channel'));
    }

    public function store(Channel $channel, Request $request)
    {
//        $path = $request->video->store("/FFMPEG/channels/{$channel->id}/videos", 'public');

        $video = $channel->videos()->create([
            'title' => $request->title,
            'path' => $request->video->store("/FFMPEG/channels/{$channel->id}/videos")
//            'path' => Storage::disk('public')->url($path)
        ]);

        $this->dispatch(new CreateVideoThumbnail($video));
        $this->dispatch(new ConvertForStreaming($video));

        return $video;
    }
}
