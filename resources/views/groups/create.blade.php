@extends('layouts.app')

@section('title', 'إنشاء مجموعة جديدة')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    إنشاء مجموعة جديدة
                </span>
            </h1>
            <p class="text-gray-900 dark:text-gray-200">إنشاء مجموعة طلابية جديدة لإدارة مشاريع التخرج</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100 animate-fade-in" style="animation-delay: 0.1s">
            <form action="{{ route('groups.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                اسم المجموعة *
                            </span>
                        </label>
                        <input type="text" name="name" id="name" required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 placeholder:text-gray-400 text-gray-900 bg-white"
                               placeholder="مثال: مجموعة الذكاء الاصطناعي" value="{{ old('name') }}">
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 ml-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                كود المجموعة *
                            </span>
                        </label>
                        <input type="text" name="code" id="code" required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 font-mono placeholder:text-gray-400 text-gray-900 bg-white"
                               placeholder="مثال: AI-2024-01" value="{{ old('code') }}">
                        <p class="text-xs text-gray-900 dark:text-gray-200 mt-2">سيستخدم هذا الكود للانضمام للمجموعة (يجب أن يكون فريداً)</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="supervisor_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 ml-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    المشرف *
                                </span>
                            </label>
                            <select name="supervisor_id" id="supervisor_id" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900">
                                <option value="" class="text-gray-400">اختر المشرف</option>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                        {{ $supervisor->name }} - {{ $supervisor->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="academic_year" class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 ml-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    السنة الأكاديمية *
                                </span>
                            </label>
                            <input type="number" name="academic_year" id="academic_year" required
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 placeholder:text-gray-400 text-gray-900 bg-white"
                                   placeholder="2024" value="{{ old('academic_year', date('Y')) }}" min="2020" max="2100">
                        </div>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 ml-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                الحالة
                            </span>
                        </label>
                        <select name="status" id="status"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>نشطة</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>مؤرشفة</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('groups.index') }}" 
                       class="px-6 py-3 border-2 border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200 font-medium text-gray-700 hover:text-gray-900">
                        إلغاء
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-medium">
                        إنشاء المجموعة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

