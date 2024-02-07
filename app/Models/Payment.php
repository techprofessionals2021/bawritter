<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Wallet\Transactionable;

class Payment extends Model
{
	use Transactionable;

    protected $fillable = [
        'number',
        'user_id',
        'method',
        'amount',
        'reference',
        'attachment'
    ];


    function from()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }


}
