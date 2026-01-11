<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\Blog;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ClientReview;
use App\Models\ContactUs;
use App\Models\Feature;
use App\Models\FQ;
use App\Models\MainCenter;
use App\Models\Product;
use App\Models\Section;
use App\Models\Service;
use App\Models\Slider;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function index()
    {

        return Inertia::render('Website/Index', [
            'sliders' => Slider::with('media')->where('is_active', 1)->get(),
            'services' => Service::with('media')->where('is_active', 1)->get(),
            'client_reviews' => ClientReview::with('media')->get(),
            'fqs' => FQ::where('is_active', 1)->limit(3)->get(),
            'blogs' => Blog::where('is_active', 1)->limit(5)->get(),
            'contact_us_infos' => ContactUs::where('is_active', 1)->get(),
            'social_media_infos' => SocialMedia::where('is_active', 1)->get(),

        ]);
    }

}
