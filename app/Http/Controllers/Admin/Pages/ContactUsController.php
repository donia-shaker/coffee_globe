<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pages\ContactUsRequest;
use App\Models\ContactUs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class ContactUsController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactUs::query();

        // 🔍 البحث في جميع الأعمدة
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $columns = Schema::getColumnListing((new ContactUs)->getTable());

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
        if (Schema::hasColumn((new ContactUs)->getTable(), $sort)) {
            $query->orderBy($sort, $direction);
        }

        // 🛠 تغيير عدد العناصر في الصفحة
        $perPage = $request->get('perPage', 10); // افتراضي: 10 عناصر في الصفحة
        if (!in_array($perPage, [10, 25, 50, 100])) { // التحقق من أن القيمة المدخلة صحيحة
            $perPage = 10; // إذا كانت القيمة غير صحيحة، استخدم القيمة الافتراضية
        }

        // 🔢 إرجاع النتائج مع التصفية والبحث
        return Inertia::render('Admin/Pages/ContactUs/Index', [
            'contact_us_infos' => $query->paginate($perPage), // استخدم $perPage هنا
            'filters' => $request->only(['search', 'role']),
            'langs' => getLangs(),
        ]);
    }


    public function create()
    {
        return Inertia::render('Admin/Pages/ContactUs/Create', [
            'langs' => getLangs(),
        ]);
    }

    public function store(ContactUsRequest $request)
    {
        $request->validated();
        try {

            $name = [];
            $value = [];

            $langs = getLangs();

            foreach ($langs as $locale) {
                $name[$locale->code] = $request->input("name_{$locale->code}");
                $value[$locale->code] = $request->input("value_{$locale->code}");
            }

            ContactUs::create([
                'name'      => $name,
                'value'      => $value,
                'icon'      => $request->icon,
                'is_active' =>  $request->is_active ?? 0
            ]);
            return to_route('contact_us_infos.index')->with('success',  'تمت الاضافة بنجاح');
        } catch (\Exception $e) {
            return to_route('contact_us_infos.index')->with('error', 'Something went wrong :(');
        }
    }
    public function edit($id)
    {
        return Inertia::render('Admin/Pages/ContactUs/Edit', [
            'contact_us_info' => ContactUs::find($id),
            'langs' => getLangs(),
        ]);
    }

    public function update(ContactUsRequest $request, $id)
    {
        $request->validated();
        try {

            $contact_us = ContactUs::find($id);

            $name = [];
            $value = [];

            $langs = getLangs();

            foreach ($langs as $locale) {
                $name[$locale->code] = $request->input("name_{$locale->code}");
                $value[$locale->code] = $request->input("value_{$locale->code}");
            }

            $contact_us->update([
                'name'      => $name,
                'value'      => $value,
                'icon'      => $request->icon,
                'is_active' => $request->is_active,
            ]);

            return redirect()->route('contact_us_infos.index')->with('success', 'تم تحديث البيانات بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function active($id)
    {
        try {

            $contact_us = ContactUs::find($id);
            if ($contact_us->is_active)
                $contact_us->is_active = 0;
            else
                $contact_us->is_active = 1;
            if ($contact_us->save())
                return redirect()->back()->with([
                    'success'   => 'تم تغيير الحالة  بنجاح',
                ]);
        } catch (\Exception) {
            return redirect()->back()->with([
                'error'   => 'حدث خطأ ما',
            ]);
        }
    }
}
