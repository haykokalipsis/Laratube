<?php

namespace Laratube;

//use Illuminate\Database\Eloquent\Model; Удоляй

class Channel extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
