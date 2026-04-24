<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectFile extends Model
{
    protected $fillable = [
        'project_id',
        'section_id',
        'uploaded_by',
        'name',
        'original_name',
        'path',
        'mime_type',
        'size',
        'type',
        'file_type',
        'description',
        'extracted_text',
        'similarity_score',
        'similar_files',
        'status',
        'rejection_reason',
        'ai_probability',
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
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(ProjectSection::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Helper Methods
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
