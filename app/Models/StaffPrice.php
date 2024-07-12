<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'single_space_price',
        'double_space_price',
    ];
}
