<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pages\PageRequest;
use App\Models\PageImage;
use DoniaShaker\MediaLibrary\Models\Media;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query =PageImage::query()->with('media');

        // 🔍 البحث في جميع الأعمدة
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $columns = Schema::getColumnListing((new PageImage)->getTable());

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
        if (Schema::hasColumn((new PageImage)->getTable(), $sort)) {
            $query->orderBy($sort, $direction);
        }

        // 🛠 تغيير عدد العناصر في الصفحة
        $perPage = $request->get('perPage', 10); // افتراضي: 10 عناصر في الصفحة
        if (!in_array($perPage, [10, 25, 50, 100])) { // التحقق من أن القيمة المدخلة صحيحة
            $perPage = 10; // إذا كانت القيمة غير صحيحة، استخدم القيمة الافتراضية
        }

        // 🔢 إرجاع النتائج مع التصفية والبحث
        return Inertia::render('Admin/Pages/Page/Index', [
            'pages' => $query->paginate($perPage), // استخدم $perPage هنا
            'filters' => $request->only(['search', 'role']),
            'langs' => getLangs(),
        ]);
    }

    public function edit($id)
    {
        return Inertia::render('Admin/Pages/Page/Edit', [
            'page' =>PageImage::with('media')->find($id),
            'langs' => getLangs(),
        ]);
    }

    public function update(PageRequest $request, $id)
    {
        $request->validated();
        try {

            $about =PageImage::find($id);

            $name = [];

            $langs = getLangs();

            foreach ($langs as $locale) {
                $name[$locale->code] = $request->input("name_{$locale->code}");
            }

            $about->update([
                'name' => $name,
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                if (isset($about->media)) {
                    Media::where('model', 'page')->where('id', $about->media->id)->delete();
                }
                // upload image
                $this->media_controller->saveImage('page', $about->id, $request->file('image'));
            }


            return redirect()->route('pages.index')->with('success', 'تم تحديث البيانات بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
