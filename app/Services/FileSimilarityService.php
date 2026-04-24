<?php

namespace App\Services;

use App\Models\PhaseFile;
use App\Models\ProjectFile;
use App\Models\ProjectPhase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use setasign\Fpdi\Fpdi;

class FileSimilarityService
{
    // رابط خدمة الذكاء الاصطناعي الخارجية
    private string $aiServiceUrl;
    
    // الحد الأدنى المسموح به للتشابه (50%)، ما زاد عنه يعتبر تطابقاً غير مقبول
    private const SIMILARITY_THRESHOLD = 50;

    public function __construct()
    {
        // جلب عنوان الخدمة من ملف الإعدادات أو استخدام القيمة الافتراضية للمنفذ 8001
        $this->aiServiceUrl = config('services.ai_similarity.url', 'http://localhost:8001');
    }

    /**
     * تنفيذ فحص التشابه الأساسي باستخدام PHP 
     * (يُستخدم كخيار بديل/احتياطي في حال عدم توفر خدمة الذكاء الاصطناعي)
     */
    private function performBasicSimilarityCheck(string $text, $existingFiles): array
    {
        // إذا لم تكن هناك ملفات سابقة في قاعدة البيانات، نرجع نتيجة صفرية فوراً
        if ($existingFiles->isEmpty()) {
            return [
                'score' => 0,
                'similar_files' => [],
            ];
        }

        $maxSimilarity = 0; // لتخزين أعلى نسبة تشابه يتم العثور عليها
        $similarFiles = []; // قائمة ببيانات الملفات المتشابهة

        /**
         * تحضير النص المرفوع للمقارنة:
         * 1. تحويل الحروف إلى صغيرة (strtolower).
         * 2. استخراج الكلمات (str_word_count) مع السماح بالأرقام والحروف اللاتينية.
         * 3. استخدام (array_unique) لإزالة الكلمات المكررة، لأننا نهتم بوجود الكلمة لا تكرارها.
         */
        $textWords = array_unique(str_word_count(strtolower($text), 1, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'));
        $textWordCount = count($textWords);

        // حلقة تكرارية للمقارنة مع كل ملف موجود مسبقاً في قاعدة البيانات
        foreach ($existingFiles as $existingFile) {
            
            // تنظيف النص المستخرج من الملف المخزن مسبقاً
            $existingText = $this->cleanExtractedText($existingFile->extracted_text ?? '');
            if (empty($existingText)) {
                continue; // تخطي الملف إذا كان فارغاً
            }

            // تحويل نص الملف المخزن إلى كلمات فريدة بنفس الطريقة السابقة
            $existingWords = array_unique(str_word_count(strtolower($existingText), 1, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'));
            $existingWordCount = count($existingWords);

            /**
             * حساب تشابه جاكارد (Jaccard Similarity):
             * المعادلة: (عدد الكلمات المشتركة) مقسوماً على (إجمالي الكلمات الفريدة في النصين معاً)
             */
            
            // 1. التقاطع (Intersection): الكلمات الموجودة في الملفين معاً
            $intersection = count(array_intersect($textWords, $existingWords));
            
            // 2. الاتحاد (Union): إجمالي الكلمات الفريدة في كلا الملفين بدون تكرار
            $union = count(array_unique(array_merge($textWords, $existingWords)));
            
            // 3. حساب النسبة المئوية للتشابه
            $similarity = $union > 0 ? ($intersection / $union) * 100 : 0;

            // تحديث أعلى درجة تشابه تم العثور عليها حتى الآن
            if ($similarity > $maxSimilarity) {
                $maxSimilarity = $similarity;
            }

            // إذا تجاوزت نسبة التشابه 30%، نقوم بتسجيل بيانات الملف لإظهارها في التقرير
            if ($similarity >= 30) {
                $similarFiles[] = [
                    'id' => $existingFile->id,
                    'file_name' => $existingFile->original_name ?? $existingFile->name ?? 'unknown',
                    'similarity' => round($similarity, 2), // تقريب النسبة لخانة عشرية
                ];
            }
        }

        // ترتيب قائمة الملفات المتشابهة من الأعلى تشابهاً إلى الأقل
        usort($similarFiles, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        // إرجاع النتيجة النهائية: أعلى درجة تشابه، وأفضل 5 ملفات مطابقة
        return [
            'score' => round($maxSimilarity, 2),
            'similar_files' => array_slice($similarFiles, 0, 5),
        ];
    }
/**
     * تنظيف النص المستخرج من خلال إزالة الروابط، البيانات الوصفية، والمحتوى غير الضروري.
     * الهدف: تحسين دقة المقارنة وتوفير مساحة التخزين.
     */
    private function cleanExtractedText(string $text): string
    {
        // 1. إزالة روابط المواقع الإلكترونية (URLs)
        $text = preg_replace('/https?:\/\/[^\s]+/i', '', $text);
        
        // 2. إزالة عناوين البريد الإلكتروني
        $text = preg_replace('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', '', $text);
        
        // 3. إزالة أنماط البيانات الوصفية الخاصة بملفات PDF (مثل تواريخ الإنشاء البرمجية)
        $text = preg_replace('/D:\d{14}[+-]\d{2}\'?\d{2}\'?/', '', $text);
        
        // 4. إزالة أنماط تشبه أوامر SQL أو سجلات قواعد البيانات لضمان نقاء النص
        $text = preg_replace('/`\w+`\.`\w+`\s*=\s*\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}/', '', $text);
        
        // 5. إزالة الرموز الخاصة التي لا تشكل جزءاً من النص المقروء
        $text = preg_replace('/Q\s+rv_[,`]/', '', $text);
        
        // 6. توحيد المسافات: تحويل المسافات المتعددة والأسطر الجديدة إلى مسافة واحدة
        $text = preg_replace('/\s+/', ' ', $text);
        
        // 7. حذف المسافات الزائدة من بداية ونهاية النص
        $text = trim($text);
        
        // 8. تحديد طول النص: منعاً لمشاكل الأداء، يتم الاحتفاظ بـ 1 ميجابايت فقط من النص
        $maxLength = 1048576; // 1MB
        if (strlen($text) > $maxLength) {
            $text = mb_substr($text, 0, $maxLength, 'UTF-8');
            // تسجيل تحذير في حال تم قص النص
            Log::warning("تم تقليص النص المستخرج إلى {$maxLength} حرفاً لضمان أداء قاعدة البيانات.");
        }
        
        return $text;
    }

    /**
     * الوظيفة المسؤولة عن اختيار طريقة الاستخراج المناسبة بناءً على نوع الملف (Word أو PDF).
     */
    public function extractTextFromFile(PhaseFile $file): ?string
    {
        try {
            // تحديد المسار الكامل للملف في التخزين
            $filePath = storage_path('app/public/' . $file->path);

            // التحقق من وجود الملف في السيرفر قبل البدء
            if (!file_exists($filePath)) {
                Log::error("الملف غير موجود في المسار المحدد: {$filePath}");
                return null;
            }

            // توجيه الملف للمُعالج المناسب حسب نوعه
            if ($file->file_type === 'word') {
                return $this->extractTextFromWord($filePath);
            } elseif ($file->file_type === 'pdf') {
                return $this->extractTextFromPDF($filePath);
            }

            return null;
        } catch (\Exception $e) {
            Log::error("حدث خطأ أثناء محاولة استخراج النص: " . $e->getMessage());
            return null;
        }
    }

    /**
     * استخراج النص من ملفات Word (.docx)
     */
    private function extractTextFromWord(string $filePath): string
    {
        try {
            // تحميل ملف Word باستخدام مكتبة PHPWord
            $phpWord = WordIOFactory::load($filePath);
            $text = '';

            // الدوران على أقسام الملف
            foreach ($phpWord->getSections() as $section) {
                // الدوران على العناصر داخل كل قسم (فقرات، جداول، إلخ)
                foreach ($section->getElements() as $element) {
                    // إذا كان العنصر نصاً مباشراً
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    } 
                    // التعامل مع العناصر المتداخلة (مثل الجداول أو القوائم)
                    elseif (method_exists($element, 'getElements')) {
                        foreach ($element->getElements() as $subElement) {
                            if (method_exists($subElement, 'getText')) {
                                $text .= $subElement->getText() . "\n";
                            }
                        }
                    }
                }
            }

            // تنظيف نهائي للنص المستخرج
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);

            if (empty($text)) {
                Log::warning("تم استخراج نص فارغ من ملف Word: {$filePath}");
            } else {
                Log::info("تم استخراج النص بنجاح من ملف Word، الطول: " . strlen($text));
            }

            return $text;
        } catch (\Exception $e) {
            Log::error("خطأ أثناء قراءة ملف Word: " . $e->getMessage() . " | الملف: " . $filePath);
            return '';
        }
    }
    /**
     * استخراج النص من ملف PDF.
     * تعتمد الدالة على نظام "الأولويات": تبدأ بالأكثر دقة، وفي حال الفشل تنتقل للطريقة التالية.
     */
    private function extractTextFromPDF(string $filePath): string
    {
        try {
            // --- الطريقة الأولى: استخدام خدمة Python الخارجية (الأكثر دقة) ---
            // نرسل طلب لخدمة الذكاء الاصطناعي لأنها تستخدم مكتبات متطورة لمعالجة النصوص العربية والتنسيقات المعقدة.
            try {
                $response = Http::timeout(30)->post("{$this->aiServiceUrl}/api/extract-pdf-text", [
                    'file_path' => $filePath,
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $extractedText = $result['text'] ?? '';
                    if (!empty($extractedText)) {
                        Log::info("تم استخراج نص PDF بنجاح باستخدام خدمة Python");
                        return trim($extractedText);
                    }
                }
            } catch (\Exception $e) {
                Log::warning("فشلت خدمة Python لاستخراج PDF، يتم الآن الانتقال للخيار البديل: " . $e->getMessage());
            }

            // --- الطريقة الثانية: استخدام أداة pdftotext (أداة سطر أوامر بالنظام) ---
            // إذا كانت الأداة مثبتة على السيرفر، فهي سريعة جداً وفعالة في الحفاظ على تخطيط الصفحة (-layout).
            if (function_exists('shell_exec') && !empty(shell_exec('which pdftotext'))) {
                try {
                    $output = shell_exec("pdftotext -layout \"{$filePath}\" - 2>&1");
                    if (!empty($output)) {
                        Log::info("تم استخراج نص PDF بنجاح باستخدام pdftotext");
                        return trim($output);
                    }
                } catch (\Exception $e) {
                    Log::warning("فشلت أداة pdftotext، يتم الآن الانتقال للخيار الأخير: " . $e->getMessage());
                }
            }

            // --- الطريقة الثالثة: الاستخراج اليدوي باستخدام التعابير النمطية (Regex) والبحث في Streams ---
            // هذا هو الملاذ الأخير إذا فشلت الأدوات السابقة، حيث نقوم بفتح ملف الـ PDF كملف نصي خام.
            $text = '';
            $content = file_get_contents($filePath);
            
            if (empty($content)) {
                Log::error("ملف PDF فارغ أو لا يمكن قراءته: {$filePath}");
                return '';
            }

            // 1. استخراج النصوص من داخل الـ "streams" (المناطق التي يُخزن فيها المحتوى داخل PDF)
            preg_match_all('/stream\s*(.*?)\s*endstream/s', $content, $matches);
            
            foreach ($matches[1] as $match) {
                // محاولة فك ضغط البيانات إذا كانت مشفرة بنوع FlateDecode
                if (str_pos($content, '/FlateDecode') !== false) {
                    try {
                        $decompressed = @gzuncompress($match);
                        if ($decompressed !== false) {
                            $match = $decompressed;
                        }
                    } catch (\Exception $e) {
                        // في حال فشل فك الضغط، نستمر في معالجة البيانات الخام
                    }
                }
                
                // تنظيف النص المستخرج والاحتفاظ بالأحرف المقروءة (ASCII) واللغة العربية (Unicode)
                // النطاق \x{0600}-\x{06FF} مخصص لدعم الحروف العربية
                $cleaned = preg_replace('/[^\x20-\x7E\x{0600}-\x{06FF}\x{200C}\x{200D}\x{FEFF}]/u', ' ', $match);
                $text .= $cleaned . ' ';
            }

            // 2. البحث عن النصوص المحصورة بين أقواس ( ) وهي الطريقة التقليدية لتخزين النصوص في PDF
            preg_match_all('/\((.*?)\)/s', $content, $textMatches);
            foreach ($textMatches[1] as $textMatch) {
                $cleaned = preg_replace('/[^\x20-\x7E\x{0600}-\x{06FF}\x{200C}\x{200D}\x{FEFF}]/u', ' ', $textMatch);
                if (strlen(trim($cleaned)) > 3) { // إضافة النصوص ذات المعنى فقط (تجاهل الرموز القصيرة)
                    $text .= $cleaned . ' ';
                }
            }

            $result = trim($text);
            
            if (empty($result)) {
                Log::warning("فشلت جميع المحاولات لاستخراج النص من: {$filePath}");
            } else {
                Log::info("تم استخراج النص باستخدام طريقة Regex اليدوية، الطول: " . strlen($result));
            }

            return $result;
        } catch (\Exception $e) {
            Log::error("خطأ فادح أثناء قراءة ملف PDF: " . $e->getMessage() . " | الملف: " . $filePath);
            return '';
        }
    }
    /**
     * Check similarity of uploaded file with existing files
     */
    public function checkFileSimilarity(PhaseFile $file): array
    {
        // تمديد وقت التنفيذ لعمليات الاستخراج واستدعاء AI
        set_time_limit(600);

        // Initialize variables for fallback scenario
        $cleanedText = null;
        $existingFiles = collect();
        
        try {
            // Extract text from uploaded file
            $extractedText = $this->extractTextFromFile($file);
            
            if (empty($extractedText)) {
                return [
                    'success' => false,
                    'message' => 'فشل استخراج النص من الملف',
                    'similarity_score' => 0,
                ];
            }

            // Clean and save extracted text
            $cleanedText = $this->cleanExtractedText($extractedText);
            $file->update(['extracted_text' => $cleanedText]);

            // Get files from OTHER students only (exclude files from the same student)
            // Each student's projects should be separate
            $currentUserId = $file->uploaded_by;
            
            // Get PhaseFiles from other students
            $existingPhaseFiles = PhaseFile::where('id', '!=', $file->id)
                ->where('uploaded_by', '!=', $currentUserId)
                ->whereNotNull('extracted_text')
                ->where('status', '!=', 'rejected')
                ->get();

            // Get ProjectFiles from other students
            $existingProjectFiles = ProjectFile::where('uploaded_by', '!=', $currentUserId)
                ->whereNotNull('extracted_text')
                ->where('status', '!=', 'rejected')
                ->get();

            // Combine all files for comparison (only from other students)
            $existingFiles = $existingPhaseFiles->concat($existingProjectFiles);

            if ($existingFiles->isEmpty()) {
                // No files to compare with, but still check for AI
                $aiProbability = null;
                try {
                    $aiDetectionResponse = Http::timeout(30)->post("{$this->aiServiceUrl}/api/detect-ai", [
                        'text' => $cleanedText,
                    ]);

                    if ($aiDetectionResponse->successful()) {
                        $aiResult = $aiDetectionResponse->json();
                        $aiProbability = $aiResult['ai_probability'] ?? null;
                    }
                } catch (\Exception $e) {
                    Log::warning('AI detection service unavailable: ' . $e->getMessage());
                    $aiProbability = null;
                }

                // Check if AI probability is high enough to reject
                $shouldReject = false;
                $rejectionReason = '';
                
                if ($aiProbability !== null && $aiProbability >= 70) {
                    $shouldReject = true;
                    $rejectionReason = "احتمالية كون الملف منشأ بالذكاء الاصطناعي عالية ({$aiProbability}%)";
                }

                if ($shouldReject) {
                    $file->update([
                        'status' => 'rejected',
                        'similarity_score' => 0,
                        'ai_probability' => $aiProbability,
                        'rejection_reason' => $rejectionReason,
                    ]);

                    $file->phase->update([
                        'status' => 'rejected',
                        'similarity_score' => 0,
                        'rejection_reason' => "تم رفض الجزء بسبب: {$rejectionReason}",
                    ]);

                    return [
                        'success' => false,
                        'message' => "تم رفض الملف: {$rejectionReason}",
                        'similarity_score' => 0,
                        'ai_probability' => $aiProbability,
                        'rejected' => true,
                    ];
                }

                $file->update([
                    'status' => 'checked',
                    'similarity_score' => 0,
                    'ai_probability' => $aiProbability,
                    'checked_at' => now(), // Record check timestamp
                ]);
                
                // Archive the phase after checking
                if ($file->phase && !$file->phase->archived_at) {
                    $file->phase->update([
                        'archived_at' => now(),
                    ]);
                }

                $message = 'تم فحص الملف. لا توجد ملفات للمقارنة.';
                if ($aiProbability !== null) {
                    $message .= " | احتمالية AI: " . number_format($aiProbability, 2) . "%";
                }

                return [
                    'success' => true,
                    'message' => $message,
                    'similarity_score' => 0,
                    'ai_probability' => $aiProbability,
                ];
            }

            // Validate text length before sending
            $maxTextLength = 500000; // Limit text to ~500KB to avoid timeout issues
            if (strlen($cleanedText) > $maxTextLength) {
                Log::warning("Text too long, truncating from " . strlen($cleanedText) . " to {$maxTextLength}");
                $cleanedText = mb_substr($cleanedText, 0, $maxTextLength, 'UTF-8');
            }

            // Prepare data for AI service (use cleaned text)
            $existingFilesData = $existingFiles->map(function ($f) {
                // Clean existing files' text as well
                $existingText = $f->extracted_text ?? '';
                $cleanedExistingText = $this->cleanExtractedText($existingText);
                
                // Limit existing file text length too
                if (strlen($cleanedExistingText) > 50000) {
                    $cleanedExistingText = mb_substr($cleanedExistingText, 0, 50000, 'UTF-8');
                }
                
                return [
                    'id' => $f->id,
                    'text' => $cleanedExistingText,
                    'file_name' => $f->original_name ?? $f->name ?? 'unknown',
                ];
            })->filter(function ($f) {
                // Filter out files with empty text
                return !empty($f['text']) && strlen($f['text']) > 10; // At least 10 characters
            })->take(50) // Limit to 50 files to avoid timeout
            ->toArray();
            
            $data = [
                'text' => $cleanedText,
                'existing_files' => $existingFilesData,
            ];
            
            Log::info("Sending similarity check request with " . count($existingFilesData) . " files, text length: " . strlen($cleanedText));

            // Try to check service availability (non-blocking - don't fail if health check fails)
            $serviceAvailable = false;
            try {
                $healthCheck = Http::timeout(3)->retry(2, 100)->get("{$this->aiServiceUrl}/health");
                $serviceAvailable = $healthCheck->successful();
            } catch (\Exception $e) {
                Log::info('AI Service health check failed, will try anyway: ' . $e->getMessage());
                $serviceAvailable = false;
            }

            // Call AI service with retry mechanism
            $maxRetries = 2;
            $retryDelay = 500; // milliseconds
            $response = null;
            $lastException = null;
            
            for ($attempt = 0; $attempt <= $maxRetries; $attempt++) {
                try {
                    if ($attempt > 0) {
                        Log::info("Retrying AI service call (attempt {$attempt}/{$maxRetries})...");
                        usleep($retryDelay * 1000 * $attempt); // Exponential backoff
                    }
                    
                    $response = Http::timeout(90)
                        ->retry(1, 200)
                        ->post("{$this->aiServiceUrl}/api/check-file-similarity", $data);
                    
                    if ($response->successful()) {
                        break; // Success, exit retry loop
                    }
                    
                    // If not successful but not a connection error, don't retry
                    if (!in_array($response->status(), [408, 500, 502, 503, 504])) {
                        break;
                    }
                    
                    $lastException = new \Exception("HTTP {$response->status()}: " . $response->body());
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    $lastException = $e;
                    Log::warning("AI Service connection attempt {$attempt} failed: " . $e->getMessage());
                    if ($attempt === $maxRetries) {
                        // Last attempt failed, will try fallback
                        break;
                    }
                } catch (\Exception $e) {
                    $lastException = $e;
                    Log::error("AI Service request attempt {$attempt} failed: " . $e->getMessage());
                    if ($attempt === $maxRetries) {
                        break;
                    }
                }
            }
            
            // If all retries failed, throw exception to trigger fallback
            if ($response === null || !$response->successful()) {
                if ($lastException instanceof \Illuminate\Http\Client\ConnectionException) {
                    throw new \Exception('فشل الاتصال بخدمة AI بعد عدة محاولات. يرجى التأكد من تشغيل الخدمة.');
                } else {
                    throw new \Exception('فشل الاتصال بخدمة AI: ' . ($lastException ? $lastException->getMessage() : 'خطأ غير معروف'));
                }
            }

            // Validate response
            try {
                $result = $response->json();
            } catch (\Exception $e) {
                Log::error('Failed to parse AI service response: ' . $e->getMessage());
                throw new \Exception('فشل تحليل استجابة خدمة AI. يرجى المحاولة مرة أخرى.');
            }
            
            $similarityScore = $result['similarity_percentage'] ?? 0;
            $aiProbability = $result['ai_probability'] ?? null;
            
            // Validate similarity score
            if (!is_numeric($similarityScore) || $similarityScore < 0 || $similarityScore > 100) {
                Log::warning("Invalid similarity score received: {$similarityScore}");
                $similarityScore = 0;
            }
            
            // Validate AI probability
            if ($aiProbability !== null && (!is_numeric($aiProbability) || $aiProbability < 0 || $aiProbability > 100)) {
                Log::warning("Invalid AI probability received: {$aiProbability}");
                $aiProbability = null;
            }

            // Update file with similarity score, AI probability, and check timestamp
            $file->update([
                'similarity_score' => $similarityScore,
                'similar_files' => $result['similar_files'] ?? [],
                'ai_probability' => $aiProbability,
                'checked_at' => now(), // Record check timestamp
            ]);

                // Check if similarity exceeds threshold or AI probability is high
                $shouldReject = false;
                $rejectionReason = '';

                if ($similarityScore > self::SIMILARITY_THRESHOLD) {
                    $shouldReject = true;
                    $rejectionReason = "نسبة التشابه ({$similarityScore}%) تتجاوز الحد المسموح (50%)";
                } elseif ($aiProbability !== null && $aiProbability >= 70) {
                    // High AI probability threshold
                    $shouldReject = true;
                    $rejectionReason = "احتمالية كون الملف منشأ بالذكاء الاصطناعي عالية ({$aiProbability}%)";
                }

                if ($shouldReject) {
                    // Reject the file
                    $file->update([
                        'status' => 'rejected',
                        'rejection_reason' => $rejectionReason,
                    ]);

                    // Also reject the phase
                    $file->phase->update([
                        'status' => 'rejected',
                        'similarity_score' => $similarityScore,
                        'rejection_reason' => "تم رفض الجزء بسبب: {$rejectionReason}",
                    ]);

                    $errorMessage = "تم رفض الملف: {$rejectionReason}";
                    if ($aiProbability !== null && $similarityScore <= self::SIMILARITY_THRESHOLD) {
                        $errorMessage = "تم رفض الملف: احتمالية AI ({$aiProbability}%)";
                    }

                    return [
                        'success' => false,
                        'message' => $errorMessage,
                        'similarity_score' => $similarityScore,
                        'ai_probability' => $aiProbability,
                        'rejected' => true,
                    ];
                } else {
                    // Approve the file and archive phase
                    $file->update([
                        'status' => 'checked',
                    ]);
                    
                    // Archive the phase after checking
                    if ($file->phase && !$file->phase->archived_at) {
                        $file->phase->update([
                            'archived_at' => now(),
                        ]);
                    }

                    $message = "تم فحص الملف بنجاح. نسبة التشابه: {$similarityScore}%";
                    if ($aiProbability !== null) {
                        $message .= " | احتمالية AI: " . number_format($aiProbability, 2) . "%";
                    }

                    return [
                        'success' => true,
                        'message' => $message,
                        'similarity_score' => $similarityScore,
                        'ai_probability' => $aiProbability,
                        'rejected' => false,
                    ];
                }
        } catch (\Exception $e) {
            Log::error('File similarity check failed: ' . $e->getMessage() . ' | Stack trace: ' . $e->getTraceAsString());

            // If AI service is unavailable, try basic similarity check using PHP
            $errorMessage = $e->getMessage();
            $isServiceUnavailable = str_contains($errorMessage, 'غير متاحة') 
                || str_contains($errorMessage, 'غير متاح') 
                || str_contains($errorMessage, 'Connection')
                || str_contains($errorMessage, 'connection')
                || str_contains($errorMessage, 'timeout')
                || str_contains($errorMessage, 'Timeout');
            
            // Only try basic similarity check if we have the required data
            if ($isServiceUnavailable && $cleanedText !== null && !empty($cleanedText) && $existingFiles->isNotEmpty()) {
                // Try basic similarity check without AI service
                try {
                    $basicSimilarity = $this->performBasicSimilarityCheck($cleanedText, $existingFiles);
                    
                    $file->update([
                        'status' => 'checked',
                        'similarity_score' => $basicSimilarity['score'],
                        'similar_files' => $basicSimilarity['similar_files'],
                        'checked_at' => now(), // Record check timestamp
                    ]);
                    
                    // Archive the phase after checking
                    if ($file->phase && !$file->phase->archived_at) {
                        $file->phase->update([
                            'archived_at' => now(),
                        ]);
                    }

                    if ($basicSimilarity['score'] > self::SIMILARITY_THRESHOLD) {
                        $file->update([
                            'status' => 'rejected',
                            'rejection_reason' => "نسبة التشابه ({$basicSimilarity['score']}%) تتجاوز الحد المسموح (50%). ملاحظة: تم استخدام فحص أساسي لأن خدمة AI غير متاحة.",
                        ]);
                        
                        return [
                            'success' => false,
                            'message' => "تم رفض الملف: نسبة التشابه ({$basicSimilarity['score']}%) تتجاوز الحد المسموح. ملاحظة: تم استخدام فحص أساسي لأن خدمة AI غير متاحة. يرجى تشغيل خدمة Python AI للحصول على نتائج أدق.",
                            'similarity_score' => $basicSimilarity['score'],
                            'rejected' => true,
                        ];
                    }

                    return [
                        'success' => true,
                        'message' => "تم فحص الملف بنجاح. نسبة التشابه: {$basicSimilarity['score']}%. ملاحظة: تم استخدام فحص أساسي لأن خدمة AI غير متاحة. يرجى تشغيل خدمة Python AI للحصول على نتائج أدق.",
                        'similarity_score' => $basicSimilarity['score'],
                        'warning' => 'خدمة AI غير متاحة. تم استخدام فحص أساسي. للحصول على نتائج أدق، يرجى تشغيل خدمة Python AI: cd python_ai_service && python main.py',
                    ];
                } catch (\Exception $basicError) {
                    Log::error('Basic similarity check also failed: ' . $basicError->getMessage());
                }
            }
            
            // Determine the specific error type for better user feedback
            $specificMessage = 'فشل فحص التشابه. يرجى المحاولة مرة أخرى.';
            
            if ($isServiceUnavailable) {
                $specificMessage = 'خدمة فحص التشابه غير متاحة حالياً. يرجى التأكد من تشغيل خدمة Python AI.';
                if ($cleanedText === null || empty($cleanedText)) {
                    $specificMessage = 'فشل استخراج النص من الملف. يرجى التحقق من صحة الملف وإعادة المحاولة.';
                }
            } elseif (str_contains($errorMessage, 'استخراج')) {
                $specificMessage = 'فشل استخراج النص من الملف. يرجى التحقق من صحة الملف وإعادة المحاولة.';
            } elseif (str_contains($errorMessage, 'HTTP')) {
                $specificMessage = 'حدث خطأ في الاتصال بخدمة فحص التشابه. يرجى المحاولة مرة أخرى لاحقاً.';
            }

            // Mark file as pending for manual review
            $file->update([
                'status' => 'pending',
            ]);

            return [
                'success' => false,
                'message' => $specificMessage . ' إذا استمرت المشكلة، سيتم فحص الملف يدوياً.',
                'similarity_score' => 0,
                'error_details' => $isServiceUnavailable ? 'لتشغيل خدمة Python AI: افتح terminal جديد واكتب: cd python_ai_service && pip install -r requirements.txt && python main.py' : null,
            ];
        }
    }

    /**
     * Extract text from ProjectFile
     */
    public function extractTextFromProjectFile(ProjectFile $file): ?string
    {
        try {
            $filePath = storage_path('app/public/' . $file->path);

            if (!file_exists($filePath)) {
                Log::error("File not found: {$filePath}");
                return null;
            }

            if ($file->file_type === 'word') {
                return $this->extractTextFromWord($filePath);
            } elseif ($file->file_type === 'pdf') {
                return $this->extractTextFromPDF($filePath);
            }

            return null;
        } catch (\Exception $e) {
            Log::error("Error extracting text from file: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Check similarity of uploaded project file with existing files
     */
    public function checkProjectFileSimilarity(ProjectFile $file): array
    {
        // Initialize variables for fallback scenario
        $cleanedText = null;
        $existingFiles = collect();
        
        try {
            // Extract text from uploaded file
            $extractedText = $this->extractTextFromProjectFile($file);
            
            if (empty($extractedText)) {
                return [
                    'success' => false,
                    'error' => 'فشل استخراج النص من الملف',
                    'similarity_score' => 0,
                ];
            }

            // Clean and save extracted text
            $cleanedText = $this->cleanExtractedText($extractedText);
            $file->update(['extracted_text' => $cleanedText]);

            // Get files from OTHER students only (exclude files from the same student)
            // Each student's projects should be separate
            $currentUserId = $file->uploaded_by;
            
            // Get PhaseFiles from other students
            $existingPhaseFiles = PhaseFile::where('uploaded_by', '!=', $currentUserId)
                ->whereNotNull('extracted_text')
                ->where('status', '!=', 'rejected')
                ->get();

            // Get ProjectFiles from other students
            $existingProjectFiles = ProjectFile::where('id', '!=', $file->id)
                ->where('uploaded_by', '!=', $currentUserId)
                ->whereNotNull('extracted_text')
                ->where('status', '!=', 'rejected')
                ->get();

            // Combine all files for comparison (only from other students)
            $existingFiles = $existingPhaseFiles->concat($existingProjectFiles);

            if ($existingFiles->isEmpty()) {
                // No files to compare with, but still check for AI
                try {
                    $aiDetectionResponse = Http::timeout(30)->post("{$this->aiServiceUrl}/api/detect-ai", [
                        'text' => $cleanedText,
                    ]);

                    $aiProbability = null;
                    if ($aiDetectionResponse->successful()) {
                        $aiResult = $aiDetectionResponse->json();
                        $aiProbability = $aiResult['ai_probability'] ?? null;
                    }
                } catch (\Exception $e) {
                    Log::warning('AI detection service unavailable: ' . $e->getMessage());
                    $aiProbability = null;
                }

                $updateData = [
                    'status' => 'checked',
                ];
                
                // Only save AI probability if it's valid and > 0
                if ($aiProbability !== null && $aiProbability > 0) {
                    $updateData['ai_probability'] = round($aiProbability, 2);
                }
                
                $file->update($updateData);

                $message = 'تم فحص الملف. لا توجد ملفات للمقارنة.';
                if ($aiProbability !== null && $aiProbability > 0) {
                    $message .= " | احتمالية AI: " . round($aiProbability, 2) . "%";
                }

                return [
                    'success' => true,
                    'message' => $message,
                    'similarity_score' => 0,
                    'ai_probability' => $aiProbability,
                ];
            }

            // Validate text length before sending
            $maxTextLength = 500000; // Limit text to ~500KB to avoid timeout issues
            if (strlen($cleanedText) > $maxTextLength) {
                Log::warning("Text too long, truncating from " . strlen($cleanedText) . " to {$maxTextLength}");
                $cleanedText = mb_substr($cleanedText, 0, $maxTextLength, 'UTF-8');
            }

            // Prepare data for AI service (use cleaned text)
            $existingFilesData = $existingFiles->map(function ($f) {
                // Clean existing files' text as well
                $existingText = $f->extracted_text ?? '';
                $cleanedExistingText = $this->cleanExtractedText($existingText);
                
                // Limit existing file text length too
                if (strlen($cleanedExistingText) > 50000) {
                    $cleanedExistingText = mb_substr($cleanedExistingText, 0, 50000, 'UTF-8');
                }
                
                return [
                    'id' => $f->id,
                    'text' => $cleanedExistingText,
                    'file_name' => $f->original_name ?? $f->name ?? 'unknown',
                ];
            })->filter(function ($f) {
                // Filter out files with empty text
                return !empty($f['text']) && strlen($f['text']) > 10; // At least 10 characters
            })->take(100) // Limit to 100 files to avoid timeout
            ->toArray();
            
            $data = [
                'text' => $cleanedText,
                'existing_files' => $existingFilesData,
            ];
            
            Log::info("Sending project file similarity check request with " . count($existingFilesData) . " files, text length: " . strlen($cleanedText));

            // Try to check service availability (non-blocking)
            $serviceAvailable = false;
            try {
                $healthCheck = Http::timeout(3)->retry(2, 100)->get("{$this->aiServiceUrl}/health");
                $serviceAvailable = $healthCheck->successful();
            } catch (\Exception $e) {
                Log::info('AI Service health check failed, will try anyway: ' . $e->getMessage());
                $serviceAvailable = false;
            }

            // Call AI service with retry mechanism
            $maxRetries = 2;
            $retryDelay = 500; // milliseconds
            $response = null;
            $lastException = null;
            
            for ($attempt = 0; $attempt <= $maxRetries; $attempt++) {
                try {
                    if ($attempt > 0) {
                        Log::info("Retrying AI service call (attempt {$attempt}/{$maxRetries})...");
                        usleep($retryDelay * 1000 * $attempt); // Exponential backoff
                    }
                    
                    $response = Http::timeout(90)
                        ->retry(1, 200)
                        ->post("{$this->aiServiceUrl}/api/check-file-similarity", $data);
                    
                    if ($response->successful()) {
                        break; // Success, exit retry loop
                    }
                    
                    // If not successful but not a connection error, don't retry
                    if (!in_array($response->status(), [408, 500, 502, 503, 504])) {
                        break;
                    }
                    
                    $lastException = new \Exception("HTTP {$response->status()}: " . $response->body());
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    $lastException = $e;
                    Log::warning("AI Service connection attempt {$attempt} failed: " . $e->getMessage());
                    if ($attempt === $maxRetries) {
                        break;
                    }
                } catch (\Exception $e) {
                    $lastException = $e;
                    Log::error("AI Service request attempt {$attempt} failed: " . $e->getMessage());
                    if ($attempt === $maxRetries) {
                        break;
                    }
                }
            }
            
            // If all retries failed, throw exception to trigger fallback
            if ($response === null || !$response->successful()) {
                if ($lastException instanceof \Illuminate\Http\Client\ConnectionException) {
                    throw new \Exception('فشل الاتصال بخدمة AI بعد عدة محاولات. يرجى التأكد من تشغيل الخدمة.');
                } else {
                    throw new \Exception('فشل الاتصال بخدمة AI: ' . ($lastException ? $lastException->getMessage() : 'خطأ غير معروف'));
                }
            }

            // Validate response
            try {
                $result = $response->json();
            } catch (\Exception $e) {
                Log::error('Failed to parse AI service response: ' . $e->getMessage());
                throw new \Exception('فشل تحليل استجابة خدمة AI. يرجى المحاولة مرة أخرى.');
            }
            
            $similarityScore = $result['similarity_percentage'] ?? 0;
            $aiProbability = $result['ai_probability'] ?? null;
            
            // Validate similarity score
            if (!is_numeric($similarityScore) || $similarityScore < 0 || $similarityScore > 100) {
                Log::warning("Invalid similarity score received: {$similarityScore}");
                $similarityScore = 0;
            }
            
            // Validate AI probability
            if ($aiProbability !== null && (!is_numeric($aiProbability) || $aiProbability < 0 || $aiProbability > 100)) {
                Log::warning("Invalid AI probability received: {$aiProbability}");
                $aiProbability = null;
            }

            // Update file with similarity score and AI probability
            $file->update([
                'similarity_score' => $similarityScore,
                'similar_files' => $result['similar_files'] ?? [],
                'ai_probability' => $aiProbability,
            ]);

                // Check if similarity exceeds threshold or AI probability is high
                $shouldReject = false;
                $rejectionReason = '';

                if ($similarityScore > self::SIMILARITY_THRESHOLD) {
                    $shouldReject = true;
                    $rejectionReason = "نسبة التشابه ({$similarityScore}%) تتجاوز الحد المسموح (50%)";
                } elseif ($aiProbability !== null && $aiProbability >= 70) {
                    // High AI probability threshold
                    $shouldReject = true;
                    $rejectionReason = "احتمالية كون الملف منشأ بالذكاء الاصطناعي عالية ({$aiProbability}%)";
                }

                if ($shouldReject) {
                    // Reject the file
                    $file->update([
                        'status' => 'rejected',
                        'rejection_reason' => $rejectionReason,
                    ]);

                    $errorMessage = "تم رفض الملف: {$rejectionReason}";
                    if ($aiProbability !== null && $similarityScore <= self::SIMILARITY_THRESHOLD) {
                        $errorMessage = "تم رفض الملف: احتمالية AI ({$aiProbability}%)";
                    }

                    return [
                        'success' => false,
                        'error' => $errorMessage,
                        'similarity_score' => $similarityScore,
                        'ai_probability' => $aiProbability,
                        'rejected' => true,
                    ];
                } else {
                    // Approve the file
                    $file->update([
                        'status' => 'checked',
                    ]);

                    $message = "تم فحص الملف بنجاح. نسبة التشابه: {$similarityScore}%";
                    if ($aiProbability !== null) {
                        $message .= " | احتمالية AI: {$aiProbability}%";
                    }

                    return [
                        'success' => true,
                        'message' => $message,
                        'similarity_score' => $similarityScore,
                        'ai_probability' => $aiProbability,
                        'rejected' => false,
                    ];
                }
        } catch (\Exception $e) {
            Log::error('Project file similarity check failed: ' . $e->getMessage() . ' | Stack trace: ' . $e->getTraceAsString());

            // If AI service is unavailable, try basic similarity check using PHP
            $errorMessage = $e->getMessage();
            $isServiceUnavailable = str_contains($errorMessage, 'غير متاحة') 
                || str_contains($errorMessage, 'غير متاح') 
                || str_contains($errorMessage, 'Connection')
                || str_contains($errorMessage, 'connection')
                || str_contains($errorMessage, 'timeout')
                || str_contains($errorMessage, 'Timeout');
            
            // Only try basic similarity check if we have the required data
            if ($isServiceUnavailable && $cleanedText !== null && !empty($cleanedText) && $existingFiles->isNotEmpty()) {
                try {
                    $basicSimilarity = $this->performBasicSimilarityCheck($cleanedText, $existingFiles);
                    
                    $file->update([
                        'status' => 'checked',
                        'similarity_score' => $basicSimilarity['score'],
                        'similar_files' => $basicSimilarity['similar_files'],
                        'checked_at' => now(), // Record check timestamp
                    ]);
                    
                    // Archive the phase after checking
                    if ($file->phase && !$file->phase->archived_at) {
                        $file->phase->update([
                            'archived_at' => now(),
                        ]);
                    }

                    if ($basicSimilarity['score'] > self::SIMILARITY_THRESHOLD) {
                        $file->update([
                            'status' => 'rejected',
                            'rejection_reason' => "نسبة التشابه ({$basicSimilarity['score']}%) تتجاوز الحد المسموح (50%). ملاحظة: تم استخدام فحص أساسي لأن خدمة AI غير متاحة.",
                        ]);
                        
                        return [
                            'success' => false,
                            'error' => "تم رفض الملف: نسبة التشابه ({$basicSimilarity['score']}%) تتجاوز الحد المسموح. ملاحظة: تم استخدام فحص أساسي لأن خدمة AI غير متاحة.",
                            'similarity_score' => $basicSimilarity['score'],
                            'rejected' => true,
                        ];
                    }

                    return [
                        'success' => true,
                        'message' => "تم فحص الملف بنجاح. نسبة التشابه: {$basicSimilarity['score']}%. ملاحظة: تم استخدام فحص أساسي لأن خدمة AI غير متاحة.",
                        'similarity_score' => $basicSimilarity['score'],
                        'warning' => 'خدمة AI غير متاحة. تم استخدام فحص أساسي.',
                    ];
                } catch (\Exception $basicError) {
                    Log::error('Basic similarity check also failed: ' . $basicError->getMessage());
                }
            }

            // Determine the specific error type for better user feedback
            $specificMessage = 'حدث خطأ أثناء فحص الملف';
            
            if ($isServiceUnavailable) {
                $specificMessage = 'خدمة فحص التشابه غير متاحة حالياً. يرجى التأكد من تشغيل خدمة Python AI.';
                if ($cleanedText === null || empty($cleanedText)) {
                    $specificMessage = 'فشل استخراج النص من الملف. يرجى التحقق من صحة الملف وإعادة المحاولة.';
                }
            } elseif (str_contains($errorMessage, 'استخراج')) {
                $specificMessage = 'فشل استخراج النص من الملف. يرجى التحقق من صحة الملف وإعادة المحاولة.';
            } elseif (str_contains($errorMessage, 'HTTP')) {
                $specificMessage = 'حدث خطأ في الاتصال بخدمة فحص التشابه. يرجى المحاولة مرة أخرى لاحقاً.';
            }

            // Don't fail the file upload if AI service is unavailable
            // Just mark it as pending and allow manual check later
            $file->update([
                'status' => 'pending',
            ]);

            return [
                'success' => false,
                'error' => $specificMessage . ' سيتم فحص الملف لاحقاً.',
                'similarity_score' => 0,
                'error_details' => $isServiceUnavailable ? 'لتشغيل خدمة Python AI: cd python_ai_service && python main.py' : null,
            ];
        }
    }
}

