<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalService extends Model
{
    protected $fillable = [
        'type', 'name', 'description', 'rate', 'inactive'
    ];
}
