<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Group;
use App\Models\ProjectFile;
use App\Models\ProjectSection;
use App\Models\ProjectPhase;
use App\Services\AISimilarityService;
use App\Services\FileSimilarityService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Omaralalwi\Gpdf\Facade\Gpdf;

class ProjectController extends Controller
{
    protected AISimilarityService $similarityService;
    protected FileSimilarityService $fileSimilarityService;
    protected NotificationService $notificationService;

    public function __construct(AISimilarityService $similarityService, FileSimilarityService $fileSimilarityService, NotificationService $notificationService)
    {
        $this->similarityService = $similarityService;
        $this->fileSimilarityService = $fileSimilarityService;
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
{
    $user = Auth::user();
    $filter = $request->get('filter', 'all'); // all, archived, active
    $specialtyId = $request->get('specialty_id'); // الفلتر الجديد للأدمن
    
    $query = Project::query();
    
    // Apply role-based filtering
    if ($user->hasRole('admin') || $user->hasRole('department_admin')) {
        // الأدمن يرى كل المشاريع، وإذا اختار تخصص معين نفلتر له
        if ($specialtyId) {
            $query->whereHas('group', function ($q) use ($specialtyId) {
                $q->where('specialty_id', $specialtyId);
            });
        }
    } elseif ($user->hasRole('student')) {
        $query->whereHas('group', function ($query) use ($user) {
            $query->whereHas('students', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        });
    } elseif ($user->hasRole('supervisor')) {
        $query->whereHas('group', function ($query) use ($user) {
            $query->where('supervisor_id', $user->id);
        });
    } elseif ($user->hasRole('committee')) {
        // Committee only sees archived projects matching their specialty
        $query->archived()->where('status', '!=', 'rejected');
        if ($user->specialty_id) {
            $query->where('specialty_id', $user->specialty_id);
        }
        $filter = 'archived'; // Force archived view for committee
    }
    
    // Apply archive filter (skip if committee - already filtered)
    if ($filter === 'archived') {
        $query->archived();
    } elseif ($filter === 'active') {
        $query->notArchived();
    }
    // 'all' shows everything
    
    $projects = $query->with('group', 'phases', 'similarityChecks')
        ->orderBy('archived_at', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();

    // جلب التخصصات فقط إذا كان المستخدم أدمن ليراها في قائمة الفلتر
    $specialties = ($user->hasRole('admin') || $user->hasRole('department_admin')) 
        ? \App\Models\Specialty::orderBy('name')->get() 
        : collect();

    return view('projects.index', compact('projects', 'filter', 'specialties'));
}
    public function create()
    {
        $user = Auth::user();
        
        // Only students can create projects. Supervisors and committee cannot create projects.
        if (!$user->hasRole('student')) {
            abort(403, 'فقط الطلاب يمكنهم إضافة مشاريع');
        }
        
        $groups = $user->groups;
        $specialties = \App\Models\Specialty::orderBy('name')->get();
        
        return view('projects.create', compact('groups', 'specialties'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Only students can create projects. Supervisors and committee cannot create projects.
        if (!$user->hasRole('student')) {
            abort(403, 'فقط الطلاب يمكنهم إضافة مشاريع');
        }
        
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'objectives' => ['nullable', 'string'],
            'technologies' => ['nullable', 'string'],
            'group_id' => ['required', 'exists:groups,id'],
            'specialty_id' => ['required', 'exists:specialties,id'],
        ]);
        
        // Verify that the user belongs to the group
        if (!$user->groups->contains($validated['group_id'])) {
            return back()->withErrors(['group_id' => 'أنت لست عضو في هذه المجموعة']);
        }

        $project = Project::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'objectives' => $validated['objectives'] ?? null,
            'technologies' => $validated['technologies'] ?? null,
            'group_id' => $validated['group_id'],
            'specialty_id' => $validated['specialty_id'],
            'status' => 'pending',
            'progress_percentage' => 0,
        ]);

        // Check project similarity automatically
        try {
            $similarityCheck = $this->similarityService->checkSimilarity($project);

            // Auto-reject proposal if similarity is greater than 25%
            if ($similarityCheck->status === 'completed' && (float) $similarityCheck->similarity_percentage > 25) {
                $autoRejectReason = 'تم رفض اقتراح المشروع تلقائياً لأن نسبة التشابه تجاوزت 25% ('
                    . number_format($similarityCheck->similarity_percentage, 2) . '%).';

                $project->update([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'rejection_reason' => $autoRejectReason,
                    'archived_at' => now(),
                    'last_archived_at' => now(),
                    'last_archived_by' => $user->id,
                ]);

                if ($project->group && $project->owner) {
                    $this->notificationService->notifyProjectStatusChange($project, 'rejected', $user);
                }

                return redirect()->route('projects.show', $project)
                    ->with('error', $autoRejectReason);
            }
        } catch (\Exception $e) {
            Log::error('Similarity check failed: ' . $e->getMessage());
        }

        // Send notification to supervisor
        $this->notificationService->notifyNewProjectSubmission($project);

        return redirect()->route('projects.show', $project)
            ->with('success', 'تم إنشاء اقتراح المشروع بنجاح. انتظر موافقة المشرف لإضافة جزئيات المشروع.');
    }

   public function show(Project $project)
{
    // إذا كان الرابط يحتوي على كلمة print، افتح ملف التقرير المنفصل
    if (request()->has('print')) {
        return view('projects.report', compact('project'));
    }

    // غير ذلك، افتح الصفحة العادية
    return view('projects.show', compact('project'));
}
    public function edit(Project $project)
    {
        if ($project->isArchived()) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط ولا يمكن تعديلها.');
        }
        $user = Auth::user();
        $groups = $user->groups;
        $project->load('phases');
        
        return view('projects.edit', compact('project', 'groups'));
    }

