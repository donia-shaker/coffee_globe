<?php

namespace App\Models;

use DoniaShaker\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientReview extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'name' => 'array',  // Cast name to an array, automatically encoding and decoding JSON
        'text' => 'array',  // Cast name to an array, automatically encoding and decoding JSON
    ];

    public function media()
    {
        return $this->hasOne(Media::class, 'model_id')->where('model', 'client');
    }
}
