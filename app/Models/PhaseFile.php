<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhaseFile extends Model
{
    protected $fillable = [
        'phase_id',
        'uploaded_by',
        'name',
        'original_name',
        'path',
        'mime_type',
        'size',
        'file_type',
        'extracted_text',
        'similarity_score',
        'ai_probability',
        'similar_files',
        'status',
        'rejection_reason',
        'checked_at',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'similarity_score' => 'decimal:2',
            'ai_probability' => 'decimal:2',
            'similar_files' => 'array',
            'checked_at' => 'datetime',
        ];
    }

    // Relationships
    public function phase(): BelongsTo
    {
        return $this->belongsTo(ProjectPhase::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
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

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
