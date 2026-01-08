<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pages\SocialMediaRequest;
use App\Models\Social_media_info;
use App\Models\SocialMedia;
use DoniaShaker\MediaLibrary\Models\Media;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class SocialMediaController extends Controller
{
    public function index(Request $request)
    {
        $query = SocialMedia::query();

        // 🔍 البحث في جميع الأعمدة
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $columns = Schema::getColumnListing((new SocialMedia)->getTable());

            $query->where(function ($q) use ($columns, $searchTerm) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'LIKE', "%{$searchTerm}%");
                }
            });
        }

        // 🔽 التصفية حسب الدور
        // if ($request->filled('role')) {
        //     $query->where('role', $request->role);
        // }

        $sort = $request->get('sort', 'id'); // افتراضي: الترتيب حسب ID
        $direction = $request->get('direction', 'desc'); // افتراضي: تنازلي
        if (Schema::hasColumn((new SocialMedia)->getTable(), $sort)) {
            $query->orderBy($sort, $direction);
        }

        // 🛠 تغيير عدد العناصر في الصفحة
        $perPage = $request->get('perPage', 10); // افتراضي: 10 عناصر في الصفحة
        if (!in_array($perPage, [10, 25, 50, 100])) { // التحقق من أن القيمة المدخلة صحيحة
            $perPage = 10; // إذا كانت القيمة غير صحيحة، استخدم القيمة الافتراضية
        }

        // 🔢 إرجاع النتائج مع التصفية والبحث
        return Inertia::render('Admin/Pages/SocialMedia/Index', [
            'social_media_infos' => $query->paginate($perPage), // استخدم $perPage هنا
            'filters' => $request->only(['search', 'role']),
            'langs' => getLangs(),
        ]);
    }


    public function create()
    {
        return Inertia::render('Admin/Pages/SocialMedia/Create');
    }

    public function store(SocialMediaRequest $request)
    {
        $request->validated();
        try {

            $social_media_info = SocialMedia::create([
                'name'      => $request->name,
                'url'       => $request->url,
                'icon'      => $request->icon,
                'is_active' =>  $request->is_active ?? 0
            ]);
            return to_route('social_media_infos.index')->with('success',  'تمت الاضافة بنجاح');
        } catch (\Exception $e) {
            return to_route('social_media_infos.index')->with('error', 'Something went wrong :(');
        }
    }
    public function edit($id)
    {
        return Inertia::render('Admin/Pages/SocialMedia/Edit', [
            'social_media_info' => SocialMedia::find($id),
            'langs' => getLangs(),
        ]);
    }

    public function update(SocialMediaRequest $request, $id)
    {
        $request->validated();
        try {

            $social_media_info = SocialMedia::find($id);


            $social_media_info->update([
                'name'      => $request->name,
                'url'       => $request->url,
                'icon'      => $request->icon,
                'is_active' =>  $request->is_active ?? 0
            ]);

            return redirect()->route('social_media_infos.index')->with('success', 'تم تحديث البيانات بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function active($id)
    {
        try {

            $social_media_info = SocialMedia::find($id);
            if ($social_media_info->is_active)
                $social_media_info->is_active = 0;
            else
                $social_media_info->is_active = 1;
            if ($social_media_info->save())
                return redirect()->back()->with([
                    'success'   => 'تم تغيير حالة البيانات بنجاح',
                ]);
        } catch (\Exception) {
            return redirect()->back()->with([
                'error'   => 'حدث خطأ ما',
            ]);
        }
    }
}
