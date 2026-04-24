<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = [
        'name',
        'code',
        'supervisor_id',
        'academic_year',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'academic_year' => 'integer',
        ];
    }

    // Relationships
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function deadlines(): HasMany
    {
        return $this->hasMany(Deadline::class);
    }

    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_managers');
    }

    public function joinRequests(): HasMany
    {
        return $this->hasMany(GroupJoinRequest::class);
    }

    public function pendingJoinRequests(): HasMany
    {
        return $this->hasMany(GroupJoinRequest::class)->where('status', 'pending');
    }

    // Helper Methods
    public function allMembers()
    {
        $members = collect();
        
        // Add supervisor
        if ($this->supervisor) {
            $members->push($this->supervisor);
        }
        
        // Add managers
        $managers = $this->managers;
        foreach ($managers as $manager) {
            $members->push($manager);
        }
        
        // Add students
        $students = $this->students;
        foreach ($students as $student) {
            $members->push($student);
        }
        
        return $members->unique('id');
    }

    public function isManager(User $user): bool
    {
        return $this->supervisor_id === $user->id || $this->managers->contains($user->id);
        return $this->managers->contains($user->id);
    }

    public function getUserRoleInGroup(User $user): ?string
    {
        if ($this->supervisor_id === $user->id) {
            return 'supervisor';
        }
        
        if ($this->managers->contains($user->id)) {
            return 'supervisor'; // Managers now have supervisor role
        }
        
        if ($this->students->contains($user->id)) {
            return 'student';
        }
        
        return null;
         if ($this->supervisor_id == $user->id) {
        return 'supervisor';
    }
    
    // إذا كان ضمن المديرين (Managers) يعتبر مشرفاً أيضاً في العرض
    if ($this->managers->contains($user->id)) {
        return 'supervisor';
    }

    return 'student';
    }
    // app/Models/Group.php


public function users()
{
    // استخدم belongsToMany بدلاً من hasMany
    return $this->belongsToMany(User::class);
}
public function specialty() {
    return $this->belongsTo(Specialty::class, 'specialty_id');
}

}
