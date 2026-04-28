<?php

namespace App\Services;

use App\Models\Project;
use App\Models\SimilarityCheck;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AISimilarityService
{
    // الرابط الذي تعمل عليه خدمة الذكاء الاصطناعي (مثل: http://localhost:8001)
    private string $aiServiceUrl;

    public function __construct()
    {
        // جلب الرابط من ملف الإعدادات config، وإذا لم يوجد يستخدم الافتراضي (localhost:8001)
        //$this->aiServiceUrl = config('services.ai_similarity.url', 'http://localhost:8001');
   $this->aiServiceUrl = 'https://cooperative-courage-production-1f2b.up.railway.app';
        }

    /**
     * الوظيفة الرئيسية: فحص تشابه مشروع مع المشاريع الموجودة في قاعدة البيانات
     */
    public function checkSimilarity(Project $project): SimilarityCheck
    {
        try {
            // 1. منطق الفلترة: لا نقارن المشروع بمشاريع نفس المجموعة
            $currentGroupId = $project->group_id;
            
            // جلب المشاريع من المجموعات الأخرى فقط والتي لم يتم رفضها سابقاً
            $existingProjects = Project::where('id', '!=', $project->id)
                ->where('group_id', '!=', $currentGroupId)
                ->where('status', '!=', 'rejected')
                ->get(['id', 'title', 'description', 'objectives']);

            // 2. تجهيز البيانات لإرسالها إلى خدمة الـ AI (بصيغة Array سيتحول لـ JSON)
            $data = [
                'project' => [
                    'title' => $project->title,
                    'description' => $project->description,
                    'objectives' => $project->objectives,
                ],
                'existing_projects' => $existingProjects->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'title' => $p->title,
                        'description' => $p->description,
                        'objectives' => $p->objectives,
                    ];
                })->toArray(),
            ];

            // 3. فحص "صحة الخدمة" (Health Check)
            // نتأكد أولاً أن سيرفر البايثون يعمل قبل إرسال البيانات الكبيرة
            try {
                $healthCheck = Http::timeout(5)->get("{$this->aiServiceUrl}/health");
                if (!$healthCheck->successful()) {
                    throw new \Exception('خدمة AI غير متاحة حالياً. يرجى التأكد من تشغيل الخدمة.');
                }
            } catch (\Exception $e) {
                Log::warning('AI Service health check failed: ' . $e->getMessage());
                throw new \Exception('خدمة AI غير متاحة حالياً. تأكد من تشغيل: cd python_ai_service && python main.py');
            }

            // 4. إرسال الطلب الفعلي لخدمة الـ AI لفحص التشابه
            try {
                $response = Http::timeout(30)->post("{$this->aiServiceUrl}/api/check-similarity", $data);
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                Log::warning('AI Service connection failed: ' . $e->getMessage());
                throw new \Exception('فشل الاتصال بخدمة AI. تأكد من أن السيرفر يعمل.');
            }

            // 5. معالجة النتيجة القادمة من الـ AI
            if ($response->successful()) {
                $result = $response->json();

                // التأكد من أن نسبة التشابه بين 0 و 100
                $similarityPercentage = min(100, max(0, floatval($result['similarity_percentage'] ?? 0)));
                
                // تحديد عدد النتائج (أول 50 مشروع مشابه فقط) لتجنب امتلاء قاعدة البيانات
                $similarProjects = $result['similar_projects'] ?? [];
                if (is_array($similarProjects) && count($similarProjects) > 50) {
                    $similarProjects = array_slice($similarProjects, 0, 50);
                }
                
                // معالجة النصوص الطويلة جداً لضمان تخزينها في قاعدة البيانات (حجم الـ Text)
                $details = $result['details'] ?? null;
                if ($details && strlen($details) > 65535) {
                    $details = substr($details, 0, 65535);
                }

                // تجهيز نص توضيحي للمصادر التي تم المقارنة معها
                $comparisonSources = [];
                $sourceText = "تمت المقارنة مع " . count($existingProjects) . " مشروع موجود:\n";
                
                foreach ($similarProjects as $similarProject) {
                    $sourceProject = $existingProjects->firstWhere('id', $similarProject['id'] ?? null);
                    if ($sourceProject) {
                        $comparisonSources[] = [
                            'id' => $sourceProject->id,
                            'title' => $sourceProject->title,
                            'similarity' => $similarProject['similarity'] ?? 0,
                        ];
                        $sourceText .= "- {$sourceProject->title} (نسبة التشابه: " . ($similarProject['similarity'] ?? 0) . "%)\n";
                    }
                }

                // 6. حفظ النتيجة في جدول "SimilarityCheck"
                $similarityCheck = SimilarityCheck::create([
                    'project_id' => $project->id,
                    'similarity_percentage' => $similarityPercentage,
                    'similar_projects' => $similarProjects,
                    'details' => $details,
                    'status' => 'completed',
                    'checked_at' => now(),
                    'source_comparison' => $sourceText,
                    'comparison_sources' => $comparisonSources,
                ]);

                // تحديث نسبة التشابه في جدول المشاريع الأصلي
                $project->update([
                    'similarity_score' => $similarityPercentage,
                ]);

                return $similarityCheck;
            } else {
                // معالجة الأخطاء القادمة من السيرفر (مثل خطأ 500 أو 404)
                $statusCode = $response->status();
                $errorBody = $response->body();
                
                // التحقق إذا كان الرد صفحة HTML (يعني السيرفر معطل أو الصفحة غير موجودة)
                if (str_contains($errorBody, '<!DOCTYPE html>') || $statusCode === 404) {
                    throw new \Exception('خدمة AI غير متاحة حالياً.');
                }
                
                // محاولة فهم رسالة الخطأ من الـ JSON
                $errorMessage = 'خطأ غير معروف من خدمة AI';
                // ... (منطق جلب رسالة الخطأ)
                
                Log::error("AI Service returned error: {$errorMessage}");
                throw new \Exception('خطأ من خدمة AI: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            // في حال حدوث أي فشل، نقوم بتسجيل العملية كـ "فاشلة" في قاعدة البيانات ليعرف المستخدم
            Log::error('Similarity check failed: ' . $e->getMessage());

            return SimilarityCheck::create([
                'project_id' => $project->id,
                'similarity_percentage' => 0,
                'status' => 'failed',
                'details' => substr($e->getMessage(), 0, 65535),
            ]);
        }
    }

    /**
     * وظيفة لجلب آخر نتيجة فحص ناجحة لمشروع معين
     */
    public function getSimilarityResults(Project $project): ?SimilarityCheck
    {
        return SimilarityCheck::where('project_id', $project->id)
            ->where('status', 'completed')
            ->latest()
            ->first();
    }
}