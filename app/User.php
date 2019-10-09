<?php

namespace Laratube;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $incrementing = false;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
        // $model->id = Str::uuid();
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

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
}
