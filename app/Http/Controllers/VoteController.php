<?php

namespace Laratube\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laratube\Comment;
use Laratube\Video;
use Illuminate\Http\Request;

class VoteController extends Controller
{
//    public function vote(Video $video, $type)
//    {
//        return auth()->user()->toggleVote($video, $type);
//    }

    public function vote($entity_type, $entity_id, $vote_type)
    {
        switch($entity_type) {
            case 'comment' : $entity = Comment::find($entity_id); break;
            case 'video'   : $entity = Video  ::find($entity_id); break;
            default : throw new ModelNotFoundException('Entity not found');
        }

        return auth()->user()->toggleVote($entity, $vote_type);
    }
}
