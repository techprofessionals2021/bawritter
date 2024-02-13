<?php
namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    public function user()
    {
        return $this->belongsTo('App\models\User', 'user_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }
}
