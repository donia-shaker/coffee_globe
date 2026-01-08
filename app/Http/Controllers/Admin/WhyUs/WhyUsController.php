<?php

namespace App\Http\Controllers\Admin\WhyUs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WhyUs\WhyUsRequest;
use App\Models\WhyUs;
use DoniaShaker\MediaLibrary\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class WhyUsController extends Controller
{
     public function index(Request $request)
    {
        $query = WhyUs::query()->with('media');

        // 🔍 البحث في جميع الأعمدة
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $columns = Schema::getColumnListing((new WhyUs)->getTable());

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
        if (Schema::hasColumn((new WhyUs)->getTable(), $sort)) {
            $query->orderBy($sort, $direction);
        }

        // 🛠 تغيير عدد العناصر في الصفحة
        $perPage = $request->get('perPage', 10); // افتراضي: 10 عناصر في الصفحة
        if (!in_array($perPage, [10, 25, 50, 100])) { // التحقق من أن القيمة المدخلة صحيحة
            $perPage = 10; // إذا كانت القيمة غير صحيحة، استخدم القيمة الافتراضية
        }

        // 🔢 إرجاع النتائج مع التصفية والبحث
        return Inertia::render('Admin/WhyUss/Index', [
            'why_uss' => $query->paginate($perPage), // استخدم $perPage هنا
            'filters' => $request->only(['search', 'role']),
            'langs' => getLangs(),
        ]);
    }


    public function create()
    {
        return Inertia::render('Admin/WhyUss/Create', [
            'langs' => getLangs(),
        ]);
    }

    public function store(WhyUsRequest $request)
    {
        $request->validated();
        try {
            $name = [];
            $text = [];

            $langs = getLangs();
            foreach ($langs as $locale) {
                $name[$locale->code] = $request->input("name_{$locale->code}");
                $text[$locale->code] = $request->input("text_{$locale->code}");
            }

            $why_us = WhyUs::create([
                'name' => $name,
                'text' => $text,
                'is_active' => $request->is_active,
            ]);


            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $this->media_controller->saveImage('whyus', $why_us->id, $request->file('image'));
            }
            return to_route('why_uss.index')->with('success', 'تمت الاضافة بنجاح');
        } catch (\Exception $e) {
            return to_route('why_uss.index')->with('error', 'Something went wrong :(');
        }
    }
    public function edit($id)
    {
        return Inertia::render('Admin/WhyUss/Edit', [
            'why_us' => WhyUs::find($id),
            'langs' => getLangs(),
        ]);
    }

    public function update(WhyUsRequest $request, $id)
    {
        $request->validated();

        $why_us = WhyUs::find($id);

        $name = [];
        $text = [];

        $langs = getLangs();
        foreach ($langs as $locale) {
            $name[$locale->code] = $request->input("name_{$locale->code}");
            $text[$locale->code] = $request->input("text_{$locale->code}");
        }


        $why_us->update([
            'name' => $name,
            'text' => $text,
            'is_active' => $request->is_active,
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if (isset($why_us->media)) {
                Media::where('model', 'why_us')->where('id', $why_us->media->id)->delete();
            }
            // upload image
            $this->media_controller->saveImage('why_us', $why_us->id, $request->file('image'));
        }

        return redirect()->route('why_uss.index')->with('success', 'تم تحديث الخدمة بنجاح!');
    }

    public function active($id)
    {
        try {

            $why_us = WhyUs::find($id);
            if ($why_us->is_active)
                $why_us->is_active = 0;
            else
                $why_us->is_active = 1;
            if ($why_us->save())
                return redirect()->back()->with([
                    'success'   => 'تم تغيير حالة الصنف بنجاح',
                ]);
        } catch (\Exception) {
            return redirect()->back()->with([
                'error'   => 'حدث خطأ ما',
            ]);
        }
    }
}
