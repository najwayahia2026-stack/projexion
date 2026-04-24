<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    /**
     * عرض قائمة المجموعات والطلاب المتاحين (الصفحة الرئيسية للمجموعات)
     */
 public function index(Request $request)
{
    $user = Auth::user();
    $specialtyId = $request->get('specialty_id');
    
    // 1. جلب المجموعات
    if ($user->hasRole('admin')) {
        $query = Group::query();
       if ($specialtyId) { $query->whereHas('supervisor', function($q) use ($specialtyId) { $q->where('specialty_id', $specialtyId); }); }
        $groups = $query->with(['supervisor', 'students', 'projects'])->latest()->get();
    } elseif ($user->hasRole('supervisor')) {
        $groups = Group::where('supervisor_id', $user->id)
            ->orWhereHas('managers', function($q) use ($user) { $q->where('user_id', $user->id); })
            ->with(['supervisor', 'students', 'projects'])->latest()->get();
    } else {
        $groups = $user->groups()->with(['supervisor', 'students', 'projects'])->latest()->get();
    }

    // 2. إعدادات المشرف والحسابات (التعديل هنا)
    $availableStudents = [];
    $myPoolStudents = [];
    $totalOccupied = 0; // متغير جديد لحساب المجموع من 15

    if ($user->hasRole('supervisor')) {
        // الطلاب المتاحون للجميع في نفس التخصص
        $availableStudents = User::role('student')
            ->where('specialty_id', $user->specialty_id)
            ->whereDoesntHave('groups')
            ->whereNull('pool_supervisor_id')
            ->get();

        // طلابي المحجوزون في القائمة
        $myPoolStudents = User::role('student')
            ->where('pool_supervisor_id', $user->id)
            ->get();

        // الطلاب الذين هم فعلياً في مجموعات يشرف عليها هذا المشرف
        $inGroupsCount = User::whereHas('groups', function($q) use ($user) {
            $q->where('supervisor_id', $user->id);
        })->count();

        // المجموع الكلي المستهلك من الـ 15
        $totalOccupied = $myPoolStudents->count() + $inGroupsCount;
    }

    $specialties = ($user->hasRole('admin')) 
        ? \App\Models\Specialty::orderBy('name')->get() 
        : collect();

    // نمرر المتغير totalOccupied للـ Blade
    return view('groups.index', compact('groups', 'availableStudents', 'myPoolStudents', 'specialties', 'totalOccupied'));
}
    /**
     * عرض أعضاء المجموعة (تتوافق مع ملف members.blade.php)
     */
    public function members(Request $request, Group $group)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            if ($user->hasRole('student') && !$group->students->contains($user->id)) {
                abort(403);
            }
            if ($user->hasRole('supervisor') && $group->supervisor_id != $user->id && !$group->isManager($user)) {
                abort(403);
            }
        }

        $search = $request->input('search');
        $roleFilter = $request->input('role');

        // جلب الطلاب مع البحث
        $studentsQuery = $group->students();
        if ($search) {
            $studentsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }
        $students = $studentsQuery->get();

        // جلب المديرين والمشرف الرئيسي
        $managers = $group->managers()->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))->get();
        $mainSupervisor = User::where('id', $group->supervisor_id)
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))->get();

        // دمج الجميع
        $allMembers = $students->concat($managers)->concat($mainSupervisor)->unique('id');

        // تطبيق الفلترة
        if ($roleFilter === 'supervisor') {
            $members = $allMembers->filter(fn($m) => $group->getUserRoleInGroup($m) === 'supervisor');
        } elseif ($roleFilter === 'student') {
            $members = $allMembers->filter(fn($m) => $group->getUserRoleInGroup($m) === 'student');
        } else {
            $members = $allMembers;
        }

        return view('groups.members', compact('group', 'members', 'search', 'roleFilter'));
    }

    /**
     * إضافة طالب من الحوض إلى المجموعة يدوياً
     */
    public function addUser(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $student = User::findOrFail($request->user_id);

        // التحقق من أن الطالب في حوض المشرف وأن المشرف يملك المجموعة
        if ($student->pool_supervisor_id != Auth::id() || $group->supervisor_id != Auth::id()) {
            abort(403, 'غير مسموح لك بإضافة هذا الطالب');
        }

        if (!$group->students->contains($student->id)) {
            $group->students()->attach($student->id);
            // إزالة من الحوض بعد الإضافة للمجموعة
            $student->update(['pool_supervisor_id' => null]);
        }

        return back()->with('success', 'تمت إضافة الطالب للمجموعة بنجاح');
    }

    /**
     * حذف عضو من المجموعة
     */
    public function removeMember(Group $group, User $user)
{
    // التأكد من الصلاحيات
    if (!Auth::user()->hasRole('admin') && Auth::id() != $group->supervisor_id) {
        abort(403);
    }

    // منع حذف المشرف من مجموعته
    if ($group->supervisor_id == $user->id) {
        return back()->with('error', 'لا يمكن حذف المشرف الأساسي');
    }

    DB::transaction(function () use ($group, $user) {
        // 1. حذف ارتباط الطالب بالمجموعة
        $group->students()->detach($user->id);
        $group->managers()->detach($user->id);

        // 2. التعديل المهم: بدلاً من null نضع id المشرف الحالي
        // هذا سيجعل الطالب يظهر في "قائمتي الخاصة" الخاصة بك
        $user->update([
            'pool_supervisor_id' => Auth::id() 
        ]);
    });

    return back()->with('success', "تم حذف العضو وإعادته لقائمتك الخاصة بنجاح");
}

    /**
     * حجز طالب إلى الحوض
     */
    public function addToPool($id)
{
    $supervisor = Auth::user();
    $student = User::findOrFail($id);

    // 1. حساب الطلاب الموجودين حالياً في "الحوض" (قائمتك الخاصة)
    $inPoolCount = User::where('pool_supervisor_id', $supervisor->id)
                       ->whereDoesntHave('groups') // لضمان عدم التكرار
                       ->count();

    // 2. حساب الطلاب الذين هم أصلاً في "مجموعاتك" (الذين أشرفت عليهم سابقاً)
    $inGroupsCount = User::whereHas('groups', function($q) use ($supervisor) {
        $q->where('supervisor_id', $supervisor->id);
    })->count();

    // 3. المجموع الكلي (الرصيد المستهلك من الـ 15)
    $totalOccupied = $inPoolCount + $inGroupsCount;

    // 4. الشرط الصارم: إذا وصل المجموع لـ 15، امنع الإضافة
    if ($totalOccupied >= 15) {
        return back()->with('error', "لا يمكنك حجز طالب جديد. لقد استهلكت حصتك (15 طالباً): لديك {$inGroupsCount} في مجموعات و {$inPoolCount} في قائمة الحجز.");
    }

    // 5. التحقق إذا كان الطالب محجوزاً لغيرك
    if ($student->pool_supervisor_id !== null && $student->pool_supervisor_id !== $supervisor->id) {
        return back()->with('error', 'هذا الطالب محجوز لمشرف آخر.');
    }

    // 6. تنفيذ عملية الحجز
    $student->update([
        'pool_supervisor_id' => $supervisor->id
    ]);

    return back()->with('success', "تم الحجز. رصيدك الحالي: " . ($totalOccupied + 1) . " من 15");
}
    /**
     * إزالة طالب من الحوض
     */
    public function removeFromPool(User $student)
    {
        if ($student->pool_supervisor_id != Auth::id()) abort(403);
        $student->update(['pool_supervisor_id' => null]);
        return back()->with('success', 'تم إزالة الطالب من قائمة الحجز');
    }
    // داخل كلاس GroupController


