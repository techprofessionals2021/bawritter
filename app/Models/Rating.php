<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{

    protected $fillable = [
        'id',
        'order_id',
        'user_id',
        'number',
        'comment'
    ];

    function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    function order()
    {
        return $this->belongsTo('App\models\Order');
    }
}
