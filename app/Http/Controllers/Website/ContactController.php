<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\ContactUsMessageRequest;
use App\Mail\ContactUsEmail;
use App\Models\Brand;
use App\Models\ContactUs;
use App\Models\Section;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Website/Contact', [
            'contact_us_infos' => ContactUs::where('is_active', 1)->get(),
            'social_media_infos' => SocialMedia::where('is_active', 1)->get(),

        ]);
    }

    public function send(ContactUsMessageRequest $request)
    {
        $request->validated();

        try {
            $email_data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'messages' => $request->message,
            ];

            Mail::to(env('MAIL_TO_ADDRESS'), 'Admin')
                ->send(new ContactUsEmail($email_data));

            return back()->with('success', 'تم إرسال الرسالة بنجاح!');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إرسال الرسالة!');
        }
    }
}
