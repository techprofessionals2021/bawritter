<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRecord extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'option_key',
        'option_value'
    ];
}
