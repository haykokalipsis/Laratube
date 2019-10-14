<?php

namespace Laratube;

use Laratube\Channel;

class Video extends Model
{
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('comment_id')->orderBy('created_at', 'DESC');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function editable()
    {
        return auth()->check() && $this->channel->user_id === auth()->user()->id;
    }
}
