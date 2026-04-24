<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'objectives',
        'technologies',
        'group_id',
        'specialty_id',
        'status',
        'progress_percentage',
        'supervisor_notes',
        'approved_at',
        'rejected_at',
        'rejection_reason',
        'similarity_score',
        'archived_at',
        'phases_count',
        'phases_delivery_dates',
        'last_archived_at',
        'last_archived_by',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'archived_at' => 'datetime',
            'last_archived_at' => 'datetime',
            'progress_percentage' => 'integer',
            'similarity_score' => 'decimal:2',
            'phases_count' => 'integer',
            'phases_delivery_dates' => 'array',
        ];
    }

    // Relationships
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ProjectReport::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ProjectNote::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function supervisorEvaluations(): HasMany
    {
        return $this->hasMany(SupervisorEvaluation::class);
    }

    public function deadlines(): HasMany
    {
        return $this->hasMany(Deadline::class);
    }

    public function similarityChecks(): HasMany
    {
        return $this->hasMany(SimilarityCheck::class);
    }

    public function phases(): HasMany
    {
        return $this->hasMany(ProjectPhase::class)->orderBy('created_at');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(ProjectSection::class)->orderBy('order_number');
    }

    // Scopes
    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public function scopeNotArchived($query)
    {
        return $query->whereNull('archived_at');
    }

    // Helper Methods
    public function isProposal(): bool
    {
        return $this->status === 'pending';
    }

    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }

    public function getProgressColorAttribute(): string
    {
        if ($this->progress_percentage >= 75) {
            return 'green';
        } elseif ($this->progress_percentage >= 50) {
            return 'yellow';
        } else {
            return 'red';
        }
    }

    /**
     * Calculate and update progress percentage based on approved sections
     */
    public function calculateProgress(): void
    {
        $sections = $this->sections;
        $totalPercentage = 0;
        
        foreach ($sections as $section) {
            if ($section->isApproved()) {
                $totalPercentage += $section->percentage;
            }
        }
        
        $this->update([
            'progress_percentage' => min(100, $totalPercentage)
        ]);
    }

    /**
     * Calculate total progress percentage from phases (allocated)
     */
    public function getTotalPhasesPercentageAttribute(): float
    {
        $totalPercentage = $this->phases()->sum('percentage');
        return min(100, (float) $totalPercentage);
    }

    /**
     * نسبة الأجزاء المعتمدة (للتأكد من اكتمال 100% قبل القبول النهائي)
     */
    public function getApprovedPhasesPercentageAttribute(): float
    {
        return (float) $this->phases()->where('status', 'approved')->sum('percentage');
    }

    /**
     * هل المشروع جاهز للقبول أو الرفض النهائي (اكتملت الموافقة على الأجزاء 100%)
     */
    public function isReadyForFinalApproval(): bool
    {
        $phases = $this->phases;
        if ($phases->isEmpty()) {
            return false;
        }
        return $this->approved_phases_percentage >= 100;
    }

    /**
     * Get the project owner (student from the group)
     * For projects, we consider all students in the group as owners
     */
    public function getOwnersAttribute()
    {
        return $this->group->students;
    }
    
    /**
     * Get the first owner for notification purposes
     */
    public function getOwnerAttribute()
    {
        return $this->group->students->first();
    }
}
