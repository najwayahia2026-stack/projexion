<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Omaralalwi\Gpdf\Facade\Gpdf;

class EvaluationController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index()
    {
        $user = Auth::user();
        $evaluations = Evaluation::where('evaluator_id', $user->id)
            ->with('project')
            ->get();

        return view('evaluations.index', compact('evaluations'));
    }

    public function create(Project $project)
    {
        $user = Auth::user();
        
        // Committee members and supervisors can create evaluations
        if (!$user->hasRole('committee') && !$user->hasRole('supervisor')) {
            abort(403, 'ليس لديك صلاحية لإنشاء تقييمات');
        }
        
        // Supervisors can only evaluate projects from their groups
        if ($user->hasRole('supervisor') && !$user->hasRole('committee')) {
            $supervisedGroups = $user->supervisedGroups()->pluck('id');
            if (!in_array($project->group_id, $supervisedGroups->toArray())) {
                abort(403, 'يمكنك تقييم المشاريع في مجموعاتك فقط');
            }
        }

        // Committee can only evaluate projects matching their specialty
        if ($user->hasRole('committee') && $user->specialty_id && $project->specialty_id !== $user->specialty_id) {
            abort(403, 'يمكنك تقييم المشاريع في تخصصك فقط');
        }
        
        return view('evaluations.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $user = Auth::user();
        
        // Committee members and supervisors can create evaluations
        if (!$user->hasRole('committee') && !$user->hasRole('supervisor')) {
            abort(403, 'ليس لديك صلاحية لإنشاء تقييمات');
        }
        
        // Supervisors can only evaluate projects from their groups
        if ($user->hasRole('supervisor') && !$user->hasRole('committee')) {
            $supervisedGroups = $user->supervisedGroups()->pluck('id');
            if (!in_array($project->group_id, $supervisedGroups->toArray())) {
                abort(403, 'يمكنك تقييم المشاريع في مجموعاتك فقط');
            }
        }

        // Committee can only evaluate projects matching their specialty
        if ($user->hasRole('committee') && $user->specialty_id && $project->specialty_id !== $user->specialty_id) {
            abort(403, 'يمكنك تقييم المشاريع في تخصصك فقط');
        }

        $validated = $request->validate([
            'proposal_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'objectives_achievement' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'final_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'general_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'comments' => ['nullable', 'string'],
        ]);

        $evaluation = new Evaluation($validated);
        $evaluation->project_id = $project->id;
        $evaluation->evaluator_id = Auth::id();
        // تحديد نوع التقييم بناءً على دور المستخدم
        $evaluation->evaluation_type = $user->hasRole('committee') ? 'committee' : 'supervisor';
        $evaluation->calculateTotalScore();
        $evaluation->status = 'submitted';
        $evaluation->evaluated_at = now();
        $evaluation->save();

        // Archive project after evaluation (only if not already archived)
        $wasArchived = $project->archived_at !== null;
        if (!$wasArchived) {
            $project->update([
                'archived_at' => now(),
                'last_archived_at' => now(),
                'last_archived_by' => $user->id,
            ]);
        }

        $successMessage = 'تم إضافة التقييم بنجاح';
        if (!$wasArchived) {
            $successMessage .= ' وتم أرشفة المشروع';
        }

        return redirect()->route('projects.show', $project)
            ->with('success', $successMessage);
    }

    public function show(Evaluation $evaluation)
    {
        $evaluation->load('project', 'evaluator');
        
        return view('evaluations.show', compact('evaluation'));
    }

    public function edit(Evaluation $evaluation)
    {
        $user = Auth::user();
        
        // Committee members and supervisors can edit evaluations
        if (!$user->hasRole('committee') && !$user->hasRole('supervisor')) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا التقييم');
        }
        
        // Check if the evaluation belongs to the current user
        if ($evaluation->evaluator_id !== $user->id) {
            abort(403, 'يمكنك تعديل تقييماتك فقط');
        }
        
        // Supervisors can only edit evaluations for projects from their groups
        if ($user->hasRole('supervisor') && !$user->hasRole('committee')) {
            $supervisedGroups = $user->supervisedGroups()->pluck('id');
            if (!in_array($evaluation->project->group_id, $supervisedGroups->toArray())) {
                abort(403, 'يمكنك تعديل تقييمات المشاريع في مجموعاتك فقط');
            }
        }
        
        $evaluation->load('project');
        
        return view('evaluations.edit', compact('evaluation'));
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $user = Auth::user();
        
        // Committee members and supervisors can edit evaluations
        if (!$user->hasRole('committee') && !$user->hasRole('supervisor')) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا التقييم');
        }
        
        // Check if the evaluation belongs to the current user
        if ($evaluation->evaluator_id !== $user->id) {
            abort(403, 'يمكنك تعديل تقييماتك فقط');
        }
        
        // Supervisors can only edit evaluations for projects from their groups
        if ($user->hasRole('supervisor') && !$user->hasRole('committee')) {
            $supervisedGroups = $user->supervisedGroups()->pluck('id');
            if (!in_array($evaluation->project->group_id, $supervisedGroups->toArray())) {
                abort(403, 'يمكنك تعديل تقييمات المشاريع في مجموعاتك فقط');
            }
        }

        $validated = $request->validate([
            'proposal_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'objectives_achievement' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'final_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'general_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'comments' => ['nullable', 'string'],
        ]);

        $evaluation->fill($validated);
        $evaluation->calculateTotalScore();
        $evaluation->save();

        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'تم تحديث التقييم بنجاح');
    }

    public function destroy(Evaluation $evaluation)
    {
        $user = Auth::user();
        
        // Committee members and supervisors can delete evaluations
        if (!$user->hasRole('committee') && !$user->hasRole('supervisor')) {
            abort(403, 'ليس لديك صلاحية لحذف هذا التقييم');
        }
        
        // Check if the evaluation belongs to the current user
        if ($evaluation->evaluator_id !== $user->id) {
            abort(403, 'يمكنك حذف تقييماتك فقط');
        }
        
        // Supervisors can only delete evaluations for projects from their groups
        if ($user->hasRole('supervisor') && !$user->hasRole('committee')) {
            $supervisedGroups = $user->supervisedGroups()->pluck('id');
            if (!in_array($evaluation->project->group_id, $supervisedGroups->toArray())) {
                abort(403, 'يمكنك حذف تقييمات المشاريع في مجموعاتك فقط');
            }
        }
        
        $evaluation->delete();

        return redirect()->route('evaluations.index')
            ->with('success', 'تم حذف التقييم بنجاح');
    }

    public function generatePDF(Project $project)
    {
        $project->load('group.students', 'evaluations.evaluator');
        $evaluations = $project->evaluations;

        $html = view('evaluations.pdf', [
            'project' => $project,
            'evaluations' => $evaluations,
        ])->render();

        $pdfContent = Gpdf::generate($html);
        $filename = 'project-evaluation-' . $project->id . '.pdf';

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
