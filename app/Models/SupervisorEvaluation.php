<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupervisorEvaluation extends Model
{
    protected $fillable = [
        'project_id',
        'supervisor_id',
        'work_quality',
        'punctuality',
        'teamwork',
        'innovation',
        'technical_skills',
        'communication',
        'progress_score',
        'overall_assessment',
        'total_score',
        'strengths',
        'weaknesses',
        'recommendations',
        'general_comments',
        'status',
        'evaluated_at',
    ];

    protected function casts(): array
    {
        return [
            'work_quality' => 'decimal:1',
            'punctuality' => 'decimal:1',
            'teamwork' => 'decimal:1',
            'innovation' => 'decimal:1',
            'technical_skills' => 'decimal:1',
            'communication' => 'decimal:1',
            'progress_score' => 'decimal:2',
            'overall_assessment' => 'decimal:1',
            'total_score' => 'decimal:2',
            'evaluated_at' => 'datetime',
        ];
    }

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    // Helper Methods
    /**
     * حساب الدرجة الإجمالية بطريقة مختلفة عن تقييم المقيم
     * النظام الجديد: متوسط جميع المعايير + التقييم الشامل
     */
    public function calculateTotalScore(): void
    {
        $scores = array_filter([
            $this->work_quality,
            $this->punctuality,
            $this->teamwork,
            $this->innovation,
            $this->technical_skills,
            $this->communication,
        ]);

        $averageScore = !empty($scores) ? array_sum($scores) / count($scores) : 0;
        
        // التقييم الشامل له وزن أكبر (40% من المتوسط + 60% من التقييم الشامل)
        $overallWeight = $this->overall_assessment ?? 0;
        
        if ($overallWeight > 0) {
            $this->total_score = ($averageScore * 0.4) + ($overallWeight * 0.6);
        } else {
            $this->total_score = $averageScore;
        }
        
        // تحويل من مقياس 1-10 إلى مقياس 0-100
        $this->total_score = $this->total_score * 10;
    }

    /**
     * الحصول على تقييم نصي بناءً على الدرجة
     */
    public function getRatingTextAttribute(): string
    {
        $score = $this->total_score ?? 0;
        
        if ($score >= 90) {
            return 'ممتاز';
        } elseif ($score >= 80) {
            return 'جيد جداً';
        } elseif ($score >= 70) {
            return 'جيد';
        } elseif ($score >= 60) {
            return 'مقبول';
        } else {
            return 'يحتاج تحسين';
        }
    }

    /**
     * التحقق من اكتمال التقييم
     */
    public function isComplete(): bool
    {
        return !empty($this->work_quality) &&
               !empty($this->punctuality) &&
               !empty($this->teamwork) &&
               !empty($this->innovation) &&
               !empty($this->technical_skills) &&
               !empty($this->communication) &&
               !empty($this->overall_assessment);
    }
}
