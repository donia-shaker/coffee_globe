<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $guarded = [];

    protected $casts = [
        'display_name' => 'array',  // Cast name to an array, automatically encoding and decoding JSON
    ];
}
