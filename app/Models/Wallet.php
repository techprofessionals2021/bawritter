<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance'
    ];

    /**
     * Get the owning commentable model.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\WalletTransaction', 'wallet_id')->with('relatedTable');
    }
}