    public function update(Request $request, Project $project)
    {
        if ($project->isArchived()) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط ولا يمكن تعديلها.');
        }
        // Check authorization
        $user = Auth::user();
        
        if (!$user->can('edit projects')) {
            // Check if user is in the project's group
            $canEdit = false;
            if ($project->group) {
                $canEdit = $project->group->students->contains($user) || 
                          $project->group->supervisor_id === $user->id;
            }
            
            if (!$canEdit) {
                abort(403, 'غير مصرح لك بتعديل هذا المشروع');
            }
        }
        
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'objectives' => ['required', 'string'],
            'technologies' => ['nullable', 'string'],
            'progress_percentage' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'تم تحديث المشروع بنجاح.');
    }

    public function destroy(Project $project)
    {
        if ($project->isArchived()) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط ولا يمكن حذفها.');
        }
        try {
            // Check authorization
            $user = Auth::user();
            
            // Check if user has delete permission
            if (!$user->can('delete projects')) {
                abort(403, 'غير مصرح لك بحذف هذا المشروع');
            }
            
            // If user is a student, check if they are in the project's group
            if ($user->hasRole('student')) {
                if (!$project->group || !$project->group->students->contains($user->id)) {
                    abort(403, 'غير مصرح لك بحذف هذا المشروع');
                }
            }
            
            // Delete associated files
            foreach ($project->files as $file) {
                if (Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->delete($file->path);
                }
            }
            
            // Delete the project (cascade will handle related records)
            $project->delete();
            
            return redirect()->route('projects.index')
                ->with('success', 'تم حذف المشروع بنجاح.');
        } catch (\Exception $e) {
            Log::error('Project deletion failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف المشروع: ' . $e->getMessage());
        }
    }

    public function checkSimilarity(Project $project)
    {
        $user = Auth::user();
        
        // Block non-committee/admins from running similarity on archived projects (read-only)
        if ($project->isArchived() && !$user->hasRole('committee') && !$user->hasRole('admin')) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط. فقط اللجنة يمكنها فحص التشابه على المشاريع المؤرشفة.');
        }
        
        // Committee members can only check similarity on the latest archived version
        if ($user->hasRole('committee') && !$user->hasRole('admin')) {
            // Check if project has been archived and sent to committee
            if (!$project->last_archived_at || !$project->last_archived_by) {
                return redirect()->back()->with('error', 'لا يمكن فحص التشابه. المشروع لم يتم أرشفته وإرساله للجنة بعد.');
            }
            
            // Get the latest archived phase
            $latestPhase = $project->phases()
                ->whereNotNull('archived_at')
                ->orderBy('archived_at', 'desc')
                ->first();
            
            if (!$latestPhase) {
                return redirect()->back()->with('error', 'لا توجد نسخة أرشيفية للمشروع. يرجى طلب أرشفة المشروع من المشرف.');
            }
            
            // Only check similarity on the latest archived phase files
            $project = $project->load(['phases' => function($query) use ($latestPhase) {
                $query->where('id', $latestPhase->id)->with('files');
            }]);
        }
        
        try {
            $similarityCheck = $this->similarityService->checkSimilarity($project);

            if ($similarityCheck->status === 'failed') {
                return redirect()->back()->with('error', 'فشل فحص التشابه. يرجى المحاولة مرة أخرى.');
            }

            return redirect()->back()->with('success', 'تم فحص التشابه بنجاح. نسبة التشابه: ' . number_format($similarityCheck->similarity_percentage, 2) . '%');
        } catch (\Exception $e) {
            Log::error('Check similarity error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء فحص التشابه: ' . $e->getMessage());
        }
    }

    /**
     * Check similarity for all phase files in the project
     */
    public function checkAllPhasesSimilarity(Project $project)
    {
        $user = Auth::user();
        
        // Block non-committee/admins from running similarity on archived projects (read-only)
        if ($project->isArchived() && !$user->hasRole('committee') && !$user->hasRole('admin')) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط. فقط اللجنة يمكنها فحص التشابه على المشاريع المؤرشفة.');
        }
        
        // Committee members can only check similarity on the latest archived version
        if ($user->hasRole('committee') && !$user->hasRole('admin')) {
            // Check if project has been archived and sent to committee
            if (!$project->last_archived_at || !$project->last_archived_by) {
                return redirect()->back()->with('error', 'لا يمكن فحص التشابه. المشروع لم يتم أرشفته وإرساله للجنة بعد.');
            }
            
            // Get the latest archived phase only
            $latestPhase = $project->phases()
                ->whereNotNull('archived_at')
                ->orderBy('archived_at', 'desc')
                ->first();
            
            if (!$latestPhase) {
                return redirect()->back()->with('error', 'لا توجد نسخة أرشيفية للمشروع. يرجى طلب أرشفة المشروع من المشرف.');
            }
            
            // Load only the latest archived phase with its files
            $project->load(['phases' => function($query) use ($latestPhase) {
                $query->where('id', $latestPhase->id)->with('files');
            }]);
        } else {
            // For supervisors and admins, check all phases
            $project->load('phases.files');
        }
        
        try {
            if ($project->phases->isEmpty()) {
                return redirect()->back()
                    ->with('warning', 'لا توجد أجزاء في هذا المشروع لفحصها');
            }

            $totalCheckedCount = 0;
            $totalFilesCount = 0;
            $maxSimilarity = 0;
            $maxAiProbability = null;
            $filesWithAi = 0;
            $phasesChecked = 0;

            foreach ($project->phases as $phase) {
                if ($phase->files->isEmpty()) {
                    continue;
                }

                $phaseCheckedCount = 0;
                $phaseMaxSimilarity = 0;
                $phaseMaxAiProbability = null;

                foreach ($phase->files as $file) {
                    $totalFilesCount++;
                    
                    if ($file->status === 'pending' || $file->status === 'checked') {
                        $result = $this->fileSimilarityService->checkFileSimilarity($file);
                        
                        if ($result['success'] ?? false) {
                            $totalCheckedCount++;
                            $phaseCheckedCount++;
                            
                            $fileSimilarity = $result['similarity_score'] ?? 0;
                            $fileAiProbability = $result['ai_probability'] ?? null;
                            
                            if ($fileSimilarity > $maxSimilarity) {
                                $maxSimilarity = $fileSimilarity;
                            }
                            
                            if ($fileSimilarity > $phaseMaxSimilarity) {
                                $phaseMaxSimilarity = $fileSimilarity;
                            }
                            
                            if ($fileAiProbability !== null) {
                                if ($maxAiProbability === null || $fileAiProbability > $maxAiProbability) {
                                    $maxAiProbability = $fileAiProbability;
                                }
                                if ($fileAiProbability >= 50) {
                                    $filesWithAi++;
                                }
                                if ($phaseMaxAiProbability === null || $fileAiProbability > $phaseMaxAiProbability) {
                                    $phaseMaxAiProbability = $fileAiProbability;
                                }
                            }
                        }
                    }
                }

                // Update phase similarity score and count phase as checked if any files were checked
                if ($phaseCheckedCount > 0) {
                    // Update similarity score (even if it's 0, to mark the phase as checked)
                    $phase->update(['similarity_score' => $phaseMaxSimilarity]);
                    $phasesChecked++;
                }
            }

            if ($totalCheckedCount > 0) {
                // Create beautiful success message with AI information
                $successData = [
                    'files_count' => $totalCheckedCount,
                    'total_files' => $totalFilesCount,
                    'phases_count' => $phasesChecked,
                    'max_similarity' => number_format($maxSimilarity, 2),
                    'max_ai_probability' => $maxAiProbability !== null ? number_format($maxAiProbability, 2) : null,
                    'files_with_ai' => $filesWithAi,
                ];
                
                return redirect()->back()
                    ->with('success', "تم فحص {$totalCheckedCount} ملف من {$totalFilesCount} ملف في {$phasesChecked} جزء بنجاح")
                    ->with('similarity_data', $successData);
            } else {
                return redirect()->back()
                    ->with('warning', 'لم يتم فحص أي ملف. قد تكون جميع الملفات معتمدة أو مرفوضة مسبقاً.');
            }
        } catch (\Exception $e) {
            Log::error('Check all phases similarity error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء فحص التشابه: ' . $e->getMessage());
        }
    }

    // Section management methods
    public function storeSection(Request $request, Project $project)
    {
        if ($project->isArchived()) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط ولا يمكن إضافة أقسام جديدة.');
        }
        if ($project->isProposal()) {
            abort(403, 'يجب الموافقة على اقتراح المشروع من قبل المشرف قبل إضافة أي قسم.');
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        // Get the next order number
        $nextOrder = ($project->sections()->max('order_number') ?? 0) + 1;

        $section = ProjectSection::create([
            'project_id' => $project->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'order_number' => $nextOrder,
            'percentage' => $validated['percentage'],
        ]);

        return redirect()->route('projects.edit', $project)
            ->with('success', 'تم إضافة القسم بنجاح.');
    }

    public function updateSection(Request $request, Project $project, ProjectSection $section)
    {
        if ($project->isArchived()) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط ولا يمكن تعديل أقسامها.');
        }
        if ($project->isProposal()) {
            abort(403, 'يجب الموافقة على اقتراح المشروع من قبل المشرف قبل تعديل الأقسام.');
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $section->update($validated);
        
        // Recalculate project progress
        $project->calculateProgress();

        return redirect()->route('projects.edit', $project)
            ->with('success', 'تم تحديث القسم بنجاح.');
    }

    public function destroySection(Project $project, ProjectSection $section)
    {
        if ($project->isArchived()) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط ولا يمكن حذف أقسامها.');
        }
        if ($project->isProposal()) {
            abort(403, 'يجب الموافقة على اقتراح المشروع من قبل المشرف.');
        }
        $section->delete();

        // Reorder remaining sections
        $sections = $project->sections()->orderBy('order_number')->get();
        foreach ($sections as $index => $sec) {
            $sec->update(['order_number' => $index + 1]);
        }

        return redirect()->route('projects.edit', $project)
            ->with('success', 'تم حذف القسم بنجاح.');
    }

    public function uploadFileToSection(Request $request, Project $project, ProjectSection $section)
    {
        if ($project->isProposal()) {
            abort(403, 'يجب الموافقة على اقتراح المشروع من قبل المشرف قبل رفع الملفات.');
        }
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('project-files', $fileName, 'public');

        $fileType = in_array($file->getClientOriginalExtension(), ['doc', 'docx']) ? 'word' : 'pdf';

        $projectFile = ProjectFile::create([
            'project_id' => $project->id,
            'section_id' => $section->id,
            'uploaded_by' => Auth::id(),
            'name' => $fileName,
            'original_name' => $file->getClientOriginalName(),
            'path' => $filePath,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'file_type' => $fileType,
            'type' => 'document',
            'status' => 'pending',
        ]);

        // Check file similarity in background or synchronously
        try {
            $similarityResult = $this->fileSimilarityService->checkProjectFileSimilarity($projectFile);
            
            if (isset($similarityResult['error'])) {
                return redirect()->back()
                    ->with('warning', $similarityResult['error']);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the upload
            \Log::error('File similarity check failed: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'تم رفع الملف بنجاح. جاري فحص التشابه...');
    }

    /**
     * Approve a project section
     */
    public function approveSection(Request $request, Project $project, ProjectSection $section)
    {
        $user = Auth::user();
        
        // Check if user is department manager or supervisor
        $canApprove = false;
        if ($project->group) {
            $canApprove = $project->group->isManager($user) || 
                         $project->group->supervisor_id === $user->id ||
                         $user->hasRole('admin');
        }
        
        if (!$canApprove) {
            abort(403, 'ليس لديك صلاحية للموافقة على الجزئيات');
        }

        $section->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'rejected_by' => null,
            'rejected_at' => null,
            'rejection_reason' => null,
        ]);

        // Recalculate project progress
        $project->calculateProgress();

        // Send notification
        $this->notificationService->notifySectionStatusChange($section, 'approved', $user);

        return redirect()->back()->with('success', 'تم الموافقة على الجزئية بنجاح.');
    }

    /**
     * Reject a project section
     */
    public function rejectSection(Request $request, Project $project, ProjectSection $section)
    {
        $user = Auth::user();
        
        // Check if user is department manager or supervisor
        $canReject = false;
        if ($project->group) {
            $canReject = $project->group->isManager($user) || 
                        $project->group->supervisor_id === $user->id ||
                        $user->hasRole('admin');
        }
        
        if (!$canReject) {
            abort(403, 'ليس لديك صلاحية لرفض الجزئيات');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string'],
        ]);

        $section->update([
            'status' => 'rejected',
            'rejected_by' => $user->id,
            'rejected_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
            'approved_by' => null,
            'approved_at' => null,
        ]);

        // Recalculate project progress
        $project->calculateProgress();

        // Send notification
        $this->notificationService->notifySectionStatusChange($section, 'rejected', $user);

        return redirect()->back()->with('success', 'تم رفض الجزئية بنجاح.');
    }

    /**
     * قبول الاقتراح - يسمح للطالب بإضافة الأجزاء (المرحلة الأولى)
     */
    public function approveProposal(Request $request, Project $project)
    {
        $user = Auth::user();
        $canApprove = $user->hasRole('admin') || $user->hasRole('department_admin');
        if (!$canApprove && $project->group) {
            $canApprove = $project->group->isManager($user) || $project->group->supervisor_id === $user->id;
        }
        if (!$canApprove) {
            abort(403, 'ليس لديك صلاحية للموافقة على اقتراحات المشاريع');
        }
        if (!$project->isProposal()) {
            return redirect()->back()->with('error', 'المشروع ليس في مرحلة الاقتراح.');
        }

        $project->update(['status' => 'in_progress']);

        if ($project->group && $project->owner) {
            $this->notificationService->create(
                $project->owner,
                'project_proposal_approved',
                $project,
                'تم قبول اقتراح المشروع',
                "تم قبول اقتراح مشروعك '{$project->title}'. يمكنك الآن إضافة الأجزاء ورفع الملفات.",
                ['project_id' => $project->id]
            );
        }

        return redirect()->back()->with('success', 'تم قبول الاقتراح. يمكن للطالب الآن إضافة الأجزاء.');
    }

    /**
     * الموافقة النهائية على المشروع - بعد اكتمال جميع الأجزاء 100% (المرحلة الثانية)
     */
    public function approve(Request $request, Project $project)
    {
        $user = Auth::user();
        $canApprove = $user->hasRole('admin') || $user->hasRole('department_admin');
        if (!$canApprove && $project->group) {
            $canApprove = $project->group->isManager($user) || $project->group->supervisor_id === $user->id;
        }
        if (!$canApprove) {
            abort(403, 'ليس لديك صلاحية للموافقة على المشاريع');
        }
        if ($project->isProposal()) {
            return redirect()->back()->with('error', 'يجب قبول الاقتراح أولاً. استخدم زر "قبول الاقتراح".');
        }
        if ($project->status === 'approved' || $project->status === 'rejected') {
            return redirect()->back()->with('error', 'تم اتخاذ قرار نهائي على هذا المشروع مسبقاً.');
        }
        if (!$project->isReadyForFinalApproval()) {
            return redirect()->back()->with('error', 'يجب إكمال الموافقة على جميع الأجزاء (100%) قبل الموافقة النهائية على المشروع. نسبة المعتمدة: ' . number_format($project->approved_phases_percentage, 1) . '%');
        }

        $request->validate(['notes' => ['nullable', 'string']]);

        $project->update([
            'status' => 'approved',
            'approved_at' => now(),
            'supervisor_notes' => $request->notes,
            'rejected_at' => null,
            'rejection_reason' => null,
            'archived_at' => now(),
            'last_archived_at' => now(),
            'last_archived_by' => $user->id,
        ]);

        $this->notificationService->notifyProjectStatusChange($project, 'approved', $user);

        return redirect()->back()->with('success', 'تم الموافقة على المشروع بنجاح وتم أرشفته.');
    }

    /**
     * Reject a project - يعمل لرفض الاقتراح أو المشروع الكامل، ويتم أرشفته دائماً
     */
    public function reject(Request $request, Project $project)
    {
        $user = Auth::user();
        $canReject = $user->hasRole('admin') || $user->hasRole('department_admin');
        if (!$canReject && $project->group) {
            $canReject = $project->group->isManager($user) || $project->group->supervisor_id === $user->id;
        }
        if (!$canReject) {
            abort(403, 'ليس لديك صلاحية لرفض المشاريع');
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:3'],
        ], [
            'rejection_reason.required' => 'سبب الرفض مطلوب',
            'rejection_reason.min' => 'يجب أن يكون سبب الرفض 3 حروف على الأقل',
        ]);

        $wasProposal = $project->isProposal();

        $project->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'approved_at' => null,
            'supervisor_notes' => null,
            'archived_at' => now(),
            'last_archived_at' => now(),
            'last_archived_by' => $user->id,
        ]);

        if ($project->group && $project->owner) {
            $this->notificationService->notifyProjectStatusChange($project, 'rejected', $user);
        }

        $msg = $wasProposal
            ? 'تم رفض اقتراح المشروع. المشروع محفوظ في المؤرشفـات.'
            : 'تم رفض المشروع بنجاح. المشروع محفوظ في المؤرشفـات ولم يتم حذفه.';

        return redirect()->route('projects.index', ['filter' => 'archived'])->with('success', $msg);
    }

    /**
     * Set phases and delivery dates for a project (for supervisor)
     */
    public function setPhases(Request $request, Project $project)
    {
        $user = Auth::user();
        
        // Check permissions - Only supervisor can set phases
        $canSetPhases = $user->hasRole('admin') || $user->hasRole('department_admin');
        if (!$canSetPhases && $project->group) {
            $canSetPhases = $project->group->supervisor_id === $user->id || $project->group->isManager($user);
        }
        
        if (!$canSetPhases) {
            abort(403, 'ليس لديك صلاحية لتحديد جزئيات المشروع');
        }
        if ($project->isProposal()) {
            abort(403, 'يجب قبول اقتراح المشروع أولاً قبل تحديد الأجزاء.');
        }
        
        $validated = $request->validate([
            'phases_count' => ['required', 'integer', 'min:1', 'max:20'],
            'phases' => ['required', 'array', 'min:1'],
            'phases.*.title' => ['required', 'string', 'max:255'],
            'phases.*.delivery_date' => ['required', 'date', 'after_or_equal:today'],
            'phases.*.percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $totalPercentage = collect($validated['phases'])->sum(fn ($p) => (float) ($p['percentage'] ?? 0));
        if ($totalPercentage > 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'مجموع نسب الأجزاء (' . number_format($totalPercentage, 1) . '%) يتجاوز الحد الأقصى 100%.');
        }
        
        // Update project with phases count
        $project->update([
            'phases_count' => $validated['phases_count'],
        ]);
        
        // Create or update phases
        foreach ($validated['phases'] as $index => $phaseData) {
            $phaseNumber = $index + 1;
            
            ProjectPhase::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'phase_number' => $phaseNumber,
                ],
                [
                    'title' => $phaseData['title'],
                    'delivery_date' => $phaseData['delivery_date'],
                    'percentage' => $phaseData['percentage'] ?? null,
                    'status' => 'pending',
                ]
            );
        }
        
        // Delete phases that exceed the new count
        $project->phases()->where('phase_number', '>', $validated['phases_count'])->delete();
        
        // Send notification to students
        if ($project->group && $project->group->students) {
            foreach ($project->group->students as $student) {
                $this->notificationService->create(
                    $student,
                    'phases_set',
                    $project,
                    'تم تحديد جزئيات المشروع',
                    "تم تحديد {$validated['phases_count']} جزئية للمشروع '{$project->title}' من قبل المشرف",
                    [
                        'project_id' => $project->id,
                        'phases_count' => $validated['phases_count'],
                    ]
                );
            }
        }
        
        return redirect()->back()->with('success', "تم تحديد {$validated['phases_count']} جزئية للمشروع بنجاح.");
    }

    /**
     * Archive project and send to committee (for supervisor)
     */
    public function archiveAndSendToCommittee(Request $request, Project $project)
    {
        $user = Auth::user();
        
        // Check permissions - Only supervisor can archive and send to committee
        $canArchive = false;
        if ($project->group) {
            $canArchive = $project->group->supervisor_id === $user->id ||
                         $project->group->isManager($user) ||
                         $user->hasRole('admin');
        }
        
        if (!$canArchive) {
            abort(403, 'ليس لديك صلاحية لأرشفة المشروع وإرساله للجنة');
        }
        
        // Get the latest archived version (the last phase that was archived)
        $latestPhase = $project->phases()
            ->whereNotNull('archived_at')
            ->orderBy('archived_at', 'desc')
            ->first();
        
        if (!$latestPhase) {
            return redirect()->back()->with('error', 'لا توجد نسخة أرشيفية للمشروع. يرجى أرشفة جزئية على الأقل أولاً.');
        }
        
        // Archive the project
        $project->update([
            'archived_at' => now(),
            'last_archived_at' => now(),
            'last_archived_by' => $user->id,
            'status' => 'archived',
        ]);
        
        // Send notification to committee members
        $committeeMembers = \App\Models\User::role('committee')->get();
        
        foreach ($committeeMembers as $committeeMember) {
            $this->notificationService->create(
                $committeeMember,
                'project_sent_to_committee',
                $project,
                'تم إرسال مشروع للجنة التقييم',
                "تم إرسال المشروع '{$project->title}' من قبل المشرف {$user->name} للجنة التقييم",
                [
                    'project_id' => $project->id,
                    'supervisor_id' => $user->id,
                    'latest_phase_id' => $latestPhase->id,
                    'archived_at' => now(),
                ]
            );
        }
        
        return redirect()->back()->with('success', 'تم أرشفة المشروع وإرساله للجنة التقييم بنجاح.');
    }

    /**
     * Generate PDF for similarity check reports
     */
    public function generateSimilarityPDF(Project $project)
    {
        $project->load('group.students', 'similarityChecks');
        $similarityChecks = $project->similarityChecks()->orderBy('checked_at', 'desc')->get();

        $html = view('projects.similarity-pdf', [
            'project' => $project,
            'similarityChecks' => $similarityChecks,
        ])->render();

        $pdfContent = Gpdf::generate($html);
        $filename = 'similarity-report-' . $project->id . '-' . now()->format('Y-m-d') . '.pdf';

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Display library of all archived projects (read-only for students)
     */
    public function library(Request $request)
    {
        $user = Auth::user();
        
        // Only students can access the library
        if (!$user->hasRole('student')) {
            abort(403, 'فقط الطلاب يمكنهم الوصول إلى مكتبة المشاريع');
        }

        // Get all archived projects with their relations
        $query = Project::archived()
            ->where('status', '!=', 'rejected')
            ->with(['group.students', 'phases', 'evaluations', 'supervisorEvaluations'])
            ->orderBy('archived_at', 'desc');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('objectives', 'like', "%{$search}%")
                  ->orWhere('technologies', 'like', "%{$search}%")
                  ->orWhereHas('group', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('students', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                  });
            });
        }

        // Filter by department (optional)
        if ($request->has('department') && $request->department) {
            $query->whereHas('group', function($q) use ($request) {
                $q->whereHas('students', function($q) use ($request) {
                    $q->where('department', $request->department);
                });
            });
        }

        $projects = $query->paginate(12);

        // Get unique departments for filter
        $departments = Project::archived()
            ->where('status', '!=', 'rejected')
            ->whereHas('group.students')
            ->get()
            ->flatMap(function($project) {
                return $project->group->students->pluck('department');
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('projects.library', compact('projects', 'departments'));
    }
}
