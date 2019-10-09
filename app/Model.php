<?php

namespace Laratube;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Str;

class Model extends BaseModel
{

    protected $guarded = [];
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // $model->id = Str::uuid();
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

}
