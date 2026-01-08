<?php

namespace App\Http\Controllers\Admin\ServiceCompany;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceCompany\ServiceCompanyRequest;
use App\Models\ServiceCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class ServiceCompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceCompany::query();

        // 🔍 البحث في جميع الأعمدة
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $columns = Schema::getColumnListing((new ServiceCompany)->getTable());

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
        if (Schema::hasColumn((new ServiceCompany)->getTable(), $sort)) {
            $query->orderBy($sort, $direction);
        }

        // 🛠 تغيير عدد العناصر في الصفحة
        $perPage = $request->get('perPage', 10); // افتراضي: 10 عناصر في الصفحة
        if (!in_array($perPage, [10, 25, 50, 100])) { // التحقق من أن القيمة المدخلة صحيحة
            $perPage = 10; // إذا كانت القيمة غير صحيحة، استخدم القيمة الافتراضية
        }

        // 🔢 إرجاع النتائج مع التصفية والبحث
        return Inertia::render('Admin/ServiceCompanies/Index', [
            'service_companies' => $query->paginate($perPage), // استخدم $perPage هنا
            'filters' => $request->only(['search', 'role']),
            'langs' => getLangs(),
        ]);
    }


    public function create()
    {
        return Inertia::render('Admin/ServiceCompanies/Create', [
            'langs' => getLangs(),
        ]);
    }

    public function store(ServiceCompanyRequest $request)
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

            $service_company = ServiceCompany::create([
                'name' => $name,
                'text' => $text,
                'is_active' => $request->is_active,
            ]);

            return to_route('service_companies.index')->with('success', 'تمت الاضافة بنجاح');
        } catch (\Exception $e) {
            return to_route('service_companies.index')->with('error', 'Something went wrong :(');
        }
    }
    public function edit($id)
    {
        return Inertia::render('Admin/ServiceCompanies/Edit', [
            'service_company' => ServiceCompany::find($id),
            'langs' => getLangs(),
        ]);
    }

    public function update(ServiceCompanyRequest $request, $id)
    {
        $request->validated();

        $service_company = ServiceCompany::find($id);

        $name = [];
        $text = [];

        $langs = getLangs();
        foreach ($langs as $locale) {
            $name[$locale->code] = $request->input("name_{$locale->code}");
            $text[$locale->code] = $request->input("text_{$locale->code}");
        }


        $service_company->update([
            'name' => $name,
            'text' => $text,
            'is_active' => $request->is_active,
        ]);


        return redirect()->route('service_companies.index')->with('success', 'تم تحديث الخدمة بنجاح!');
    }

    public function active($id)
    {
        try {

            $service_company = ServiceCompany::find($id);
            if ($service_company->is_active)
                $service_company->is_active = 0;
            else
                $service_company->is_active = 1;
            if ($service_company->save())
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
