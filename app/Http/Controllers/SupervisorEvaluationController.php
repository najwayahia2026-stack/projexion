<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\SupervisorEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupervisorEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('supervisor')) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        }
        
        $evaluations = SupervisorEvaluation::where('supervisor_id', $user->id)
            ->with('project.group')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('supervisor-evaluations.index', compact('evaluations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('supervisor')) {
            abort(403, 'ليس لديك صلاحية لإنشاء تقييمات');
        }
        
        // التحقق من أن المشرف يشرف على مجموعة المشروع
        $supervisedGroups = $user->supervisedGroups()->pluck('id');
        if (!in_array($project->group_id, $supervisedGroups->toArray())) {
            abort(403, 'يمكنك تقييم المشاريع في مجموعاتك فقط');
        }
        
        // التحقق من عدم وجود تقييم سابق
        $existingEvaluation = SupervisorEvaluation::where('project_id', $project->id)
            ->where('supervisor_id', $user->id)
            ->first();
            
        if ($existingEvaluation) {
            return redirect()->route('supervisor-evaluations.edit', $existingEvaluation)
                ->with('info', 'يوجد تقييم سابق لهذا المشروع. يمكنك تعديله.');
        }
        
        return view('supervisor-evaluations.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('supervisor')) {
            abort(403, 'ليس لديك صلاحية لإنشاء تقييمات');
        }
        
        // التحقق من أن المشرف يشرف على مجموعة المشروع
        $supervisedGroups = $user->supervisedGroups()->pluck('id');
        if (!in_array($project->group_id, $supervisedGroups->toArray())) {
            abort(403, 'يمكنك تقييم المشاريع في مجموعاتك فقط');
        }
        
        // التحقق من عدم وجود تقييم سابق
        $existingEvaluation = SupervisorEvaluation::where('project_id', $project->id)
            ->where('supervisor_id', $user->id)
            ->first();
            
        if ($existingEvaluation) {
            return redirect()->route('supervisor-evaluations.edit', $existingEvaluation)
                ->with('error', 'يوجد تقييم سابق لهذا المشروع.');
        }

        $validated = $request->validate([
            'work_quality' => ['required', 'numeric', 'min:1', 'max:10', 'regex:/^\d+(\.\d{1})?$/'],
            'punctuality' => ['required', 'numeric', 'min:1', 'max:10', 'regex:/^\d+(\.\d{1})?$/'],
            'teamwork' => ['required', 'numeric', 'min:1', 'max:10', 'regex:/^\d+(\.\d{1})?$/'],
            'innovation' => ['required', 'numeric', 'min:1', 'max:10', 'regex:/^\d+(\.\d{1})?$/'],
            'technical_skills' => ['required', 'numeric', 'min:1', 'max:10', 'regex:/^\d+(\.\d{1})?$/'],
            'communication' => ['required', 'numeric', 'min:1', 'max:10', 'regex:/^\d+(\.\d{1})?$/'],
            'progress_score' => ['nullable', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'overall_assessment' => ['required', 'numeric', 'min:1', 'max:10', 'regex:/^\d+(\.\d{1})?$/'],
            'strengths' => ['nullable', 'string', 'max:2000'],
            'weaknesses' => ['nullable', 'string', 'max:2000'],
            'recommendations' => ['nullable', 'string', 'max:2000'],
            'general_comments' => ['nullable', 'string', 'max:2000'],
        ]);

        $evaluation = new SupervisorEvaluation($validated);
        $evaluation->project_id = $project->id;
        $evaluation->supervisor_id = Auth::id();
        $evaluation->calculateTotalScore();
        $evaluation->status = $request->has('save_draft') ? 'draft' : 'submitted';
        $evaluation->evaluated_at = $request->has('save_draft') ? null : now();
        $evaluation->save();

        // Archive project after evaluation submission (not for drafts)
        $wasArchived = $project->archived_at !== null;
        if ($evaluation->status === 'submitted' && !$wasArchived) {
            $project->update([
                'archived_at' => now(),
                'last_archived_at' => now(),
                'last_archived_by' => $user->id,
            ]);
        }

        $message = $evaluation->status === 'draft' 
            ? 'تم حفظ التقييم كمسودة بنجاح' 
            : 'تم إضافة التقييم بنجاح' . (!$wasArchived ? ' وتم أرشفة المشروع' : '');

        return redirect()->route('projects.show', $project)
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(SupervisorEvaluation $supervisorEvaluation)
    {
        $user = Auth::user();
        
        // المشرف يمكنه رؤية تقييماته فقط، والطلاب يمكنهم رؤية تقييمات مشاريعهم
        if ($supervisorEvaluation->supervisor_id !== $user->id) {
            // التحقق إذا كان المستخدم طالب في مجموعة المشروع
            $project = $supervisorEvaluation->project;
            $isStudentInGroup = $project->group->students->contains('id', $user->id);
            
            if (!$isStudentInGroup && !$user->hasRole('admin|department_admin|committee')) {
                abort(403, 'ليس لديك صلاحية لعرض هذا التقييم');
            }
        }
        
        $supervisorEvaluation->load('project.group.students', 'supervisor');
        
        return view('supervisor-evaluations.show', compact('supervisorEvaluation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupervisorEvaluation $supervisorEvaluation)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('supervisor')) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا التقييم');
        }
        
        // التحقق من أن التقييم يخص المشرف الحالي
        if ($supervisorEvaluation->supervisor_id !== $user->id) {
            abort(403, 'يمكنك تعديل تقييماتك فقط');
        }
        
        // التحقق من أن المشرف يشرف على مجموعة المشروع
        $supervisedGroups = $user->supervisedGroups()->pluck('id');
        if (!in_array($supervisorEvaluation->project->group_id, $supervisedGroups->toArray())) {
            abort(403, 'يمكنك تعديل تقييمات المشاريع في مجموعاتك فقط');
        }
        
        $supervisorEvaluation->load('project');
        
        return view('supervisor-evaluations.edit', compact('supervisorEvaluation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupervisorEvaluation $supervisorEvaluation)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('supervisor')) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا التقييم');
        }
        
        // التحقق من أن التقييم يخص المشرف الحالي
        if ($supervisorEvaluation->supervisor_id !== $user->id) {
            abort(403, 'يمكنك تعديل تقييماتك فقط');
        }
        
        // التحقق من أن المشرف يشرف على مجموعة المشروع
        $supervisedGroups = $user->supervisedGroups()->pluck('id');
        if (!in_array($supervisorEvaluation->project->group_id, $supervisedGroups->toArray())) {
            abort(403, 'يمكنك تعديل تقييمات المشاريع في مجموعاتك فقط');
        }

        $validated = $request->validate([
            'work_quality' => ['required', 'numeric', 'min:1', 'max:10', 'regex:/^\d+(\.\d{1})?$/'],
            'punctuality' => ['required', 'numeric', 'min:1', 'max:10', 'regex:/^\d+(\.\d{1})?$/'],
            'teamwork' => ['required', 'numeric', 'min:1', 'max:10', 'regex:/^\d+(\.\d{1})?$/'],
            'innovation' => ['required', 'numeric', 'min:1', 'max:10', 'regex:/^\d+(\.\d{1})?$/'],
            'technical_skills' => ['required', 'numeric', 'min:1', 'max:10', 'regex:/^\d+(\.\d{1})?$/'],
            'communication' => ['required', 'numeric', 'min:1', 'max:10', 'regex:/^\d+(\.\d{1})?$/'],
            'progress_score' => ['nullable', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'overall_assessment' => ['required', 'numeric', 'min:1', 'max:10', 'regex:/^\d+(\.\d{1})?$/'],
            'strengths' => ['nullable', 'string', 'max:2000'],
            'weaknesses' => ['nullable', 'string', 'max:2000'],
            'recommendations' => ['nullable', 'string', 'max:2000'],
            'general_comments' => ['nullable', 'string', 'max:2000'],
        ]);

        $supervisorEvaluation->fill($validated);
        $supervisorEvaluation->calculateTotalScore();
        
        $project = $supervisorEvaluation->project;
        $wasArchived = $project->archived_at !== null;
        
        if ($request->has('save_draft')) {
            $supervisorEvaluation->status = 'draft';
            $supervisorEvaluation->evaluated_at = null;
        } elseif ($request->has('submit')) {
            $supervisorEvaluation->status = 'submitted';
            $supervisorEvaluation->evaluated_at = now();
            
            // Archive project after evaluation submission (if not already archived)
            if (!$wasArchived) {
                $project->update([
                    'archived_at' => now(),
                    'last_archived_at' => now(),
                    'last_archived_by' => $user->id,
                ]);
            }
        } elseif ($request->has('finalize')) {
            $supervisorEvaluation->status = 'finalized';
            if (!$supervisorEvaluation->evaluated_at) {
                $supervisorEvaluation->evaluated_at = now();
            }
            
            // Archive project after finalizing evaluation (if not already archived)
            if (!$wasArchived) {
                $project->update([
                    'archived_at' => now(),
                    'last_archived_at' => now(),
                    'last_archived_by' => $user->id,
                ]);
            }
        }
        
        $supervisorEvaluation->save();

        $message = match($supervisorEvaluation->status) {
            'draft' => 'تم حفظ التقييم كمسودة بنجاح',
            'submitted' => 'تم تحديث التقييم بنجاح' . (!$wasArchived ? ' وتم أرشفة المشروع' : ''),
            'finalized' => 'تم إقرار التقييم نهائياً بنجاح' . (!$wasArchived ? ' وتم أرشفة المشروع' : ''),
            default => 'تم تحديث التقييم بنجاح'
        };

        return redirect()->route('supervisor-evaluations.show', $supervisorEvaluation)
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupervisorEvaluation $supervisorEvaluation)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('supervisor')) {
            abort(403, 'ليس لديك صلاحية لحذف هذا التقييم');
        }
        
        // التحقق من أن التقييم يخص المشرف الحالي
        if ($supervisorEvaluation->supervisor_id !== $user->id) {
            abort(403, 'يمكنك حذف تقييماتك فقط');
        }
        
        // التحقق من أن المشرف يشرف على مجموعة المشروع
        $supervisedGroups = $user->supervisedGroups()->pluck('id');
        if (!in_array($supervisorEvaluation->project->group_id, $supervisedGroups->toArray())) {
            abort(403, 'يمكنك حذف تقييمات المشاريع في مجموعاتك فقط');
        }
        
        $project = $supervisorEvaluation->project;
        $supervisorEvaluation->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', 'تم حذف التقييم بنجاح');
    }
}
