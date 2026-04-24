<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProjectReportController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ProjectPhaseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\PoolController;


// Public Routes - Home page (visible without login)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('home');
})->name('home');

// Specialty search (for registration and user forms)
Route::get('/specialties/search', [SpecialtyController::class, 'search'])->name('specialties.search');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/admin', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin', [AuthController::class, 'adminLogin'])->name('admin-login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('quick-login');
});

// Authenticated Routes (ensure.not.banned kicks out banned users on each request)
Route::middleware(['auth', 'ensure.not.banned'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reports (تقارير المشاريع) - غير متاحة للطلاب
    Route::middleware('role:admin|department_admin|supervisor|committee')->group(function () {
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    });
    
    // Profile Management (All authenticated users)
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // User profile view (for supervisors to view student details) - Must be before specific routes
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    
    // Users Management (Admin and Department Admin)
    Route::middleware('role:admin|department_admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
        Route::post('/users/{user}/unban', [UserController::class, 'unban'])->name('users.unban');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
    
    // Groups
    Route::resource('groups', GroupController::class);
    Route::post('/groups/join', [GroupController::class, 'join'])->name('groups.join');
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    Route::post('/groups/{group}/add-student', [GroupController::class, 'addStudent'])->name('groups.add-student');
    Route::post('/groups/{group}/add-manager', [GroupController::class, 'addManager'])->name('groups.add-manager');
    Route::post('/groups/{group}/add-user', [GroupController::class, 'addUser'])->name('groups.add-user');
    Route::get('/groups/{group}/members', [GroupController::class, 'members'])->name('groups.members');
    Route::delete('/groups/{group}/members/{user}', [GroupController::class, 'removeMember'])->name('groups.remove-member');
    Route::post('/group-join-requests/{joinRequest}/approve', [GroupController::class, 'approveJoinRequest'])->name('group-join-requests.approve');
    Route::post('/group-join-requests/{joinRequest}/reject', [GroupController::class, 'rejectJoinRequest'])->name('group-join-requests.reject');
    
    // Projects
    Route::resource('projects', ProjectController::class);
    Route::get('/projects-library', [ProjectController::class, 'library'])->name('projects.library');
    Route::post('/projects/{project}/approve', [ProjectController::class, 'approve'])->name('projects.approve');
    Route::post('/projects/{project}/approve-proposal', [ProjectController::class, 'approveProposal'])->name('projects.approve-proposal');
    Route::post('/projects/{project}/reject', [ProjectController::class, 'reject'])->name('projects.reject');
    Route::post('/projects/{project}/set-phases', [ProjectController::class, 'setPhases'])->name('projects.set-phases');
    Route::post('/projects/{project}/archive-and-send-to-committee', [ProjectController::class, 'archiveAndSendToCommittee'])->name('projects.archive-and-send-to-committee');
    Route::post('/projects/{project}/check-similarity', [ProjectController::class, 'checkSimilarity'])->name('projects.check-similarity');
    Route::post('/projects/{project}/check-all-phases-similarity', [ProjectController::class, 'checkAllPhasesSimilarity'])->name('projects.check-all-phases-similarity');
    Route::get('/projects/{project}/similarity-pdf', [ProjectController::class, 'generateSimilarityPDF'])->name('projects.similarity-pdf');
    
    // Project Sections
    Route::post('/projects/{project}/sections', [ProjectController::class, 'storeSection'])->name('projects.sections.store');
    Route::put('/projects/{project}/sections/{section}', [ProjectController::class, 'updateSection'])->name('projects.sections.update');
    Route::delete('/projects/{project}/sections/{section}', [ProjectController::class, 'destroySection'])->name('projects.sections.destroy');
    Route::post('/projects/{project}/sections/{section}/upload-file', [ProjectController::class, 'uploadFileToSection'])->name('projects.sections.upload-file');
    Route::post('/projects/{project}/sections/{section}/approve', [ProjectController::class, 'approveSection'])->name('projects.sections.approve');
    Route::post('/projects/{project}/sections/{section}/reject', [ProjectController::class, 'rejectSection'])->name('projects.sections.reject');
    
    // Project Reports
    //Route::resource('project-reports', ProjectReportController::class);
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/list', [NotificationController::class, 'getNotifications'])->name('notifications.list');
    Route::post('/notifications/send', [NotificationController::class, 'send'])->name('notifications.send');
    Route::get('/notifications/{notification}/attachment', [NotificationController::class, 'downloadAttachment'])->name('notifications.attachment-download');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Evaluations
    // Exclude 'create' and 'store' from resource route since we have custom routes that require project parameter
    Route::resource('evaluations', EvaluationController::class)->except(['create', 'store']);
    Route::get('/projects/{project}/evaluation', [EvaluationController::class, 'create'])->name('evaluations.create');
    Route::post('/projects/{project}/evaluation', [EvaluationController::class, 'store'])->name('evaluations.store');
    Route::get('/projects/{project}/evaluation/pdf', [EvaluationController::class, 'generatePDF'])->name('evaluations.pdf');
    
    // Supervisor Evaluations (نظام تقييم منفصل للمشرف)
    Route::resource('supervisor-evaluations', \App\Http\Controllers\SupervisorEvaluationController::class)->except(['create', 'store']);
    Route::get('/projects/{project}/supervisor-evaluation', [\App\Http\Controllers\SupervisorEvaluationController::class, 'create'])->name('supervisor-evaluations.create');
    Route::post('/projects/{project}/supervisor-evaluation', [\App\Http\Controllers\SupervisorEvaluationController::class, 'store'])->name('supervisor-evaluations.store');
    
    // Project Reports
    Route::get('/projects/{project}/reports', [ProjectReportController::class, 'index'])->name('project-reports.index');
    Route::post('/projects/{project}/reports', [ProjectReportController::class, 'store'])->name('project-reports.store');
    
    // Project Phases
    Route::get('/projects/{project}/phases', [ProjectPhaseController::class, 'index'])->name('project-phases.index');
    Route::get('/projects/{project}/phases/create', [ProjectPhaseController::class, 'create'])->name('project-phases.create');
    Route::post('/projects/{project}/phases', [ProjectPhaseController::class, 'store'])->name('project-phases.store');
    
    // Legacy route redirects for backward compatibility
    Route::get('/project-phases/create', function (\Illuminate\Http\Request $request) {
        // Try to get project ID from various query parameters
        $projectId = $request->query('project') 
            ?? $request->query('id');
        
        // If not found, check if any query key is numeric (like ?9= or ?9)
        if (!$projectId && count($request->query()) > 0) {
            $keys = array_keys($request->query());
            foreach ($keys as $key) {
                if (is_numeric($key)) {
                    $projectId = $key;
                    break;
                }
            }
        }
        
        // If still not found, check the raw query string for patterns like ?9
        if (!$projectId) {
            $queryString = $request->getQueryString();
            if ($queryString) {
                // Parse query string manually to handle cases like ?9
                parse_str($queryString, $parsed);
                foreach ($parsed as $key => $value) {
                    if (is_numeric($key) && empty($value)) {
                        $projectId = $key;
                        break;
                    }
                    if (is_numeric($value)) {
                        $projectId = $value;
                        break;
                    }
                }
                
                // If query string is just a number (like ?9), extract it
                if (!$projectId && preg_match('/^(\d+)$/', $queryString, $matches)) {
                    $projectId = $matches[1];
                }
            }
        }
        
        if ($projectId) {
            return redirect()->route('project-phases.create', ['project' => $projectId]);
        }
        
        // If no project ID found, redirect to projects index
        return redirect()->route('projects.index')
            ->with('error', 'يرجى اختيار مشروع أولاً لإضافة جزء جديد');
    });
    Route::get('/project-phases/{phase}', [ProjectPhaseController::class, 'show'])->name('project-phases.show');
    Route::get('/project-phases/{phase}/edit', [ProjectPhaseController::class, 'edit'])->name('project-phases.edit');
    Route::put('/project-phases/{phase}', [ProjectPhaseController::class, 'update'])->name('project-phases.update');
    Route::delete('/project-phases/{phase}', [ProjectPhaseController::class, 'destroy'])->name('project-phases.destroy');
    Route::post('/project-phases/{phase}/approve', [ProjectPhaseController::class, 'approve'])->name('project-phases.approve');
    Route::post('/project-phases/{phase}/reject', [ProjectPhaseController::class, 'reject'])->name('project-phases.reject');
    Route::post('/project-phases/{phase}/upload-file', [ProjectPhaseController::class, 'uploadFile'])->name('project-phases.upload-file');
    Route::post('/project-phases/{phase}/check-similarity', [ProjectPhaseController::class, 'checkPhaseSimilarity'])->name('project-phases.check-similarity');
    Route::get('/phase-files/{file}/download', [ProjectPhaseController::class, 'downloadFile'])->name('phase-files.download');
    Route::post('/phase-files/{file}/check-similarity', [ProjectPhaseController::class, 'checkSimilarity'])->name('phase-files.check-similarity');
});
Route::middleware(['auth'])->group(function () {
    // صفحة عرض الطلاب المتاحين
    Route::get('/supervisor/students/available', [SupervisorController::class, 'getAvailableStudents'])->name('supervisor.students.available');
    
    // رابط إضافة طالب
    Route::post('/supervisor/students/assign/{id}', [SupervisorController::class, 'assignStudent'])->name('supervisor.students.assign');
    
    // رابط حذف طالب
    Route::post('/supervisor/students/remove/{id}', [SupervisorController::class, 'removeStudent'])->name('supervisor.students.remove');
});

Route::middleware(['auth', 'role:supervisor'])->group(function () {
    
    // 1. عرض الصفحات (لوحة تحكم الحوض)
    Route::get('/pool', [PoolController::class, 'index'])->name('pool.index');
    
    // مسارات حوض الطلاب
Route::post('/pool/add/{student}', [GroupController::class, 'addToPool'])->name('students.addToPool');
Route::post('/pool/remove/{student}', [GroupController::class, 'removeFromPool'])->name('students.removeFromPool');
    // إذا كنت لا تزال تستخدم هذه المسارات لصفحات منفصلة:
    Route::get('/students-search', [PoolController::class, 'search'])->name('students.search');
    Route::get('/my-pool', [PoolController::class, 'myPool'])->name('students.pool');
    // مسارات قبول ورفض طلبات الانضمام
Route::post('/groups/{group}/accept/{request_id}', [App\Http\Controllers\GroupController::class, 'acceptStudent'])
    ->name('groups.accept-student');

Route::post('/groups/requests/{request_id}/reject', [App\Http\Controllers\GroupController::class, 'rejectStudent'])
    ->name('groups.reject-student');
    // مسار تقرير المشرف
Route::get('/supervisor/report/{id}', [App\Http\Controllers\GroupController::class, 'supervisorReport'])
    ->name('supervisor.report')
    ->middleware(['auth']);
   // هذا المسار هو الذي يبحث عنه الزر في صفحة المجموعات
Route::get('/supervisor/report/{id}', [App\Http\Controllers\ReportsController::class, 'printSupervisorReport'])
    ->name('supervisor.report');
});