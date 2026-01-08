<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blog\BlogRequest;
use App\Models\Blog;
use DoniaShaker\MediaLibrary\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::query()->with('media');

        // 🔍 البحث في جميع الأعمدة
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $columns = Schema::getColumnListing((new Blog)->getTable());

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
        if (Schema::hasColumn((new Blog)->getTable(), $sort)) {
            $query->orderBy($sort, $direction);
        }

        // 🛠 تغيير عدد العناصر في الصفحة
        $perPage = $request->get('perPage', 10); // افتراضي: 10 عناصر في الصفحة
        if (!in_array($perPage, [10, 25, 50, 100])) { // التحقق من أن القيمة المدخلة صحيحة
            $perPage = 10; // إذا كانت القيمة غير صحيحة، استخدم القيمة الافتراضية
        }

        // 🔢 إرجاع النتائج مع التصفية والبحث
        return Inertia::render('Admin/Blogs/Index', [
            'blogs' => $query->paginate($perPage), // استخدم $perPage هنا
            'filters' => $request->only(['search', 'role']),
            'langs' => getLangs(),
        ]);
    }


    public function create()
    {
        return Inertia::render('Admin/Blogs/Create', [
            'langs' => getLangs(),
        ]);
    }

    public function store(BlogRequest $request)
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

            $blog = Blog::create([
                'name' => $name,
                'text' => $text,
                'is_active' => $request->is_active,
            ]);


            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $this->media_controller->saveImage('blog', $blog->id, $request->file('image'));
            }
            return to_route('blogs.index')->with('success', 'تمت الاضافة بنجاح');
        } catch (\Exception $e) {
            return to_route('blogs.index')->with('error', 'Something went wrong :(');
        }
    }
    public function edit($id)
    {
        return Inertia::render('Admin/Blogs/Edit', [
            'blog' => Blog::find($id),
            'langs' => getLangs(),
        ]);
    }

    public function update(BlogRequest $request, $id)
    {
        $request->validated();

        $blog = Blog::find($id);

        $name = [];
        $text = [];

        $langs = getLangs();
        foreach ($langs as $locale) {
            $name[$locale->code] = $request->input("name_{$locale->code}");
            $text[$locale->code] = $request->input("text_{$locale->code}");
        }


        $blog->update([
            'name' => $name,
            'text' => $text,
            'is_active' => $request->is_active,
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if (isset($blog->media)) {
                Media::where('model', 'blog')->where('id', $blog->media->id)->delete();
            }
            // upload image
            $this->media_controller->saveImage('blog', $blog->id, $request->file('image'));
        }

        return redirect()->route('blogs.index')->with('success', 'تم تحديث الخدمة بنجاح!');
    }

    public function active($id)
    {
        try {

            $blog = Blog::find($id);
            if ($blog->is_active)
                $blog->is_active = 0;
            else
                $blog->is_active = 1;
            if ($blog->save())
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
