<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of all users (Admin only)
     */
    public function index(Request $request)
    {
        // Only admin and department_admin can access
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('department_admin')) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        }

        $query = User::with('roles');

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->has('role') && $request->role) {
            $query->role($request->role);
        }

        // Status filter (banned/unbanned)
        if ($request->has('status') && $request->status) {
            if ($request->status === 'banned') {
                $query->whereNotNull('banned_at');
            } elseif ($request->status === 'active') {
                $query->whereNull('banned_at');
            }
        }

        $users = $query->latest()->paginate(20);
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        // Load user relationships
        $user->load(['groups', 'supervisedGroups', 'evaluations.project', 'supervisorEvaluations.project']);
        
        // Get projects through groups
        $projects = Project::whereHas('group', function($query) use ($user) {
            $query->whereHas('students', function($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        })->with('group')->get();
        
        return view('users.show', compact('user', 'projects'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        // Only admin and department_admin can access
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('department_admin')) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        }

        $roles = Role::all();
        $specialties = \App\Models\Specialty::orderBy('name')->get();
        return view('users.edit', compact('user', 'roles', 'specialties'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        // Only admin and department_admin can access
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('department_admin')) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'student_id' => ['nullable', 'string', 'max:255', 'unique:users,student_id,' . $user->id],
            'phone' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'specialty_id' => ['required_if:role,committee', 'nullable', 'exists:specialties,id'],
            'graduation_year' => ['nullable', 'integer', 'min:2020', 'max:2100'],
            'bio' => ['nullable', 'string'],
            'role' => ['required', 'exists:roles,name'],
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
            'username.unique' => 'اسم المستخدم مستخدم بالفعل',
            'student_id.unique' => 'رقم الطالب مستخدم بالفعل',
            'role.required' => 'يجب اختيار الدور',
        ]);

        // Update user data
        $userData = [
            'name' => $validated['name'],
            'username' => $validated['username'] ?? null,
            'email' => $validated['email'],
            'student_id' => $validated['student_id'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'department' => $validated['department'] ?? null,
            'graduation_year' => $validated['graduation_year'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ];
        if ($validated['role'] === 'committee') {
            $userData['specialty_id'] = $validated['specialty_id'] ?? null;
        } else {
            $userData['specialty_id'] = null;
        }

        // Update password if provided
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        // Update role
        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')
            ->with('success', 'تم تحديث معلومات المستخدم بنجاح');
    }

    /**
     * Ban a user
     */
    public function ban(User $user)
    {
        // Only admin and department_admin can access
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('department_admin')) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        }

        // Cannot ban yourself
        if ($user->id === Auth::id()) {
            return back()->with('error', 'لا يمكنك حظر نفسك');
        }

        // Cannot ban another admin
        if ($user->hasRole('admin')) {
            return back()->with('error', 'لا يمكنك حظر مدير آخر');
        }

        $user->ban();

        return back()->with('success', 'تم حظر المستخدم بنجاح');
    }

    /**
     * Unban a user
     */
    public function unban(User $user)
    {
        // Only admin and department_admin can access
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('department_admin')) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        }

        $user->unban();

        return back()->with('success', 'تم إلغاء حظر المستخدم بنجاح');
    }

    /**
     * Delete a user
     */
    public function destroy(User $user)
    {
        // Only admin and department_admin can access
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('department_admin')) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        }

        // Cannot delete yourself
        if ($user->id === Auth::id()) {
            return back()->with('error', 'لا يمكنك حذف نفسك');
        }

        // Cannot delete another admin
        if ($user->hasRole('admin')) {
            return back()->with('error', 'لا يمكنك حذف مدير آخر');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'تم حذف المستخدم "' . $userName . '" بنجاح');
    }
}
