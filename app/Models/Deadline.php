<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deadline extends Model
{
    protected $fillable = [
        'title',
        'description',
        'project_id',
        'group_id',
        'deadline_date',
        'type',
        'is_reminder_sent',
    ];

    protected function casts(): array
    {
        return [
            'deadline_date' => 'datetime',
            'is_reminder_sent' => 'boolean',
        ];
    }

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    // Helper Methods
    public function isOverdue(): bool
    {
        return $this->deadline_date < now() && !$this->isCompleted();
    }

    public function isCompleted(): bool
    {
        // Logic to check if deadline is completed
        return false; // Implement based on your business logic
    }

    public function daysRemaining(): int
    {
        return max(0, now()->diffInDays($this->deadline_date, false));
    }
}
