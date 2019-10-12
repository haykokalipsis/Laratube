<?php

namespace Laratube;

//use Illuminate\Database\Eloquent\Model; Удоляй

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Channel extends Model implements HasMedia
{
    use HasMediaTrait;

    // Relationships    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    
    public function videos()
    {
        return $this->hasMany(Video::class);
    } 
    
    // Other methods
    public function belongsToAuthenticatedUser()
    {
        if ( ! auth()->check())
            return false;

        return $this->user_id === auth()->user()->id;
    }
    
    public function registerMediaConversions( ? Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100);
    }

    public function getImage()
    {
        if ($this->media->first()) {
            return $this->media->first()->getFullUrl('thumb');
        }

        return null;
    }
}
