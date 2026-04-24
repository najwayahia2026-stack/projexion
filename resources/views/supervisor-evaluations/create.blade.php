@extends('layouts.app')

@section('title', 'تقييم المشرف للمشروع')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold mb-2">تقييم المشرف للمشروع</h1>
                    <p class="text-gray-600 dark:text-gray-400">المشروع: <span class="font-semibold">{{ $project->title }}</span></p>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">
                        يرجى تقييم المشروع بناءً على المعايير التالية (من 1 إلى 10)
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <ul class="list-disc list-inside text-red-600 dark:text-red-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('supervisor-evaluations.store', $project) }}" method="POST">
                    @csrf

                    <!-- معايير التقييم -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">معايير التقييم</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- جودة العمل -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <label for="work_quality" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    جودة العمل <span class="text-red-500">*</span>
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">تقييم جودة العمل المنجز ودقته</p>
                                <input type="number" name="work_quality" id="work_quality" min="1" max="10" step="0.1"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800"
                                       value="{{ old('work_quality') }}" required>
                                <div class="mt-1 text-xs text-gray-500">من 1 إلى 10</div>
                            </div>

                            <!-- الالتزام بالمواعيد -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <label for="punctuality" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    الالتزام بالمواعيد <span class="text-red-500">*</span>
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">التزام الطلاب بالمواعيد المحددة</p>
                                <input type="number" name="punctuality" id="punctuality" min="1" max="10" step="0.1"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800"
                                       value="{{ old('punctuality') }}" required>
                                <div class="mt-1 text-xs text-gray-500">من 1 إلى 10</div>
                            </div>

                            <!-- التعاون والعمل الجماعي -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <label for="teamwork" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    التعاون والعمل الجماعي <span class="text-red-500">*</span>
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">مستوى التعاون بين أعضاء الفريق</p>
                                <input type="number" name="teamwork" id="teamwork" min="1" max="10" step="0.1"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800"
                                       value="{{ old('teamwork') }}" required>
                                <div class="mt-1 text-xs text-gray-500">من 1 إلى 10</div>
                            </div>

                            <!-- الإبداع والابتكار -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <label for="innovation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    الإبداع والابتكار <span class="text-red-500">*</span>
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">مستوى الإبداع والأفكار المبتكرة</p>
                                <input type="number" name="innovation" id="innovation" min="1" max="10" step="0.1"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800"
                                       value="{{ old('innovation') }}" required>
                                <div class="mt-1 text-xs text-gray-500">من 1 إلى 10</div>
                            </div>

                            <!-- المهارات التقنية -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <label for="technical_skills" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    المهارات التقنية <span class="text-red-500">*</span>
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">مستوى المهارات التقنية المستخدمة</p>
                                <input type="number" name="technical_skills" id="technical_skills" min="1" max="10" step="0.1"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800"
                                       value="{{ old('technical_skills') }}" required>
                                <div class="mt-1 text-xs text-gray-500">من 1 إلى 10</div>
                            </div>

                            <!-- التواصل والتقديم -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <label for="communication" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    التواصل والتقديم <span class="text-red-500">*</span>
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">مستوى التواصل والتقديم والعرض</p>
                                <input type="number" name="communication" id="communication" min="1" max="10" step="0.1"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800"
                                       value="{{ old('communication') }}" required>
                                <div class="mt-1 text-xs text-gray-500">من 1 إلى 10</div>
                            </div>

                            <!-- درجة التقدم -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <label for="progress_score" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    درجة التقدم
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">نسبة التقدم في المشروع</p>
                                <input type="number" name="progress_score" id="progress_score" min="0" max="100" step="0.1"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800"
                                       value="{{ old('progress_score') }}">
                                <div class="mt-1 text-xs text-gray-500">من 0 إلى 100</div>
                            </div>
                        </div>

                        <!-- التقييم الشامل -->
                        <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                            <label for="overall_assessment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                التقييم الشامل <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">تقييم شامل للمشروع ككل (له وزن أكبر في الحساب النهائي)</p>
                            <input type="number" name="overall_assessment" id="overall_assessment" min="1" max="10" step="0.1"
                                   class="w-full px-3 py-2 border border-blue-300 dark:border-blue-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800"
                                   value="{{ old('overall_assessment') }}" required>
                            <div class="mt-1 text-xs text-blue-600 dark:text-blue-400">من 1 إلى 10 (60% من الدرجة النهائية)</div>
                        </div>
                    </div>

                    <!-- الملاحظات التفصيلية -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">ملاحظات تفصيلية</h2>
                        
                        <div class="space-y-6">
                            <div>
                                <label for="strengths" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    نقاط القوة
                                </label>
                                <textarea name="strengths" id="strengths" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800"
                                          placeholder="اذكر نقاط القوة في المشروع...">{{ old('strengths') }}</textarea>
                            </div>

                            <div>
                                <label for="weaknesses" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    نقاط الضعف
                                </label>
                                <textarea name="weaknesses" id="weaknesses" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800"
                                          placeholder="اذكر نقاط الضعف التي تحتاج تحسين...">{{ old('weaknesses') }}</textarea>
                            </div>

                            <div>
                                <label for="recommendations" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    التوصيات
                                </label>
                                <textarea name="recommendations" id="recommendations" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800"
                                          placeholder="اذكر توصياتك للتحسين...">{{ old('recommendations') }}</textarea>
                            </div>

                            <div>
                                <label for="general_comments" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    ملاحظات عامة
                                </label>
                                <textarea name="general_comments" id="general_comments" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800"
                                          placeholder="أي ملاحظات إضافية...">{{ old('general_comments') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- أزرار الإجراءات -->
                    <div class="flex justify-end space-x-3 space-x-reverse pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('projects.show', $project) }}" 
                           class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            إلغاء
                        </a>
                        <button type="submit" name="save_draft" value="1"
                                class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                            حفظ كمسودة
                        </button>
                        <button type="submit" name="submit" value="1"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            إرسال التقييم
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
