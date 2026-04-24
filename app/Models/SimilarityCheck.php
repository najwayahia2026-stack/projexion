<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SimilarityCheck extends Model
{
    protected $fillable = [
        'project_id',
        'similarity_percentage',
        'similar_projects',
        'details',
        'status',
        'checked_at',
        'source_comparison',
        'comparison_sources',
    ];

    protected function casts(): array
    {
        return [
            'similarity_percentage' => 'decimal:2',
            'similar_projects' => 'array',
            'checked_at' => 'datetime',
            'comparison_sources' => 'array',
        ];
    }

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // Helper Methods
    public function isHighSimilarity(): bool
    {
        return $this->similarity_percentage >= 70;
    }
}
