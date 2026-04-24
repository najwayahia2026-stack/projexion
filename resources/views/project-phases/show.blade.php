@extends('layouts.app')

@section('title', $phase->title)

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-gradient-to-br from-purple-50 via-pink-50 to-indigo-50 dark:from-[#1a1a18] dark:to-[#161615] rounded-2xl shadow-xl border border-purple-200 dark:border-[#3E3E3A] p-8 mb-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 space-x-reverse mb-4">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white font-bold text-lg shadow-lg">
                            {{ $phase->phase_number }}
                        </span>
                        <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $phase->title }}</h1>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="px-4 py-2 text-sm font-bold rounded-lg shadow-md
                            @if($phase->status == 'approved') bg-gradient-to-r from-green-500 to-emerald-600 text-white
                            @elseif($phase->status == 'rejected') bg-gradient-to-r from-red-500 to-rose-600 text-white
                            @elseif($phase->status == 'under_review') bg-gradient-to-r from-yellow-500 to-orange-600 text-white
                            @else bg-gradient-to-r from-gray-500 to-gray-600 text-white
                            @endif">
                            {{ $phase->status }}
                        </span>
                        @if($phase->percentage)
                            <span class="px-4 py-2 text-sm font-bold rounded-lg bg-gradient-to-r from-purple-500 to-pink-600 text-white shadow-md">
                                {{ $phase->percentage }}%
                            </span>
                        @endif
                        @if($phase->similarity_score)
                            <span class="px-4 py-2 text-sm font-bold rounded-lg {{ $phase->similarity_score > 50 ? 'bg-gradient-to-r from-red-500 to-rose-600' : 'bg-gradient-to-r from-blue-500 to-indigo-600' }} text-white shadow-md">
                                نسبة التشابه: {{ number_format($phase->similarity_score, 1) }}%
                            </span>
                        @endif
                    </div>

                    @if($phase->description)
                        <div class="mb-4 p-4 bg-white/80 dark:bg-[#161615] rounded-lg border border-purple-200 dark:border-[#3E3E3A] backdrop-blur-sm">
                            <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">الوصف</h3>
                            <p class="text-purple-700 dark:text-[#A1A09A] leading-relaxed">{{ $phase->description }}</p>
                        </div>
                    @endif

                    @if($phase->rejection_reason)
                        <div class="mb-4 p-4 bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border-r-4 border-red-400 dark:border-red-600 rounded-lg">
                            <h3 class="font-semibold text-red-800 dark:text-red-200 mb-2">سبب الرفض</h3>
                            <p class="text-red-700 dark:text-red-300">{{ $phase->rejection_reason }}</p>
                        </div>
                    @endif

                    <div class="flex items-center space-x-4 space-x-reverse text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            تم الرفع: {{ $phase->submitted_at->format('Y-m-d H:i') }}
                        </div>
                        @if($phase->reviewed_at)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                تم المراجعة: {{ $phase->reviewed_at->format('Y-m-d H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('project-phases.index', $phase->project) }}" class="px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 hover:bg-white/30 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 font-semibold">
                        العودة
                    </a>
                    @can('approve projects')
                    @if($phase->status == 'pending' || $phase->status == 'under_review')
                        <form action="{{ route('project-phases.approve', $phase) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 font-semibold">
                                قبول
                            </button>
                        </form>
                        <button type="button" onclick="showRejectPhaseModal()" class="px-6 py-3 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-lg hover:from-red-600 hover:to-rose-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 font-semibold">
                            رفض
                        </button>
                    @endif
                    @endcan
                </div>
            </div>
        </div>

        <!-- Files Section -->
        <div class="bg-gradient-to-br from-white to-blue-50 dark:from-[#1a1a18] dark:to-[#161615] rounded-2xl shadow-xl border border-blue-200 dark:border-[#3E3E3A] p-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
                <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">الملفات المرفوعة</h2>
                @if($phase->status != 'rejected')
                <form action="{{ route('project-phases.upload-file', $phase) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
                    @csrf
                    <label class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 font-semibold cursor-pointer">
                        <svg class="w-5 h-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        رفع ملف جديد
                        <input type="file" name="file" required accept=".doc,.docx,.pdf" class="hidden" onchange="this.form.submit()">
                    </label>
                </form>
                @endif
            </div>

            @if($phase->files->count() > 0)
                <div class="space-y-4">
                    @foreach($phase->files as $file)
                        <div class="group relative bg-gradient-to-br from-white to-blue-50 dark:bg-[#161615] rounded-xl shadow-lg p-6 border border-blue-200 dark:border-[#3E3E3A] hover:shadow-xl hover:border-blue-400 dark:hover:border-blue-700 transition-all duration-300 transform hover:-translate-y-1">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 rounded-bl-full"></div>
                            <div class="relative z-10">
                                <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-3 mb-3">
                                            <h4 class="text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $file->original_name }}</h4>
                                            <span class="px-3 py-1.5 text-xs font-bold rounded-lg shadow-md
                                                @if($file->status == 'approved') bg-gradient-to-r from-green-500 to-emerald-600 text-white
                                                @elseif($file->status == 'rejected') bg-gradient-to-r from-red-500 to-rose-600 text-white
                                                @elseif($file->status == 'checked') bg-gradient-to-r from-blue-500 to-indigo-600 text-white
                                                @else bg-gradient-to-r from-gray-500 to-gray-600 text-white
                                                @endif">
                                                {{ $file->status }}
                                            </span>
                                            @if($file->similarity_score !== null && $file->similarity_score > 0)
                                                <span class="px-3 py-1.5 text-xs font-bold rounded-lg shadow-md {{ $file->similarity_score > 50 ? 'bg-gradient-to-r from-red-500 to-rose-600' : 'bg-gradient-to-r from-blue-500 to-indigo-600' }} text-white">
                                                    تشابه: {{ number_format($file->similarity_score, 1) }}%
                                                </span>
                                            @endif
                                            @if($file->ai_probability !== null && $file->ai_probability > 0)
                                                <span class="px-3 py-1.5 text-xs font-bold rounded-lg shadow-md {{ $file->ai_probability >= 70 ? 'bg-gradient-to-r from-red-500 to-rose-600' : ($file->ai_probability >= 50 ? 'bg-gradient-to-r from-yellow-500 to-orange-600' : 'bg-gradient-to-r from-blue-500 to-indigo-600') }} text-white">
                                                    AI: {{ number_format($file->ai_probability, 1) }}%
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex flex-wrap items-center gap-4 text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                النوع: {{ $file->file_type == 'word' ? 'Word' : 'PDF' }}
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                                </svg>
                                                الحجم: {{ $file->formatted_size }}
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                تم الرفع: {{ $file->created_at->format('Y-m-d H:i') }}
                                            </div>
                                        </div>

                                        @if($file->rejection_reason)
                                            <div class="mb-3 p-4 bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border-r-4 border-red-400 dark:border-red-600 rounded-lg">
                                                <p class="text-sm text-red-800 dark:text-red-200 font-semibold mb-1">سبب الرفض:</p>
                                                <p class="text-sm text-red-700 dark:text-red-300">{{ $file->rejection_reason }}</p>
                                            </div>
                                        @endif

                                        <!-- AI Probability Info Card -->
                                        @if($file->ai_probability !== null && $file->ai_probability > 0)
                                            <div class="mb-3 p-4 rounded-lg border-2 {{ $file->ai_probability >= 70 ? 'bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-700' : ($file->ai_probability >= 50 ? 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-300 dark:border-yellow-700' : 'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-700') }}">
                                                <div class="flex items-center justify-between mb-2">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-5 h-5 {{ $file->ai_probability >= 70 ? 'text-red-600 dark:text-red-400' : ($file->ai_probability >= 50 ? 'text-yellow-600 dark:text-yellow-400' : 'text-blue-600 dark:text-blue-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                                        </svg>
                                                        <p class="text-sm font-bold {{ $file->ai_probability >= 70 ? 'text-red-800 dark:text-red-200' : ($file->ai_probability >= 50 ? 'text-yellow-800 dark:text-yellow-200' : 'text-blue-800 dark:text-blue-200') }}">
                                                            احتمالية كون الملف منشأ بالذكاء الاصطناعي
                                                        </p>
                                                    </div>
                                                    <span class="text-2xl font-bold {{ $file->ai_probability >= 70 ? 'text-red-600 dark:text-red-400' : ($file->ai_probability >= 50 ? 'text-yellow-600 dark:text-yellow-400' : 'text-blue-600 dark:text-blue-400') }}">
                                                        {{ number_format($file->ai_probability, 1) }}%
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                                                    <div class="h-2.5 rounded-full {{ $file->ai_probability >= 70 ? 'bg-red-600' : ($file->ai_probability >= 50 ? 'bg-yellow-500' : 'bg-blue-500') }}" style="width: {{ $file->ai_probability }}%"></div>
                                                </div>
                                                <p class="text-xs {{ $file->ai_probability >= 70 ? 'text-red-700 dark:text-red-300' : ($file->ai_probability >= 50 ? 'text-yellow-700 dark:text-yellow-300' : 'text-blue-700 dark:text-blue-300') }}">
                                                    @if($file->ai_probability >= 70)
                                                        ⚠️ احتمالية عالية جداً - قد يكون الملف منشأ بالذكاء الاصطناعي
                                                    @elseif($file->ai_probability >= 50)
                                                        ⚠️ احتمالية متوسطة - قد يحتوي الملف على محتوى منشأ بالذكاء الاصطناعي
                                                    @else
                                                        ✓ احتمالية منخفضة - الملف يبدو مكتوباً بشكل طبيعي
                                                    @endif
                                                </p>
                                            </div>
                                        @endif

                                        @if($file->similar_files && count($file->similar_files) > 0)
                                            <div class="mb-3 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                                <p class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">الملفات المشابهة:</p>
                                                <ul class="space-y-1">
                                                    @foreach(array_slice($file->similar_files, 0, 5) as $similar)
                                                        <li class="text-sm text-blue-700 dark:text-blue-300 flex items-center">
                                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            {{ $similar['file_name'] }} - {{ number_format($similar['similarity'], 1) }}%
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('phase-files.download', $file) }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 text-sm font-semibold flex items-center">
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            تحميل
                                        </a>
                                        @if($file->status == 'pending')
                                            <form action="{{ route('phase-files.check-similarity', $file) }}" method="POST" class="inline check-file-similarity-form" onsubmit="return handleCheckFileSimilarity(event, this)">
                                                @csrf
                                                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-purple-500 via-pink-500 to-rose-600 hover:from-purple-600 hover:via-pink-600 hover:to-rose-700 text-white rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 text-sm font-bold flex items-center gap-2 group relative overflow-hidden">
                                                    <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
                                                    <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="relative z-10">فحص التشابه</span>
                                                    <svg class="w-4 h-4 hidden loading-spinner absolute right-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-full mb-6">
                        <svg class="w-12 h-12 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] text-lg font-semibold">لا توجد ملفات مرفوعة</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Phase Modal -->
@can('approve projects')
@if($phase->status == 'pending' || $phase->status == 'under_review' || $errors->has('rejection_reason'))
<div id="rejectPhaseModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4" onclick="if(event.target === this) closeRejectPhaseModal()">
    <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-2xl max-w-md w-full p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] relative z-[10000]" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">رفض جزء المشروع</h3>
            <button type="button" onclick="closeRejectPhaseModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('project-phases.reject', $phase) }}" method="POST" id="rejectPhaseForm">
            @csrf
            <div class="mb-4">
                <label for="phase_rejection_reason" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">سبب الرفض <span class="text-red-500">*</span></label>
                @error('rejection_reason')
                    <p class="text-sm text-red-600 dark:text-red-400 mb-2">{{ $message }}</p>
                @enderror
                <textarea id="phase_rejection_reason" name="rejection_reason" rows="4" required
                    class="block w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-[#2a2a28] text-[#1b1b18] dark:text-[#EDEDEC] placeholder:text-gray-500 dark:placeholder:text-gray-400"
                    placeholder="أدخل سبب الرفض...">{{ old('rejection_reason') }}</textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeRejectPhaseModal()"
                    class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#3E3E3A] rounded-lg hover:bg-gray-200 dark:hover:bg-[#4E4E4A] transition-all duration-200">
                    إلغاء
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-200 font-medium">
                    تأكيد الرفض
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endcan

@if(session('warning') || session('help'))
<div class="fixed bottom-4 left-4 right-4 lg:left-auto lg:right-4 lg:w-96 z-50 animate-fade-in">
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border-r-4 border-yellow-400 dark:border-yellow-600 rounded-lg shadow-xl p-4">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div class="flex-1">
                <h3 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-1">تنبيه</h3>
                <p class="text-sm text-yellow-700 dark:text-yellow-300 mb-2">{{ session('warning') ?? session('help') }}</p>
                @if(session('help'))
                <div class="mt-3 p-3 bg-gray-50 dark:bg-[#161615] rounded border border-yellow-200 dark:border-yellow-800">
                    <p class="text-xs font-mono text-yellow-800 dark:text-yellow-200">cd python_ai_service && pip install -r requirements.txt && python main.py</p>
                </div>
                @endif
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
@endif

<script>
function showRejectPhaseModal() {
    const modal = document.getElementById('rejectPhaseModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            document.getElementById('phase_rejection_reason')?.focus();
        }, 100);
    }
}

function closeRejectPhaseModal() {
    const modal = document.getElementById('rejectPhaseModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function handleCheckFileSimilarity(event, form) {
    const button = form.querySelector('button[type="submit"]');
    const spinner = button.querySelector('.loading-spinner');
    const buttonText = button.querySelector('span');
    
    // Disable button and show loading
    button.disabled = true;
    button.classList.add('opacity-75', 'cursor-not-allowed');
    spinner.classList.remove('hidden');
    buttonText.textContent = 'جاري الفحص...';
    
    // Allow form to submit
    return true;
}

// Add smooth scroll to success message
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.animate-fade-in')) {
        setTimeout(() => {
            document.querySelector('.animate-fade-in').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }, 300);
    }
    @if($errors->has('rejection_reason'))
    showRejectPhaseModal();
    @endif
});
</script>

@endsection
