<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Http\Requests\Videos\UpdateVideoRequest;

class VideoController extends Controller
{
    public function updateViews(Video $video)
    {
        $video->increment('views');
        return response()->json();
    }

    public function update(UpdateVideoRequest $request, Video $video)
    {
        $video->update($request->only(['title', 'description']));
        return redirect()->back();
    }
}
