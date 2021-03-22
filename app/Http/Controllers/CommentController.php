<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comments\StoreCommentRequest;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Video;

class CommentController extends Controller
{
    public function comments(Video $video)
    {
        return $video->comments()->paginate(3);
    }

    public function replies(Comment $comment)
    {
        return $comment->replies()->paginate(3);
    }

    public function store(StoreCommentRequest $request, Video $video)
    {
        return auth()->user()->comments()->create([
                'body' => $request->body,
                'video_id' => $video->id,
                'comment_id' => $request->comment_id
            ])
                ->fresh(); // We call fresh to get the eager loaded relationships for the model
    }
}
