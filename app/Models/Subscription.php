<?php

namespace App\Models;

use App\Application\Uuids;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use Uuids;

    protected $guarded = [];
}
