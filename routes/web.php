<?php

use App\Http\Controllers\Admin\Blog\BlogController;
use App\Http\Controllers\Admin\ClientReview\ClientReviewController;
use App\Http\Controllers\Admin\Expert\ExpertController;
use App\Http\Controllers\Admin\Feature\FeatureController;
use App\Http\Controllers\Admin\FQ\FQController;
use App\Http\Controllers\Admin\Pages\AboutController as PagesAboutController;
use App\Http\Controllers\Admin\Pages\BranchController;
use App\Http\Controllers\Admin\Pages\ContactUsController;
use App\Http\Controllers\Admin\Pages\MainCenterController;
use App\Http\Controllers\Admin\Pages\SliderController;
use App\Http\Controllers\Admin\Pages\SocialMediaController;
use App\Http\Controllers\Admin\Product\CategoryController;
use App\Http\Controllers\Admin\Product\ProductController as ProductProductController;
use App\Http\Controllers\Admin\Service\ServiceController;
use App\Http\Controllers\Admin\ServiceCompany\ServiceCompanyController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Value\ValueController;
use App\Http\Controllers\Admin\WhyUs\WhyUsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Website\AboutController;
use App\Http\Controllers\Website\BlogsController;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\FQsController;
use App\Http\Controllers\Website\IndexController;
use App\Http\Controllers\Website\SolutionController;
use App\Models\Feature;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [IndexController::class, 'index']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/solution', [SolutionController::class, 'index']);
Route::get('/blogs', [BlogsController::class, 'index']);
Route::get('/blog/{id}', [BlogsController::class, 'detail']);
Route::get('/fqs', [FQsController::class, 'index']);
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');


