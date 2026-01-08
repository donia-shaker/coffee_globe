<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\CreateRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request,$role)
    {
        $query = User::query()->whereHas('roles', function ($query) use ($role) {
            $query->whereIn('name', ['admin','super_admin']);
        });

        if(Auth::user()->id != 1) {
            $query->where('id','!=',Auth::user()->id);
        }
    
        // 🔍 البحث في جميع الأعمدة
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $columns = Schema::getColumnListing((new User)->getTable());
    
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
        if (Schema::hasColumn((new User)->getTable(), $sort)) {
            $query->orderBy($sort, $direction);
        }
    
        // 🛠 تغيير عدد العناصر في الصفحة
        $perPage = $request->get('perPage', 10); // افتراضي: 10 عناصر في الصفحة
        if (!in_array($perPage, [10, 25, 50, 100])) { // التحقق من أن القيمة المدخلة صحيحة
            $perPage = 10; // إذا كانت القيمة غير صحيحة، استخدم القيمة الافتراضية
        }
    
        // 🔢 إرجاع النتائج مع التصفية والبحث
        return Inertia::render('Admin/Users/Index', [
            'users' => $query->paginate($perPage), // استخدم $perPage هنا
            'filters' => $request->only(['search', 'role']),
            'role' => $role,
        ]);
    }
    

    public function create($role)
    {
        return Inertia::render('Admin/Users/Create', [
            'role' => $role,
        ]);
    }

    public function store(CreateRequest $request)
    {
        $request->validated();
        try {
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'is_active' => $request->is_active,
                'password' => Hash::make($request->password),
            ]);
            $user->assignRole($request->role);

            return to_route('users.index', ['role' => $request->role])->with('success',  'تمت الاضافة بنجاح');
        } catch (\Exception $e) {
            return to_route('users.index', ['role' => $request->role])->with('error', 'Something went wrong :(');
        }
    }
    public function edit($id)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => User::find($id),
        ]);
    }

    public function update(UpdateRequest $request, $id)
    {
        $request->validated();

        $user = User::find($id);
        $data = $request->only(['name', 'email', 'phone', 'is_active']);

        $user->update($data);

        return redirect()->route('users.index', ['role' => $user->roles()->first()->name])->with('success', 'تم تحديث المستخدم بنجاح!');
    }

    public function active($id){
        try {

            $user = User::find($id);
            if ($user->is_active)
                $user->is_active = 0;
            else
                $user->is_active = 1;
            if ($user->save())
                return redirect()->back()->with([
                    'success'   => 'تم تغيير حالة المستخدم بنجاح',
                ]);
        } catch (\Exception) {
            return redirect()->back()->with([
                'error'   => 'حدث خطأ ما',
            ]);
        }
    }
}
