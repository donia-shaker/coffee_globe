<?php

namespace App\Http\Controllers\Admin\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Service\ServiceRequest;
use App\Models\Service;
use DoniaShaker\MediaLibrary\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query()->with('media');

        // 🔍 البحث في جميع الأعمدة
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $columns = Schema::getColumnListing((new Service)->getTable());

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
        if (Schema::hasColumn((new Service)->getTable(), $sort)) {
            $query->orderBy($sort, $direction);
        }

        // 🛠 تغيير عدد العناصر في الصفحة
        $perPage = $request->get('perPage', 10); // افتراضي: 10 عناصر في الصفحة
        if (!in_array($perPage, [10, 25, 50, 100])) { // التحقق من أن القيمة المدخلة صحيحة
            $perPage = 10; // إذا كانت القيمة غير صحيحة، استخدم القيمة الافتراضية
        }

        // 🔢 إرجاع النتائج مع التصفية والبحث
        return Inertia::render('Admin/Services/Index', [
            'services' => $query->paginate($perPage), // استخدم $perPage هنا
            'filters' => $request->only(['search', 'role']),
            'langs' => getLangs(),
        ]);
    }


    public function create()
    {
        return Inertia::render('Admin/Services/Create', [
            'langs' => getLangs(),
        ]);
    }

    public function store(ServiceRequest $request)
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

            $service = Service::create([
                'name' => $name,
                'text' => $text,
                'is_active' => $request->is_active,
            ]);


            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $this->media_controller->saveImage('service', $service->id, $request->file('image'));
            }
            return to_route('services.index')->with('success', 'تمت الاضافة بنجاح');
        } catch (\Exception $e) {
            return to_route('services.index')->with('error', 'Something went wrong :(');
        }
    }
    public function edit($id)
    {
        return Inertia::render('Admin/Services/Edit', [
            'service' => Service::find($id),
            'langs' => getLangs(),
        ]);
    }

    public function update(ServiceRequest $request, $id)
    {
        $request->validated();

        $service = Service::find($id);

        $name = [];
        $text = [];

        $langs = getLangs();
        foreach ($langs as $locale) {
            $name[$locale->code] = $request->input("name_{$locale->code}");
            $text[$locale->code] = $request->input("text_{$locale->code}");
        }


        $service->update([
            'name' => $name,
            'text' => $text,
            'is_active' => $request->is_active,
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if (isset($service->media)) {
                Media::where('model', 'service')->where('id', $service->media->id)->delete();
            }
            // upload image
            $this->media_controller->saveImage('service', $service->id, $request->file('image'));
        }

        return redirect()->route('services.index')->with('success', 'تم تحديث الخدمة بنجاح!');
    }

    public function active($id)
    {
        try {

            $service = Service::find($id);
            if ($service->is_active)
                $service->is_active = 0;
            else
                $service->is_active = 1;
            if ($service->save())
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