Route::get('/dashboard', function () {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:super_admin|admin'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/users/{role}', 'index')->name('users.index');
        Route::get('/user/create/{role}', 'create')->name('user.create');
        Route::post('/user/store', 'store')->name('user.store');
        Route::get('/user/edit/{id}', 'edit')->name('user.edit');
        Route::put('/user/update/{id}', 'update')->name('user.update');
        Route::post('/user/active/{id}', 'active')->name('user.active');
    });

    Route::controller(ServiceController::class)->group(function () {
        Route::get('/services', 'index')->name('services.index');
        Route::get('/services/create', 'create')->name('services.create');
        Route::post('/services/store', 'store')->name('services.store');
        Route::get('/services/edit/{id}', 'edit')->name('services.edit');
        Route::put('/services/update/{id}', 'update')->name('services.update');
        Route::post('/services/active/{id}', 'active')->name('services.active');
    });
    Route::controller(ClientReviewController::class)->group(function () {
        Route::get('/client_reviews', 'index')->name('client_reviews.index');
        Route::get('/client_reviews/create', 'create')->name('client_reviews.create');
        Route::post('/client_reviews/store', 'store')->name('client_reviews.store');
        Route::get('/client_reviews/edit/{id}', 'edit')->name('client_reviews.edit');
        Route::put('/client_reviews/update/{id}', 'update')->name('client_reviews.update');
        Route::post('/client_reviews/active/{id}', 'active')->name('client_reviews.active');
    });
    
    Route::controller(FQController::class)->group(function () {
        Route::get('/fqs', 'index')->name('fqs.index');
        Route::get('/fqs/create', 'create')->name('fqs.create');
        Route::post('/fqs/store', 'store')->name('fqs.store');
        Route::get('/fqs/edit/{id}', 'edit')->name('fqs.edit');
        Route::put('/fqs/update/{id}', 'update')->name('fqs.update');
        Route::post('/fqs/active/{id}', 'active')->name('fqs.active');
    });
    
    Route::controller(BlogController::class)->group(function () {
        Route::get('/admin_blogs', 'index')->name('admin_blogs.index');
        Route::get('/blogs/create', 'create')->name('blogs.create');
        Route::post('/blogs/store', 'store')->name('blogs.store');
        Route::get('/blogs/edit/{id}', 'edit')->name('blogs.edit');
        Route::put('/blogs/update/{id}', 'update')->name('blogs.update');
        Route::post('/blogs/active/{id}', 'active')->name('blogs.active');
    });

    Route::controller(ValueController::class)->group(function () {
        Route::get('/values', 'index')->name('values.index');
        Route::get('/values/create', 'create')->name('values.create');
        Route::post('/values/store', 'store')->name('values.store');
        Route::get('/values/edit/{id}', 'edit')->name('values.edit');
        Route::put('/values/update/{id}', 'update')->name('values.update');
        Route::post('/values/active/{id}', 'active')->name('values.active');
    });

    Route::controller(ExpertController::class)->group(function () {
        Route::get('/experts', 'index')->name('experts.index');
        Route::get('/experts/create', 'create')->name('experts.create');
        Route::post('/experts/store', 'store')->name('experts.store');
        Route::get('/experts/edit/{id}', 'edit')->name('experts.edit');
        Route::put('/experts/update/{id}', 'update')->name('experts.update');
        Route::post('/experts/active/{id}', 'active')->name('experts.active');
    });

    Route::controller(WhyUsController::class)->group(function () {
        Route::get('/why_uss', 'index')->name('why_uss.index');
        Route::get('/why_uss/create', 'create')->name('why_uss.create');
        Route::post('/why_uss/store', 'store')->name('why_uss.store');
        Route::get('/why_uss/edit/{id}', 'edit')->name('why_uss.edit');
        Route::put('/why_uss/update/{id}', 'update')->name('why_uss.update');
        Route::post('/why_uss/active/{id}', 'active')->name('why_uss.active');
    });

    Route::controller(ServiceCompanyController::class)->group(function () {
        Route::get('/service_companies', 'index')->name('service_companies.index');
        Route::get('/service_companies/create', 'create')->name('service_companies.create');
        Route::post('/service_companies/store', 'store')->name('service_companies.store');
        Route::get('/service_companies/edit/{id}', 'edit')->name('service_companies.edit');
        Route::put('/service_companies/update/{id}', 'update')->name('service_companies.update');
        Route::post('/service_companies/active/{id}', 'active')->name('service_companies.active');
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Mange Sliders
    Route::controller(SliderController::class)->group(function () {
        Route::get('/sliders', 'index')->name('sliders.index');
        Route::get('/sliders/create', 'create')->name('sliders.create');
        Route::post('/sliders/store', 'store')->name('sliders.store');
        Route::get('/sliders/edit/{id}', 'edit')->name('sliders.edit');
        Route::put('/sliders/update/{id}', 'update')->name('sliders.update');
        Route::post('/sliders/active/{id}', 'active')->name('sliders.active');
    });

    // Admin Mange contact_us_infos
    Route::controller(ContactUsController::class)->group(function () {
        Route::get('/contact_us_infos', 'index')->name('contact_us_infos.index');
        Route::get('/contact_us_infos/create', 'create')->name('contact_us_infos.create');
        Route::post('/contact_us_infos/store', 'store')->name('contact_us_infos.store');
        Route::get('/contact_us_infos/edit/{id}', 'edit')->name('contact_us_infos.edit');
        Route::put('/contact_us_infos/update/{id}', 'update')->name('contact_us_infos.update');
        Route::post('/contact_us_infos/active/{id}', 'active')->name('contact_us_infos.active');
    });

    // Admin Mange social_media_infos
    Route::controller(SocialMediaController::class)->group(function () {
        Route::get('/social_media_infos', 'index')->name('social_media_infos.index');
        Route::get('/social_media_infos/create', 'create')->name('social_media_infos.create');
        Route::post('/social_media_infos/store', 'store')->name('social_media_infos.store');
        Route::get('/social_media_infos/edit/{id}', 'edit')->name('social_media_infos.edit');
        Route::put('/social_media_infos/update/{id}', 'update')->name('social_media_infos.update');
        Route::post('/social_media_infos/active/{id}', 'active')->name('social_media_infos.active');
    });

     // Admin Mange About Page
    Route::controller(PagesAboutController::class)->group(function () {
        Route::get('/about_page_infos', 'index')->name('about_page_infos.index');
        Route::get('/about_page_infos/edit/{id}', 'edit')->name('about_page_infos.edit');
        Route::put('/about_page_infos/update/{id}', 'update')->name('about_page_infos.update');
    });

});

require __DIR__ . '/auth.php';
