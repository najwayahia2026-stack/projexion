<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectPhase;
use App\Models\PhaseFile;
use App\Services\FileSimilarityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectPhaseController extends Controller
{
    protected FileSimilarityService $fileSimilarityService;

    public function __construct(FileSimilarityService $fileSimilarityService)
    {
        $this->fileSimilarityService = $fileSimilarityService;
    }

    public function index(Project $project)
    {
        $phases = $project->phases()->with('files')->orderBy('phase_number')->get();
        
        return view('project-phases.index', compact('project', 'phases'));
    }

    public function create(Project $project)
    {
        if ($project->isArchived()) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط ولا يمكن إضافة أجزاء جديدة.');
        }
        if ($project->isProposal()) {
            abort(403, 'يجب الموافقة على اقتراح المشروع من قبل المشرف قبل إضافة أي جزء.');
        }
        if ($project->total_phases_percentage >= 100) {
            return redirect()->route('project-phases.index', $project)
                ->with('error', 'بلغت نسبة المشروع الحد الأقصى 100% ولا يمكن إضافة أجزاء جديدة.');
        }

        $currentTotalPercentage = (float) $project->phases()->sum('percentage');
        $remainingPercentage = 100 - $currentTotalPercentage;

        return view('project-phases.create', compact('project', 'currentTotalPercentage', 'remainingPercentage'));
    }

    public function store(Request $request, Project $project)
    {
        // فحص التشابه (استخراج النص + خدمة AI) قد يستغرق وقتاً طويلاً مع الملفات الكبيرة
        set_time_limit(600); // 10 دقائق

        if ($project->isArchived()) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط ولا يمكن إضافة أجزاء جديدة.');
        }
        if ($project->isProposal()) {
            abort(403, 'يجب الموافقة على اقتراح المشروع من قبل المشرف قبل إضافة أي جزء.');
        }
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'file' => ['required', 'file', 'mimes:doc,docx,pdf', 'max:10240'], // Max 10MB
        ]);

        $currentTotal = (float) $project->phases()->sum('percentage');
        if ($currentTotal + (float) $validated['percentage'] > 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'لا يمكن إضافة جزء جديد. النسبة الحالية ' . number_format($currentTotal, 1) . '% وإضافة ' . number_format($validated['percentage'], 1) . '% تتجاوز الحد الأقصى 100%.');
        }

        // Calculate phase number automatically based on creation order
        $maxPhaseNumber = $project->phases()->max('phase_number') ?? 0;
        $phaseNumber = $maxPhaseNumber + 1;

        // Create phase
        $phase = ProjectPhase::create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'phase_number' => $phaseNumber,
            'percentage' => $validated['percentage'],
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // Upload file
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('phase-files', $fileName, 'public');

        // Determine file type
        $fileType = in_array($file->getClientOriginalExtension(), ['doc', 'docx']) ? 'word' : 'pdf';

        // Create phase file record
        $phaseFile = PhaseFile::create([
            'phase_id' => $phase->id,
            'uploaded_by' => Auth::id(),
            'name' => $fileName,
            'original_name' => $file->getClientOriginalName(),
            'path' => $filePath,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'file_type' => $fileType,
            'status' => 'pending',
        ]);

        // Check similarity automatically
        try {
            $similarityResult = $this->fileSimilarityService->checkFileSimilarity($phaseFile);

            if ($similarityResult['rejected'] ?? false) {
                return redirect()->back()
                    ->with('error', $similarityResult['message'] ?? $similarityResult['error'] ?? 'تم رفض الملف بسبب نسبة التشابه العالية')
                    ->with('warning', $similarityResult['warning'] ?? null)
                    ->with('help', $similarityResult['help'] ?? null)
                    ->withInput();
            }

            // If similarity check failed but file wasn't rejected, show warning
            if (!($similarityResult['success'] ?? true)) {
                $errorMessage = $similarityResult['message'] ?? $similarityResult['error'] ?? 'تم رفع الجزء بنجاح، لكن فحص التشابه لم يكتمل';
                return redirect()->route('project-phases.show', $phase)
                    ->with('warning', $errorMessage)
                    ->with('error_details', $similarityResult['error_details'] ?? null);
            }

            return redirect()->route('project-phases.show', $phase)
                ->with('success', $similarityResult['message'] ?? 'تم رفع الجزء بنجاح')
                ->with('warning', $similarityResult['warning'] ?? null);
        } catch (\Exception $e) {
            \Log::error('Similarity check exception in controller: ' . $e->getMessage());
            return redirect()->route('project-phases.show', $phase)
                ->with('warning', 'تم رفع الجزء بنجاح، لكن فحص التشابه لم يكتمل. سيتم فحصه يدوياً لاحقاً.');
        }
    }

    public function show(ProjectPhase $phase)
    {
        $phase->load('project', 'files', 'reviewer');
        
        return view('project-phases.show', compact('phase'));
    }

    public function edit(ProjectPhase $phase)
    {
        if ($phase->project && $phase->project->isArchived()) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط ولا يمكن تعديل أجزائها.');
        }
        return view('project-phases.edit', compact('phase'));
    }

    public function update(Request $request, ProjectPhase $phase)
    {
        if ($phase->project && $phase->project->isArchived()) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط ولا يمكن تعديل أجزائها.');
        }
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $newPercentage = (float) ($validated['percentage'] ?? 0);
        $currentTotal = (float) $phase->project->phases()->sum('percentage');
        $oldPhasePercentage = (float) ($phase->percentage ?? 0);
        $newTotal = $currentTotal - $oldPhasePercentage + $newPercentage;

        if ($newTotal > 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'لا يمكن تحديث النسبة. المجموع سيتجاوز الحد الأقصى 100% (المجموع بعد التحديث: ' . number_format($newTotal, 1) . '%).');
        }

        $phase->update($validated);

        return redirect()->route('project-phases.index', $phase->project)
            ->with('success', 'تم تحديث الجزء بنجاح');
    }

    public function destroy(ProjectPhase $phase)
    {
        if ($phase->project && $phase->project->isArchived()) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط ولا يمكن حذف أجزائها.');
        }

        $project = $phase->project;

        // Delete associated files
        foreach ($phase->files as $file) {
            if ($file->path && Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
            $file->delete();
        }

        $phase->delete();

        return redirect()->route('project-phases.index', $project)
            ->with('success', 'تم حذف الجزء بنجاح');
    }

    public function approve(Request $request, ProjectPhase $phase)
    {
        if ($phase->project && $phase->project->isArchived()) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط ولا يمكن تعديل أجزائها.');
        }
        $phase->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Approve all files in this phase
        $phase->files()->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'تم قبول الجزء بنجاح');
    }

    public function reject(Request $request, ProjectPhase $phase)
    {
        if ($phase->project && $phase->project->isArchived()) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط ولا يمكن تعديل أجزائها.');
        }
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:3'],
        ], [
            'rejection_reason.required' => 'يجب إدخال سبب الرفض.',
            'rejection_reason.min' => 'يجب أن يكون سبب الرفض 3 حروف على الأقل.',
        ]);

        $phase->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Reject all files in this phase
        $phase->files()->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'تم رفض الجزء');
    }

    public function uploadFile(Request $request, ProjectPhase $phase)
    {
        if ($phase->project && $phase->project->isArchived()) {
            abort(403, 'المشاريع المؤرشفة للقراءة فقط ولا يمكن إضافة ملفات لأجزائها.');
        }
        if ($phase->project && $phase->project->isProposal()) {
            abort(403, 'يجب الموافقة على اقتراح المشروع من قبل المشرف قبل رفع الملفات.');
        }
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:doc,docx,pdf', 'max:10240'],
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('phase-files', $fileName, 'public');

        $fileType = in_array($file->getClientOriginalExtension(), ['doc', 'docx']) ? 'word' : 'pdf';

        $phaseFile = PhaseFile::create([
            'phase_id' => $phase->id,
            'uploaded_by' => Auth::id(),
            'name' => $fileName,
            'original_name' => $file->getClientOriginalName(),
            'path' => $filePath,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'file_type' => $fileType,
            'status' => 'pending',
        ]);

        // فحص التشابه قد يستغرق وقتاً مع الملفات الكبيرة
        set_time_limit(300);

        // Check similarity
        try {
            $similarityResult = $this->fileSimilarityService->checkFileSimilarity($phaseFile);

            if ($similarityResult['rejected'] ?? false) {
                return redirect()->back()
                    ->with('error', $similarityResult['message'] ?? $similarityResult['error'] ?? 'تم رفض الملف بسبب نسبة التشابه العالية')
                    ->with('warning', $similarityResult['warning'] ?? null);
            }

            // If similarity check failed but file wasn't rejected, show warning
            if (!($similarityResult['success'] ?? true)) {
                $errorMessage = $similarityResult['message'] ?? $similarityResult['error'] ?? 'تم رفع الملف بنجاح، لكن فحص التشابه لم يكتمل';
                return redirect()->back()
                    ->with('warning', $errorMessage)
                    ->with('error_details', $similarityResult['error_details'] ?? null);
            }

            return redirect()->back()
                ->with('success', $similarityResult['message'] ?? 'تم رفع الملف بنجاح')
                ->with('warning', $similarityResult['warning'] ?? null);
        } catch (\Exception $e) {
            \Log::error('Similarity check exception in uploadFile: ' . $e->getMessage());
            return redirect()->back()
                ->with('warning', 'تم رفع الملف بنجاح، لكن فحص التشابه لم يكتمل. سيتم فحصه يدوياً لاحقاً.');
        }
    }

    /**
     * تحميل ملف من قسم الملفات المرفوعة
     */
    public function downloadFile(PhaseFile $file)
    {
        $phase = $file->phase;
        if (!$phase || !$phase->project) {
            abort(404, 'الملف غير مرتبط بجزئية صالحة');
        }

        // التحقق من وجود الملف
        if (!Storage::disk('public')->exists($file->path)) {
            abort(404, 'الملف غير موجود');
        }

        return Storage::disk('public')->download(
            $file->path,
            $file->original_name,
            ['Content-Type' => $file->mime_type]
        );
    }

    public function checkSimilarity(PhaseFile $file)
    {
        $user = Auth::user();
        
        // Committee members can only check similarity on the latest archived version
        if ($user->hasRole('committee') && !$user->hasRole('admin')) {
            $phase = $file->phase;
            if (!$phase || !$phase->project) {
                abort(403, 'الملف غير مرتبط بجزئية مشروع');
            }
            
            $project = $phase->project;
            
            // Check if project has been archived and sent to committee
            if (!$project->last_archived_at || !$project->last_archived_by) {
                abort(403, 'لا يمكن فحص التشابه. المشروع لم يتم أرشفته وإرساله للجنة بعد.');
            }
            
            // Get the latest archived phase
            $latestPhase = $project->phases()
                ->whereNotNull('archived_at')
                ->orderBy('archived_at', 'desc')
                ->first();
            
            if (!$latestPhase || $latestPhase->id !== $phase->id) {
                abort(403, 'يمكنك فقط فحص التشابه على النسخة الأخيرة التي تم أرشفتها من المشرف.');
            }
            
            // Check if the phase is archived
            if (!$phase->archived_at) {
                abort(403, 'لا يمكن فحص التشابه. هذه الجزئية لم يتم أرشفتها بعد.');
            }
        }

        set_time_limit(300);
        
        try {
            $result = $this->fileSimilarityService->checkFileSimilarity($file);

            if ($result['rejected'] ?? false) {
                return redirect()->back()
                    ->with('error', $result['message'] ?? $result['error'] ?? 'تم رفض الملف')
                    ->with('warning', $result['warning'] ?? null)
                    ->with('help', $result['help'] ?? null)
                    ->with('error_details', $result['error_details'] ?? null);
            }

            // Handle both success and failure cases
            if ($result['success'] ?? false) {
                return redirect()->back()
                    ->with('success', $result['message'] ?? 'تم فحص الملف بنجاح')
                    ->with('warning', $result['warning'] ?? null)
                    ->with('help', $result['help'] ?? null);
            } else {
                // Similarity check failed
                return redirect()->back()
                    ->with('warning', $result['message'] ?? $result['error'] ?? 'فشل فحص التشابه. يرجى المحاولة مرة أخرى.')
                    ->with('error_details', $result['error_details'] ?? null)
                    ->with('help', $result['help'] ?? null);
            }
        } catch (\Exception $e) {
            \Log::error('Similarity check exception: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء فحص التشابه: ' . $e->getMessage());
        }
    }

    /**
     * Check similarity for all files in a phase
     */
    public function checkPhaseSimilarity(ProjectPhase $phase)
    {
        $user = Auth::user();
        
        // Committee members can only check similarity on the latest archived version
        if ($user->hasRole('committee') && !$user->hasRole('admin')) {
            if (!$phase->project) {
                abort(403, 'الجزئية غير مرتبطة بمشروع');
            }
            
            $project = $phase->project;
            
            // Check if project has been archived and sent to committee
            if (!$project->last_archived_at || !$project->last_archived_by) {
                abort(403, 'لا يمكن فحص التشابه. المشروع لم يتم أرشفته وإرساله للجنة بعد.');
            }
            
            // Get the latest archived phase
            $latestPhase = $project->phases()
                ->whereNotNull('archived_at')
                ->orderBy('archived_at', 'desc')
                ->first();
            
            if (!$latestPhase || $latestPhase->id !== $phase->id) {
                abort(403, 'يمكنك فقط فحص التشابه على النسخة الأخيرة التي تم أرشفتها من المشرف.');
            }
            
            // Check if the phase is archived
            if (!$phase->archived_at) {
                abort(403, 'لا يمكن فحص التشابه. هذه الجزئية لم يتم أرشفتها بعد.');
            }
        }

        set_time_limit(600);
        
        try {
            if ($phase->files->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'لا توجد ملفات في هذا الجزء لفحصها');
            }

            $checkedCount = 0;
            $totalSimilarity = 0;
            $maxSimilarity = 0;
            $maxAiProbability = null;
            $filesWithAi = 0;

            foreach ($phase->files as $file) {
                if ($file->status === 'pending' || $file->status === 'checked') {
                    $result = $this->fileSimilarityService->checkFileSimilarity($file);
                    
                    if ($result['success'] ?? false) {
                        $checkedCount++;
                        $fileSimilarity = $result['similarity_score'] ?? 0;
                        $fileAiProbability = $result['ai_probability'] ?? null;
                        
                        $totalSimilarity += $fileSimilarity;
                        if ($fileSimilarity > $maxSimilarity) {
                            $maxSimilarity = $fileSimilarity;
                        }
                        
                        if ($fileAiProbability !== null) {
                            if ($maxAiProbability === null || $fileAiProbability > $maxAiProbability) {
                                $maxAiProbability = $fileAiProbability;
                            }
                            if ($fileAiProbability >= 50) {
                                $filesWithAi++;
                            }
                        }
                    }
                }
            }

            // Update phase similarity score with the maximum similarity found
            if ($maxSimilarity > 0) {
                $phase->update(['similarity_score' => $maxSimilarity]);
            }

            if ($checkedCount > 0) {
                // Create beautiful success message with AI information
                $successData = [
                    'files_count' => $checkedCount,
                    'max_similarity' => number_format($maxSimilarity, 2),
                    'max_ai_probability' => $maxAiProbability !== null ? number_format($maxAiProbability, 2) : null,
                    'files_with_ai' => $filesWithAi,
                ];
                
                return redirect()->back()
                    ->with('success', "تم فحص {$checkedCount} ملف بنجاح")
                    ->with('similarity_data', $successData);
            } else {
                return redirect()->back()
                    ->with('warning', 'لم يتم فحص أي ملف. قد تكون جميع الملفات معتمدة أو مرفوضة مسبقاً.');
            }
        } catch (\Exception $e) {
            \Log::error('Phase similarity check exception: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء فحص التشابه: ' . $e->getMessage());
        }
    }
}
