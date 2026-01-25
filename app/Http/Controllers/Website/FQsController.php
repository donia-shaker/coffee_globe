<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\FQ;
use App\Models\PageImage;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FQsController extends Controller
{
     public function index()
    {

        return Inertia::render('Website/FQs', [
            'contact_us_infos' => ContactUs::where('is_active', 1)->get(),
            'social_media_infos' => SocialMedia::where('is_active', 1)->get(),
            'page' => PageImage::with('media')->where('id', 4)->first(),
            'fqs' => FQ::where('is_active', 1)->get(),

        ]);
    }
}
