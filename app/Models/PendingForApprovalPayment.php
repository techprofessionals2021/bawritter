<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Wallet\Transactionable;

class PendingForApprovalPayment extends Model
{
    use Transactionable;

    protected $fillable = [
        'user_id',
        'method',
        'amount',
        'reference',
        'attachment',
        'status',
        'payment_reason',
        'cart',
        'payment_reasonable_id'
    ];

    protected $casts = [
        'cart' => 'object',
    ];

    function from()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
