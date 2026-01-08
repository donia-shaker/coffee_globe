<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class ContactUs extends Model
{
    use SoftDeletes;
    protected $guarded = ['created_at','updated_at'];

    protected $casts = [
        'name' => 'array',  // Cast name to an array, automatically encoding and decoding JSON
        'value' => 'array',  // Cast name to an array, automatically encoding and decoding JSON
    ];

}
