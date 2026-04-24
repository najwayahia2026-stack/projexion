<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Group;
use App\Models\Deadline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->hasRole('admin') || $user->hasRole('department_admin')) {
            $data = $this->adminDashboard($user);
        } elseif ($user->hasRole('student')) {
            $data = $this->studentDashboard($user);
        } elseif ($user->hasRole('supervisor')) {
            $data = $this->supervisorDashboard($user);
        } elseif ($user->hasRole('committee')) {
            $data = $this->committeeDashboard($user);
        }

        return view('dashboard.index', $data);
    }

    private function studentDashboard($user)
    {
        $groups = $user->groups()->with('projects')->get();
        $projects = Project::whereHas('group', function ($query) use ($user) {
            $query->whereHas('students', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        })->get();

        $upcomingDeadlines = Deadline::whereHas('group', function ($query) use ($user) {
            $query->whereHas('students', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        })->where('deadline_date', '>=', now())
            ->orderBy('deadline_date')
            ->limit(5)
            ->get();

        return [
            'user' => $user,
            'groups' => $groups,
            'projects' => $projects,
            'upcomingDeadlines' => $upcomingDeadlines,
            'stats' => [
                'total_projects' => $projects->count(),
                'active_projects' => $projects->where('status', 'in_progress')->count(),
                'completed_projects' => $projects->where('status', 'completed')->count(),
            ],
        ];
    }

    private function supervisorDashboard($user)
    {
        $groups = $user->supervisedGroups()->with('students', 'projects')->get();
        $projects = Project::whereHas('group', function ($query) use ($user) {
            $query->where('supervisor_id', $user->id);
        })->get();

        $pendingProjects = $projects->where('status', 'pending');
        $inProgressProjects = $projects->where('status', 'in_progress');

        return [
            'user' => $user,
            'groups' => $groups,
            'projects' => $projects,
            'pendingProjects' => $pendingProjects,
            'inProgressProjects' => $inProgressProjects,
            'stats' => [
                'total_groups' => $groups->count(),
                'total_projects' => $projects->count(),
                'pending_reviews' => $pendingProjects->count(),
                'active_projects' => $inProgressProjects->count(),
            ],
        ];
    }

    private function adminDashboard($user)
    {
        $totalProjects = Project::count();
        $totalGroups = Group::count();
        $totalStudents = \App\Models\User::role('student')->count();
        $totalSupervisors = \App\Models\User::role('supervisor')->count();

        $projectsByStatus = Project::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'user' => $user,
            'stats' => [
                'total_projects' => $totalProjects,
                'total_groups' => $totalGroups,
                'total_students' => $totalStudents,
                'total_supervisors' => $totalSupervisors,
            ],
            'projectsByStatus' => $projectsByStatus,
        ];
    }

    private function committeeDashboard($user)
    {
        // Committee only sees archived projects matching their specialty
        $query = Project::archived()
            ->where('status', '!=', 'rejected')
            ->with('group', 'evaluations');

        if ($user->specialty_id) {
            $query->where('specialty_id', $user->specialty_id);
        }

        $projects = $query->get();

        $pendingEvaluations = $projects->filter(function ($project) use ($user) {
            return $project->evaluations->where('evaluator_id', $user->id)->isEmpty();
        });

        return [
            'user' => $user,
            'projects' => $projects,
            'pendingEvaluations' => $pendingEvaluations,
            'stats' => [
                'total_projects' => $projects->count(),
                'pending_evaluations' => $pendingEvaluations->count(),
            ],
        ];
    }
}
