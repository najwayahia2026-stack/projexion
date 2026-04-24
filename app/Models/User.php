<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'student_id',
        'phone',
        'department',
        'specialty_id',
        'supervisor_id',
        'pool_supervisor_id',
        'graduation_year',
        'bio',
        'avatar',
        'banned_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'banned_at' => 'datetime',
            'password' => 'hashed',
            'graduation_year' => 'integer',
        ];
    }

    // Helper Methods
    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }

    public function ban(): void
    {
        $this->update(['banned_at' => now()]);
    }

    public function unban(): void
    {
        $this->update(['banned_at' => null]);
    }

    // Relationships
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function supervisedGroups()
    {
        return $this->hasMany(Group::class, 'supervisor_id');
    }

    // Helper method to get projects through groups
    public function getProjects()
    {
        return Project::whereHas('group', function($query) {
            $query->whereHas('students', function($q) {
                $q->where('users.id', $this->id);
            });
        })->with('group')->get();
    }

    public function projectReports()
    {
        return $this->hasMany(ProjectReport::class);
    }

    public function projectFiles()
    {
        return $this->hasMany(ProjectFile::class, 'uploaded_by');
    }

    public function projectNotes()
    {
        return $this->hasMany(ProjectNote::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'evaluator_id');
    }

    public function supervisorEvaluations()
    {
        return $this->hasMany(SupervisorEvaluation::class, 'supervisor_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function managedGroups()
    {
        return $this->belongsToMany(Group::class, 'group_managers');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }
    // داخل ملف User.php
public function scopeAvailableStudents($query, $specialtyId)
{
    return $query->where('role', 'student')
                 ->where('specialty_id', $specialtyId) // طلاب نفس تخصص المشرف
                 ->whereNull('supervisor_id'); // غير محجوزين لمشرف آخر
}
}
