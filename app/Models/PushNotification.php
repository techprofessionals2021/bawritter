<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
	public $timestamps = false;

    protected $fillable = [
        'number',        
        'user_id',        
    ];

}
