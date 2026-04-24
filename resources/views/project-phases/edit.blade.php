@extends('layouts.app')

@section('title', 'تعديل الجزء')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            <h1 class="text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                تعديل الجزء
            </h1>
            <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $phase->title }}</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-[#161615] rounded-xl shadow-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-8 lg:p-12 animate-fade-in" style="animation-delay: 0.1s">
            <form action="{{ route('project-phases.update', $phase) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            عنوان الجزء *
                        </label>
                        <input type="text" name="title" id="title" required
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               value="{{ old('title', $phase->title) }}">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            وصف الجزء
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none">{{ old('description', $phase->description) }}</textarea>
                    </div>

                    <div>
                        <label for="percentage" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            نسبة هذا الجزء من المشروع (%)
                        </label>
                        <input type="number" name="percentage" id="percentage" min="0" max="100" step="0.01"
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               value="{{ old('percentage', $phase->percentage) }}">
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-2">النسبة المئوية لهذا الجزء من إجمالي المشروع (0-100)</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <a href="{{ route('project-phases.index', $phase->project) }}" class="px-6 py-3 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg text-sm font-semibold transition-all duration-200">
                        إلغاء
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 text-sm font-semibold">
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

