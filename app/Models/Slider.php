<?php

namespace App\Models;

use DoniaShaker\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;

class Slider extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'title' => 'array',
        'text' => 'array',
    ];
    public function media()
    {
        return $this->hasOne(Media::class, 'model_id')->where('model', 'slider');
    }
}