public function create()
    {
        // 1. جلب المشرفين الذين ينتمون لنفس تخصص المستخدم المسجل حالياً
        $supervisors = User::role('supervisor')
            ->where('specialty_id', auth()->user()->specialty_id)
            ->get();

        // 2. تمرير المتغير $supervisors إلى صفحة الـ Blade
        return view('groups.create', compact('supervisors'));
    }
    

    // ... الدوال الأخرى (مثل index و create) ...

    public function store(Request $request)
    {
        // 1. التحقق من البيانات المرسلة
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:groups',
            'supervisor_id' => 'required|exists:users,id',
            'academic_year' => 'required',
            'status' => 'required'
        ]);

        // 2. إنشاء المجموعة في قاعدة البيانات
        Group::create([
            'name' => $request->name,
            'code' => $request->code,
            'supervisor_id' => $request->supervisor_id,
            'academic_year' => $request->academic_year,
            'status' => $request->status,
        ]);

        // 3. إعادة التوجيه مع رسالة نجاح
        return redirect()->route('groups.index')->with('success', 'تم إنشاء المجموعة بنجاح');
    }
    public function edit(Group $group)
{
    // 1. التحقق من الصلاحية
    if (!auth()->user()->hasRole('admin') && auth()->id() != $group->supervisor_id) {
        abort(403);
    }

    // 2. جلب جميع المشرفين (عشان يظهروا في القائمة المنسدلة)
    $supervisors = \App\Models\User::role('supervisor')->get();

    // 3. جلب الطلاب المحجوزين لك (عشان تقدر تضيفهم للمجموعة)
    $availableStudents = \App\Models\User::role('student')
        ->where('pool_supervisor_id', auth()->id())
        ->whereDoesntHave('groups', function($q) use ($group) {
            $q->where('groups.id', '!=', $group->id);
        })
        ->get();

    // 4. إرسال المتغيرات الثلاثة للملف (group, supervisors, availableStudents)
    return view('groups.edit', compact('group', 'supervisors', 'availableStudents'));
}
public function update(Request $request, Group $group)
{
   
    // 2. تحديث بيانات المجموعة
    $group->update([
        'name' => $request->name,
        'supervisor_id' => $request->supervisor_id,
        'academic_year' => $request->academic_year,
        'status' => $request->status,
    ]);

    // 3. إذا كان هناك طلاب تم اختيارهم في صفحة التعديل
    if ($request->has('student_ids')) {
        $group->students()->sync($request->student_ids);
    }

    return redirect()->route('groups.index')->with('success', 'تم تحديث بيانات المجموعة بنجاح');
}
/**
 * عرض تفاصيل مجموعة محددة
 */
