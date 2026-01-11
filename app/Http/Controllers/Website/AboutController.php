<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\ContactUs;
use App\Models\Expert;
use App\Models\SocialMedia;
use App\Models\Value;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutController extends Controller
{
    public function index()
    {

        return Inertia::render('Website/About', [
            'values' => Value::where('is_active', 1)->get(),
            'experts' => Expert::where('is_active', 1)->get(),
            'contact_us_infos' => ContactUs::where('is_active', 1)->get(),
            'social_media_infos' => SocialMedia::where('is_active', 1)->get(),
            'about_page_data' => AboutPage::with('media')->get(),

        ]);
    }
}
