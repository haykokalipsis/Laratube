<?php

namespace App\Models;

use App\Application\Uuids;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable, Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $incrementing = false;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    public function toggleVote($entity, $vote_type)
    {
        $vote = $entity->votes->where('user_id', $this->id)->first();

        // Return either updated vote or new vote, depending on has the user voted on this entity before.
        if ($vote) {
            $vote->update([
                'type' => $vote_type
            ]);

            return $vote->refresh();
        } else {
            return $entity->votes()->create([
                'type' => $vote_type,
                'user_id' => $this->id
            ]);
        }
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
