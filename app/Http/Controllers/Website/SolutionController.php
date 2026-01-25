<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\PageImage;
use App\Models\ServiceCompany;
use App\Models\SocialMedia;
use App\Models\WhyUs;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SolutionController extends Controller
{
    public function index()
    {

        return Inertia::render('Website/Solution', [
            'service_companies' => ServiceCompany::where('is_active', 1)->get(),
            'page' => PageImage::with('media')->where('id', 2)->first(),
            'why_uss' => WhyUs::with('media')->where('is_active', 1)->get(),
            'contact_us_infos' => ContactUs::where('is_active', 1)->get(),
            'social_media_infos' => SocialMedia::where('is_active', 1)->get(),

        ]);
    }
}
