<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $guarded = [];
    
    protected $casts = [
        'display_name' => 'array',  // Cast name to an array, automatically encoding and decoding JSON
    ];
}
