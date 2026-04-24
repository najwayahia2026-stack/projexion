<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectPhase extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'phase_number',
        'percentage',
        'status',
        'similarity_score',
        'rejection_reason',
        'reviewed_by',
        'submitted_at',
        'delivery_date',
        'notification_sent_at',
        'reviewed_at',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'phase_number' => 'integer',
            'percentage' => 'decimal:2',
            'similarity_score' => 'decimal:2',
            'submitted_at' => 'datetime',
            'delivery_date' => 'date',
            'notification_sent_at' => 'date',
            'reviewed_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(PhaseFile::class, 'phase_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Helper Methods
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function hasHighSimilarity(): bool
    {
        return $this->similarity_score !== null && $this->similarity_score > 50;
    }

    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }
}
