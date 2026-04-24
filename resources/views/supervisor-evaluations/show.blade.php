@extends('layouts.app')

@section('title', 'عرض تقييم المشرف')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="mb-6 flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">تقييم المشرف للمشروع</h1>
                        <p class="text-gray-600 dark:text-gray-400">المشروع: <span class="font-semibold">{{ $supervisorEvaluation->project->title }}</span></p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">
                            المشرف: <span class="font-medium">{{ $supervisorEvaluation->supervisor->name }}</span>
                        </p>
                    </div>
                    <div class="text-left">
                        <span class="px-3 py-1 rounded text-sm font-medium
                            @if($supervisorEvaluation->status === 'draft') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                            @elseif($supervisorEvaluation->status === 'submitted') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                            @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                            @endif">
                            @if($supervisorEvaluation->status === 'draft') مسودة
                            @elseif($supervisorEvaluation->status === 'submitted') تم الإرسال
                            @else مقرر نهائياً
                            @endif
                        </span>
                        @if($supervisorEvaluation->evaluated_at)
                            <p class="text-xs text-gray-500 mt-2">
                                تاريخ التقييم: {{ $supervisorEvaluation->evaluated_at->format('Y-m-d H:i') }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- الدرجة الإجمالية -->
                <div class="mb-8 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 p-6 rounded-lg border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-1">الدرجة الإجمالية</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">التقييم الشامل للمشروع</p>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">
                                {{ number_format($supervisorEvaluation->total_score ?? 0, 2) }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">من 100</div>
                            <div class="text-xs text-blue-600 dark:text-blue-400 mt-1 font-medium">
                                {{ $supervisorEvaluation->rating_text }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- معايير التقييم -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">معايير التقييم</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">جودة العمل</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $supervisorEvaluation->work_quality ?? 'N/A' }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($supervisorEvaluation->work_quality ?? 0) * 10 }}%"></div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">الالتزام بالمواعيد</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $supervisorEvaluation->punctuality ?? 'N/A' }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($supervisorEvaluation->punctuality ?? 0) * 10 }}%"></div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">التعاون والعمل الجماعي</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $supervisorEvaluation->teamwork ?? 'N/A' }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($supervisorEvaluation->teamwork ?? 0) * 10 }}%"></div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">الإبداع والابتكار</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $supervisorEvaluation->innovation ?? 'N/A' }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-pink-600 h-2 rounded-full" style="width: {{ ($supervisorEvaluation->innovation ?? 0) * 10 }}%"></div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">المهارات التقنية</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $supervisorEvaluation->technical_skills ?? 'N/A' }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($supervisorEvaluation->technical_skills ?? 0) * 10 }}%"></div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">التواصل والتقديم</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $supervisorEvaluation->communication ?? 'N/A' }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-teal-600 h-2 rounded-full" style="width: {{ ($supervisorEvaluation->communication ?? 0) * 10 }}%"></div>
                            </div>
                        </div>

                        @if($supervisorEvaluation->progress_score !== null)
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">درجة التقدم</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ number_format($supervisorEvaluation->progress_score, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-orange-600 h-2 rounded-full" style="width: {{ $supervisorEvaluation->progress_score }}%"></div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- التقييم الشامل -->
                    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">التقييم الشامل</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">(60% من الدرجة النهائية)</p>
                            </div>
                            <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $supervisorEvaluation->overall_assessment ?? 'N/A' }}</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full" style="width: {{ ($supervisorEvaluation->overall_assessment ?? 0) * 10 }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- الملاحظات التفصيلية -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">ملاحظات تفصيلية</h2>
                    
                    <div class="space-y-6">
                        @if($supervisorEvaluation->strengths)
                        <div>
                            <h3 class="text-sm font-semibold text-green-700 dark:text-green-400 mb-2 flex items-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                نقاط القوة
                            </h3>
                            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $supervisorEvaluation->strengths }}</p>
                            </div>
                        </div>
                        @endif

                        @if($supervisorEvaluation->weaknesses)
                        <div>
                            <h3 class="text-sm font-semibold text-orange-700 dark:text-orange-400 mb-2 flex items-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                نقاط الضعف
                            </h3>
                            <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg border border-orange-200 dark:border-orange-800">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $supervisorEvaluation->weaknesses }}</p>
                            </div>
                        </div>
                        @endif

                        @if($supervisorEvaluation->recommendations)
                        <div>
                            <h3 class="text-sm font-semibold text-blue-700 dark:text-blue-400 mb-2 flex items-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                التوصيات
                            </h3>
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $supervisorEvaluation->recommendations }}</p>
                            </div>
                        </div>
                        @endif

                        @if($supervisorEvaluation->general_comments)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">ملاحظات عامة</h3>
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $supervisorEvaluation->general_comments }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- أزرار الإجراءات -->
                <div class="flex justify-end space-x-3 space-x-reverse pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('projects.show', $supervisorEvaluation->project) }}" 
                       class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        العودة للمشروع
                    </a>
                    @auth
                        @if(Auth::user()->hasRole('supervisor') && Auth::id() === $supervisorEvaluation->supervisor_id && $supervisorEvaluation->status !== 'finalized')
                            <a href="{{ route('supervisor-evaluations.edit', $supervisorEvaluation) }}" 
                               class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                تعديل التقييم
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
