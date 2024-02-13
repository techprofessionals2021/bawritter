<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{

    use SoftDeletes;

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'name'
    ];
}
