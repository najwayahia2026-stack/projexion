<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * نسبة الإنجاز في التقرير = مجموع نسب الأجزاء (phases) للمشروع
     */
    private function reportProgressSubquery(): string
    {
        return 'LEAST(100, (SELECT COALESCE(SUM(percentage), 0) FROM project_phases WHERE project_phases.project_id = projects.id))';
    }

    /**
     * عرض صفحة التقارير مع إمكانية الاستعلام والبحث
     */
 public function index(Request $request)
{
    $user = Auth::user();
    $specialties = \App\Models\Specialty::all();

    $query = \App\Models\Project::query()
        ->select('projects.*')
        ->selectRaw('LEAST(100, (SELECT COALESCE(SUM(percentage), 0) FROM project_phases WHERE project_phases.project_id = projects.id)) as report_progress')
        ->where('projects.status', '!=', 'rejected');

    // --- نفس منطق الفلترة والصلاحيات السابق ---
    if ($user->hasRole('committee')) {
        $query->whereHas('group', function ($q) use ($user) {
            $q->where('specialty_id', $user->specialty_id); 
        });
    } elseif ($user->hasRole('supervisor')) {
        $query->whereHas('group', function ($q) use ($user) {
            $q->where('supervisor_id', $user->id);
        });
    } elseif ($user->hasRole('admin') && $request->filled('specialty_id')) {
        $query->whereHas('group', function ($q) use ($request) {
            $q->where('specialty_id', $request->specialty_id);
        });
    }

    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . trim($request->search) . '%');
    }
    // ---------------------------------------

    // هنا السر: إذا كان الطلب "طباعة" نأخذ كل النتائج بدون تقطيع (Pagination)
    if ($request->has('print_mode')) {
        $projects = $query->orderBy('created_at', 'desc')->get();
        return view('reports.print', compact('projects'));
    }

    // إذا كان عرض عادي نستخدم pagination
    $projects = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
    return view('reports.index', compact('projects', 'specialties'));
}
public function printSupervisorReport($id)
{
    // 1. جلب بيانات المشرف
    $supervisor = \App\Models\User::findOrFail($id);

    // 2. جلب المجموعات المرتبطة بهذا المشرف يدوياً للتأكد من وجودها
    $groups = \App\Models\Group::where('supervisor_id', $id)
                ->with(['students', 'projects'])
                ->get();

    // 3. إذا كانت المجموعات فارغة، فهذا يعني أنها غير مسندة له في قاعدة البيانات
    if ($groups->isEmpty()) {
        // يمكنك هنا طباعة dd('No groups found for ID: ' . $id) للتأكد أثناء البرمجة
    }

    // 4. تمرير المجموعات للملف
    return view('supervisor_report', [
        'supervisor' => $supervisor,
        'groups' => $groups // تأكد من استخدام هذا المتغير في ملف الـ Blade
    ]);
}

}