<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCompany extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'name' => 'array',  // Cast name to an array, automatically encoding and decoding JSON
        'text' => 'array',  // Cast name to an array, automatically encoding and decoding JSON
    ];
}