/**
     * عرض تفاصيل المجموعة (حل مشكلة الخطأ 500)
     */
public function show(Group $group)
{
    $user = Auth::user();

    // 1. التحقق من الصلاحيات
    if (!$user->hasRole('admin')) {
        if ($user->hasRole('student') && !$group->students->contains($user->id)) {
            abort(403);
        }
        if ($user->hasRole('supervisor') && $group->supervisor_id != $user->id && !$group->isManager($user)) {
            abort(403);
        }
    }

    // 2. جلب طلاب الحوض
    $myPoolStudents = \App\Models\User::role('student')
        ->where('pool_supervisor_id', $user->id)
        ->get();

    // 3. (الإضافة المهمة): جلب طلبات الانضمام المعلقة
    // انتبه: المتغير يجب أن يكون اسمه مطابقاً لما هو موجود في ملف show.blade.php
    $pendingRequests = \App\Models\GroupJoinRequest::where('group_id', $group->id)
        ->where('status', 'pending')
        ->with('student') // لضمان ظهور اسم الطالب
        ->get();

    // 4. تحميل العلاقات الأساسية
    $group->load(['supervisor', 'students', 'projects']);

    // 5. تمرير المتغيرات للـ Blade (أضفنا pendingRequests هنا)
    return view('groups.show', compact('group', 'myPoolStudents', 'pendingRequests'));
}
public function join(Request $request)
{
    $request->validate(['code' => 'required|string']);
    $user = Auth::user();

    $group = Group::where('code', $request->code)->first();
    if (!$group) {
        return back()->with('error', 'كود المجموعة غير صحيح.');
    }

    // فحص سقف المجموعة (15 طالباً مقبولين)
    if ($group->students()->count() >= 15) {
        return back()->with('error', 'عذراً، هذه المجموعة اكتمل عددها (15 طالباً).');
    }

    // التأكد أن الطالب ليس في مجموعة أخرى
    if ($user->groups()->count() > 0) {
        return back()->with('error', 'أنت مسجل بالفعل في مجموعة أخرى.');
    }

    // التحقق من وجود طلب سابق (باستخدام student_id كما في جدولك)
    $hasPending = \App\Models\GroupJoinRequest::where('group_id', $group->id)
        ->where('student_id', $user->id)
        ->where('status', 'pending')
        ->exists();

    if ($hasPending) {
        return back()->with('error', 'لقد أرسلت طلباً مسبقاً، يرجى انتظار موافقة المشرف.');
    }

    // إنشاء الطلب (التسميات مطابقة تماماً لجدولك)
    \App\Models\GroupJoinRequest::create([
        'group_id'   => $group->id,
        'student_id' => $user->id,
        'status'     => 'pending'
        
    ]);
    // هذا الكود يوضع بعد إنشاء طلب الانضمام بنجاح في قاعدة البيانات
\App\Models\Notification::create([
    'user_id'         => $group->supervisor_id,
    'type'            => 'custom',
    'notifiable_type' => \App\Models\User::class,
    'notifiable_id'   => $group->supervisor_id,
    'title'           => 'طلب انضمام جديد',
    'message'         => "قام الطالب (" . auth()->user()->name . ") بطلب انضمام لمجموعتك: " . $group->name,
    'read'            => false,
    'data'            => ['group_id' => $group->id],
]);

    return back()->with('success', 'تم إرسال طلب الانضمام بنجاح! انتظر موافقة المشرف.');
}
public function acceptStudent(Request $request, Group $group, $requestId)
{
    $joinRequest = \App\Models\GroupJoinRequest::findOrFail($requestId);
    $supervisor = Auth::user();

    // 1. فحص سقف الـ 15 للمشرف (طلاب الحوض + طلاب في كل مجموعاته)
    $myPoolCount = \App\Models\User::where('pool_supervisor_id', $supervisor->id)->count();
    $inGroupsCount = \App\Models\User::whereHas('groups', function($q) use ($supervisor) {
        $q->where('supervisor_id', $supervisor->id);
    })->count();

    if (($myPoolCount + $inGroupsCount) >= 15) {
        return back()->with('error', 'لا يمكنك القبول، لقد استوفيت الحد الأقصى (15 طالباً) المسموح لك به.');
    }

    // 2. فحص سقف الـ 15 للمجموعة نفسها
    if ($group->students()->count() >= 15) {
        return back()->with('error', 'هذه المجموعة اكتملت بالفعل (15 طالباً).');
    }

    // 3. التنفيذ السليم:
    // أ. إضافة الطالب لجدول الربط الأساسي (group_user) ليصبح عضواً رسمياً
    $group->students()->syncWithoutDetaching([$joinRequest->student_id]);

    // ب. تحديث حالة الطلب إلى 'approved' (كما في الـ enum عندك)
    $joinRequest->update([
        'status' => 'approved',
        'reviewed_by' => $supervisor->id,
        'reviewed_at' => now()
    ]);
    \App\Models\Notification::create([
    'user_id'         => $joinRequest->student_id,
    'type'            => 'custom',
    'notifiable_type' => \App\Models\User::class,
    'notifiable_id'   => $joinRequest->student_id,
    'title'           => 'تم قبول طلبك بنجاح 🎉',
    'message'         => "مبروك! تمت الموافقة على انضمامك لمجموعة: {$joinRequest->group->name}",
    'read'            => false,
    'data'            => ['status' => 'accepted'],
]);

    return back()->with('success', 'تم قبول الطالب وإضافته للمجموعة بنجاح.');
}
public function rejectStudent($requestId)
{
    // 1. جلب الطلب مع بيانات الطالب والمجموعة
    $joinRequest = \App\Models\GroupJoinRequest::with(['student', 'group'])->findOrFail($requestId);
    
    // 2. تحديث حالة الطلب
    $joinRequest->update([
        'status' => 'rejected',
        'reviewed_by' => Auth::id(),
        'reviewed_at' => now()
    ]);

    // 3. إرسال الإشعار (استخدمنا معرف الطالب من الطلب نفسه)
    \App\Models\Notification::create([
        'user_id'         => $joinRequest->student_id, // هنا تم التصحيح
        'type'            => 'custom',
        'notifiable_type' => \App\Models\User::class,
        'notifiable_id'   => $joinRequest->student_id, // هنا تم التصحيح
        'title'           => 'تحديث بشأن طلب الانضمام',
        'message'         => "عذراً، تم رفض طلب انضمامك لمجموعة: {$joinRequest->group->name}",
        'read'            => false,
        'data'            => [
            'group_id' => $joinRequest->group_id,
            'status' => 'rejected'
        ],
    ]);

    return back()->with('success', 'تم رفض طلب الانضمام وإرسال إشعار للطالب.');
}


public function supervisorReport($id)
{
    // التأكد أن المستخدم الحالي هو نفسه المشرف المطلوب أو مدير نظام
    if (auth()->id() != $id && !auth()->user()->hasRole('admin')) {
        abort(403, 'غير مصرح لك بالوصول لهذا التقرير');
    }

    // جلب المشرف مع مجموعاته وطلابه
    $supervisor = User::with(['groups.students', 'groups.projects'])->findOrFail($id);

    return view('supervisor-report', compact('supervisor'));
}

}