@extends('layouts.app')

@section('title', 'تقييم المشروع')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-6">تقييم المشروع: {{ $project->title }}</h1>

                <form action="{{ route('evaluations.store', $project) }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="proposal_score" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                درجة الاقتراح (20%)
                            </label>
                            <input type="number" name="proposal_score" id="proposal_score" min="0" max="100" step="0.1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                                   value="{{ old('proposal_score') }}">
                        </div>

                        <div>
                            <label for="objectives_achievement" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                تحقيق الأهداف (30%)
                            </label>
                            <input type="number" name="objectives_achievement" id="objectives_achievement" min="0" max="100" step="0.1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                                   value="{{ old('objectives_achievement') }}">
                        </div>

                        <div>
                            <label for="final_score" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                الدرجة النهائية (40%)
                            </label>
                            <input type="number" name="final_score" id="final_score" min="0" max="100" step="0.1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                                   value="{{ old('final_score') }}">
                        </div>

                        <div>
                            <label for="general_score" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                التقييم العام (10%)
                            </label>
                            <input type="number" name="general_score" id="general_score" min="0" max="100" step="0.1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                                   value="{{ old('general_score') }}">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="comments" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            التعليقات
                        </label>
                        <textarea name="comments" id="comments" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900">{{ old('comments') }}</textarea>
                    </div>

                    <div class="flex justify-end space-x-3 space-x-reverse">
                        <a href="{{ route('projects.show', $project) }}" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                            إلغاء
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            حفظ التقييم
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
