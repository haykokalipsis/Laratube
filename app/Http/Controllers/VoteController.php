<?php

namespace Laratube\Http\Controllers;

use Laratube\Video;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function vote(Video $video, $type)
    {
        return auth()->user()->toggleVote($video, $type);
    }
}
