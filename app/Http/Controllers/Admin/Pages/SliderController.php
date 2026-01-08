<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pages\SliderRequest;
use App\Models\Slider;
use DoniaShaker\MediaLibrary\Models\Media;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $query = Slider::query()->with('media');

        // 🔍 البحث في جميع الأعمدة
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $columns = Schema::getColumnListing((new Slider)->getTable());

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
        if (Schema::hasColumn((new Slider)->getTable(), $sort)) {
            $query->orderBy($sort, $direction);
        }

        // 🛠 تغيير عدد العناصر في الصفحة
        $perPage = $request->get('perPage', 10); // افتراضي: 10 عناصر في الصفحة
        if (!in_array($perPage, [10, 25, 50, 100])) { // التحقق من أن القيمة المدخلة صحيحة
            $perPage = 10; // إذا كانت القيمة غير صحيحة، استخدم القيمة الافتراضية
        }

        // 🔢 إرجاع النتائج مع التصفية والبحث
        return Inertia::render('Admin/Pages/Slider/Index', [
            'sliders' => $query->paginate($perPage), // استخدم $perPage هنا
            'filters' => $request->only(['search', 'role']),
            'langs' => getLangs(),
        ]);
    }


    public function create()
    {
        return Inertia::render('Admin/Pages/Slider/Create', [
            'langs' => getLangs(),
        ]);
    }

    public function store(SliderRequest $request)
    {
        $request->validated();
        try {

            $title = [];
            $text_one = [];
            $text_two = [];
            
            $langs = getLangs();

            foreach ($langs as $locale) {
                $title[$locale->code] = $request->input("title_{$locale->code}");
                $text_one[$locale->code] = $request->input("text_one_{$locale->code}");
                $text_two[$locale->code] = $request->input("text_two_{$locale->code}");
            }

            $slider = Slider::create([
                'title' => $title,
                'text_one' => $text_one,
                'text_two' => $text_two,
                'is_active' => $request->is_active,
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $this->media_controller->saveImage('slider', $slider->id, $request->file('image'));
            }
            return to_route('sliders.index')->with('success',  'تمت الاضافة بنجاح');
        } catch (\Exception $e) {
            return to_route('sliders.index')->with('error', 'Something went wrong :(');
        }
    }
    public function edit($id)
    {
        return Inertia::render('Admin/Pages/Slider/Edit', [
            'slider' => Slider::find($id),
            'langs' => getLangs(),
        ]);
    }

    public function update(SliderRequest $request, $id)
    {
        $request->validated();
        try {

            $slider = Slider::find($id);

            $title = [];
            $text_one = [];
            $text_two = [];

            $langs = getLangs();

            foreach ($langs as $locale) {
                $title[$locale->code] = $request->input("title_{$locale->code}");
                $text_one[$locale->code] = $request->input("text_one_{$locale->code}");
                $text_two[$locale->code] = $request->input("text_two_{$locale->code}");
            }

            $slider->update([
                'title' => $title,
                'text_one' => $text_one,
                'text_two' => $text_two,
                'is_active' => $request->is_active,
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                if (isset($slider->media)) {
                    Media::where('model', 'slider')->where('id', $slider->media->id)->delete();
                }
                // upload image
                $this->media_controller->saveImage('slider', $slider->id, $request->file('image'));
            }


            return redirect()->route('sliders.index')->with('success', 'تم تحديث السلايد بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function active($id)
    {
        try {

            $slider = Slider::find($id);
            if ($slider->is_active)
                $slider->is_active = 0;
            else
                $slider->is_active = 1;
            if ($slider->save())
                return redirect()->back()->with([
                    'success'   => 'تم تغيير حالة السلايد بنجاح',
                ]);
        } catch (\Exception) {
            return redirect()->back()->with([
                'error'   => 'حدث خطأ ما',
            ]);
        }
    }
}
