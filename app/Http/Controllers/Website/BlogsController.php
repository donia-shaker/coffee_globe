<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ContactUs;
use App\Models\PageImage;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogsController extends Controller
{
    public function index()
    {

        return Inertia::render('Website/Blogs', [
            'contact_us_infos' => ContactUs::where('is_active', 1)->get(),
            'page' => PageImage::with('media')->where('id', 3)->first(),
            'social_media_infos' => SocialMedia::where('is_active', 1)->get(),
            'blogs' => Blog::with('media')->where('is_active', 1)->get(),

        ]);
    }

    public function detail($id)
    {
        return Inertia::render('Website/Blog', [

            'contact_us_infos' => ContactUs::where('is_active', 1)->get(),
            'social_media_infos' => SocialMedia::where('is_active', 1)->get(),
            'blog' => Blog::with('media')->where('is_active', 1)->find($id),

        ]);
    }
}
