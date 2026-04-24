<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    protected $fillable = [
        'project_id',
        'evaluator_id',
        'evaluation_type',
        'proposal_score',
        'objectives_achievement',
        'final_score',
        'general_score',
        'total_score',
        'comments',
        'status',
        'evaluated_at',
    ];

    protected function casts(): array
    {
        return [
            'proposal_score' => 'decimal:2',
            'objectives_achievement' => 'decimal:2',
            'final_score' => 'decimal:2',
            'general_score' => 'decimal:2',
            'total_score' => 'decimal:2',
            'evaluated_at' => 'datetime',
        ];
    }

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    // Helper Methods
    public function calculateTotalScore(): void
    {
        $this->total_score = (
            ($this->proposal_score ?? 0) * 0.2 +
            ($this->objectives_achievement ?? 0) * 0.3 +
            ($this->final_score ?? 0) * 0.4 +
            ($this->general_score ?? 0) * 0.1
        );
    }
}
