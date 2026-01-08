<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pages\AboutPageRequest;
use App\Models\About;
use App\Models\AboutPage;
use DoniaShaker\MediaLibrary\Models\Media;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class AboutController extends Controller
{
    public function index(Request $request)
    {
        $query =AboutPage::query()->with('media');

        // 🔍 البحث في جميع الأعمدة
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $columns = Schema::getColumnListing((new AboutPage)->getTable());

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
        if (Schema::hasColumn((new AboutPage)->getTable(), $sort)) {
            $query->orderBy($sort, $direction);
        }

        // 🛠 تغيير عدد العناصر في الصفحة
        $perPage = $request->get('perPage', 10); // افتراضي: 10 عناصر في الصفحة
        if (!in_array($perPage, [10, 25, 50, 100])) { // التحقق من أن القيمة المدخلة صحيحة
            $perPage = 10; // إذا كانت القيمة غير صحيحة، استخدم القيمة الافتراضية
        }

        // 🔢 إرجاع النتائج مع التصفية والبحث
        return Inertia::render('Admin/Pages/About/Index', [
            'about_page_infos' => $query->paginate($perPage), // استخدم $perPage هنا
            'filters' => $request->only(['search', 'role']),
            'langs' => getLangs(),
        ]);
    }

    public function edit($id)
    {
        return Inertia::render('Admin/Pages/About/Edit', [
            'about_page_info' =>AboutPage::with('media')->find($id),
            'langs' => getLangs(),
        ]);
    }

    public function update(AboutPageRequest $request, $id)
    {
        $request->validated();
        try {

            $about =AboutPage::find($id);

            $text = [];

            $langs = getLangs();

            foreach ($langs as $locale) {
                $text[$locale->code] = $request->input("text_{$locale->code}");
            }

            $about->update([
                'text' => $text,
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                if (isset($about->media)) {
                    Media::where('model', 'about_page')->where('id', $about->media->id)->delete();
                }
                // upload image
                $this->media_controller->saveImage('about_page', $about->id, $request->file('image'));
            }


            return redirect()->route('about_page_infos.index')->with('success', 'تم تحديث البيانات بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

}
