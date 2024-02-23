<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmittedWork extends Model
{

    public $table = 'submitted_works';

    protected $fillable = [
        'message',
        'name',
        'display_name',
        'user_id',
        'order_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }
}
