<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'user_id',
    ];
}
