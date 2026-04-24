@extends('layouts.app')

@section('title', 'أجزاء المشروع')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Enhanced Success Message with Similarity Data -->
        @if(session('success') && session('similarity_data'))
            @php
                $data = session('similarity_data');
            @endphp
            <div class="mb-6 animate-fade-in">
                <div class="bg-gradient-to-r from-green-50 via-emerald-50 to-teal-50 dark:from-green-900/20 dark:via-emerald-900/20 dark:to-teal-900/20 border-2 border-green-200 dark:border-green-800 rounded-2xl shadow-xl p-6 relative overflow-hidden">
                    <!-- Decorative elements -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-green-200 dark:bg-green-800 opacity-20 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-200 dark:bg-emerald-800 opacity-20 rounded-full -ml-12 -mb-12"></div>
                    
                    <div class="relative z-10">
                        <!-- Header -->
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-green-800 dark:text-green-200 mb-1">
                                    {{ session('success') }}
                                </h3>
                                <p class="text-sm text-green-600 dark:text-green-300">
                                    تم فحص جميع الملفات وتحليلها بنجاح
                                </p>
                            </div>
                        </div>
                        
                        <!-- Statistics Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <!-- Similarity Card -->
                            <div class="bg-white dark:bg-[#161615] rounded-xl p-4 border border-green-200 dark:border-green-800 shadow-md">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-white">أعلى نسبة تشابه</span>
                                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $data['max_similarity'] }}%</span>
                                    @if($data['max_similarity'] < 30)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg font-bold text-sm shadow-md transform hover:scale-105 transition-transform">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            ممتاز
                                        </span>
                                    @elseif($data['max_similarity'] < 50)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-yellow-500 to-orange-600 text-white rounded-lg font-bold text-sm shadow-md transform hover:scale-105 transition-transform">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            مقبول
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-lg font-bold text-sm shadow-md transform hover:scale-105 transition-transform">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            عالية
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- AI Detection Card -->
                            @if($data['max_ai_probability'] !== null)
                            <div class="bg-white dark:bg-[#161615] rounded-xl p-4 border border-blue-200 dark:border-blue-800 shadow-md">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-white">احتمالية AI</span>
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $data['max_ai_probability'] }}%</span>
                                    @if($data['max_ai_probability'] < 30)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg font-bold text-sm shadow-md transform hover:scale-105 transition-transform">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            منخفضة
                                        </span>
                                    @elseif($data['max_ai_probability'] < 70)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-yellow-500 to-orange-600 text-white rounded-lg font-bold text-sm shadow-md transform hover:scale-105 transition-transform">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            متوسطة
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-lg font-bold text-sm shadow-md transform hover:scale-105 transition-transform">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            عالية
                                        </span>
                                    @endif
                                </div>
                                @if($data['files_with_ai'] > 0)
                                    <p class="text-xs text-gray-900 dark:text-gray-200 mt-2">
                                        {{ $data['files_with_ai'] }} ملف يحتوي على احتمالية AI عالية
                                    </p>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Files Count Card -->
                            <div class="bg-white dark:bg-[#161615] rounded-xl p-4 border border-indigo-200 dark:border-indigo-800 shadow-md">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-200">عدد الملفات</span>
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $data['files_count'] }}</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-200">ملف</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-10 rounded-full -ml-32 -mb-32"></div>
                <div class="relative z-10">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                        <div class="flex-1">
                            <h1 class="text-4xl font-bold mb-2">أجزاء المشروع</h1>
                            <p class="text-purple-100 text-lg mb-4">{{ $project->title }}</p>
                            @php
                                $totalPhasesPercentage = $project->total_phases_percentage;
                            @endphp
                            @if($totalPhasesPercentage > 0)
                                <div class="mt-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-purple-100 font-semibold">نسبة الإنجاز الإجمالية</span>
                                        <span class="text-2xl font-bold">{{ number_format($totalPhasesPercentage, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-white/20 backdrop-blur-sm rounded-full h-4 overflow-hidden shadow-inner">
                                        <div class="h-full rounded-full transition-all duration-500
                                            @if($totalPhasesPercentage >= 75) bg-gradient-to-r from-green-400 to-green-600
                                            @elseif($totalPhasesPercentage >= 50) bg-gradient-to-r from-yellow-400 to-yellow-600
                                            @else bg-gradient-to-r from-orange-400 to-orange-600
                                            @endif" 
                                            style="width: {{ $totalPhasesPercentage }}%">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if(!$project->isArchived() && !$project->isProposal() && $totalPhasesPercentage < 100)
                        <a href="{{ route('project-phases.create', $project) }}" class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 hover:bg-white/30 text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            إضافة جزء جديد
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($phases->count() > 0)
            <div class="space-y-4">
                @foreach($phases as $phase)
                    <div class="group relative bg-white dark:bg-[#161615] rounded-2xl shadow-xl p-6 border-2 border-purple-200 dark:border-[#3E3E3A] hover:shadow-2xl hover:border-purple-400 dark:hover:border-purple-600 transition-all duration-300 transform hover:-translate-y-2">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/20 dark:to-pink-900/20 rounded-bl-full opacity-50"></div>
                        <div class="relative z-10">
                            <div class="flex flex-col lg:flex-row justify-between items-start gap-6">
                                <!-- Content Section -->
                                <div class="flex-1 w-full">
                                    <!-- Header Row -->
                                    <div class="flex flex-wrap items-center gap-3 mb-4">
                                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white font-bold text-lg shadow-lg transform hover:scale-110 transition-transform">
                                            {{ $phase->phase_number }}
                                        </span>
                                        <div class="flex-1 min-w-[200px]">
                                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                                {{ $phase->title }}
                                            </h3>
                                        </div>
                                        <!-- Status Badge -->
                                        <span class="px-4 py-2 text-sm font-bold rounded-xl shadow-lg
                                            @if($phase->status == 'approved') bg-gradient-to-r from-green-500 to-emerald-600 text-white
                                            @elseif($phase->status == 'rejected') bg-gradient-to-r from-red-500 to-rose-600 text-white
                                            @elseif($phase->status == 'under_review') bg-gradient-to-r from-yellow-500 to-orange-600 text-white
                                            @else bg-gradient-to-r from-gray-400 to-gray-500 text-white
                                            @endif">
                                            @if($phase->status == 'approved') ✓ معتمد
                                            @elseif($phase->status == 'rejected') ✗ مرفوض
                                            @elseif($phase->status == 'under_review') ⏳ قيد المراجعة
                                            @else ⏸ قيد الانتظار
                                            @endif
                                        </span>
                                        <!-- Percentage Badge -->
                                        @if($phase->percentage)
                                            <span class="px-4 py-2 text-sm font-bold rounded-xl bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500 text-white shadow-lg">
                                                {{ number_format($phase->percentage, 2) }}%
                                            </span>
                                        @endif
                                        <!-- Similarity Badge -->
                                        @if($phase->similarity_score)
                                            <span class="px-4 py-2 text-sm font-bold rounded-xl shadow-lg {{ $phase->similarity_score > 50 ? 'bg-gradient-to-r from-red-500 to-rose-600' : 'bg-gradient-to-r from-blue-500 to-indigo-600' }} text-white">
                                                🔍 {{ number_format($phase->similarity_score, 2) }}%
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Description -->
                                    @if($phase->description)
                                        <p class="text-gray-900 dark:text-gray-200 mb-4 leading-relaxed text-base">{{ $phase->description }}</p>
                                    @endif
                                    
                                    <!-- Rejection Reason -->
                                    @if($phase->rejection_reason)
                                        <div class="mb-4 p-4 bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border-r-4 border-red-400 dark:border-red-600 rounded-lg">
                                            <p class="text-sm font-semibold text-red-800 dark:text-red-200 mb-1">⚠️ سبب الرفض:</p>
                                            <p class="text-sm text-red-700 dark:text-red-300">{{ $phase->rejection_reason }}</p>
                                        </div>
                                    @endif
                                    
                                    <!-- Info Row -->
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-900 dark:text-gray-200">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="font-medium">تم الرفع: {{ $phase->submitted_at->format('Y-m-d H:i') }}</span>
                                        </div>
                                        @if($phase->isArchived())
                                            <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                                </svg>
                                                <span class="font-medium">تم الأرشفة: {{ $phase->archived_at->format('Y-m-d H:i') }}</span>
                                            </div>
                                        @endif
                                        @if($phase->files->count() > 0)
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <span class="font-medium">الملفات: <span class="font-bold text-blue-600">{{ $phase->files->count() }}</span></span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row lg:flex-col gap-3">
                                    @if(!$project->isArchived())
                                    <!-- Check Similarity Button -->
                                    @if($phase->files->count() > 0)
                                        <form action="{{ route('project-phases.check-similarity', $phase) }}" method="POST" class="inline check-phase-similarity-form" onsubmit="return handleCheckPhaseSimilarity(event, this)">
                                            @csrf
                                            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 hover:from-blue-600 hover:via-indigo-600 hover:to-purple-600 text-white rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 font-bold text-sm flex items-center justify-center gap-2 group relative overflow-hidden">
                                                <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
                                                <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="relative z-10">اختبار التشابه</span>
                                                <svg class="w-5 h-5 hidden loading-spinner absolute right-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    @endif
                                    
                                    <!-- View Button -->
                                    <a href="{{ route('project-phases.show', $phase) }}" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 font-bold text-sm flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        عرض
                                    </a>
                                    
                                    @if(!$project->isArchived())
                                    <!-- Edit Button -->
                                    <a href="{{ route('project-phases.edit', $phase) }}" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 font-bold text-sm flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        تعديل
                                    </a>
                                    
                                    <!-- Delete Button -->
                                    <form action="{{ route('project-phases.destroy', $phase) }}" method="POST" class="inline delete-phase-form" onsubmit="return confirm('هل أنت متأكد من حذف هذا الجزء؟ سيتم حذف جميع الملفات المرتبطة به بشكل نهائي ولا يمكن التراجع.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 font-bold text-sm flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span class="text-white">حذف</span>
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
            <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-12 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-full mb-6">
                    <svg class="w-12 h-12 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-xl font-semibold mb-2">لا توجد أجزاء مرفوعة بعد</p>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm mb-6">ابدأ بإضافة جزء جديد للمشروع</p>
                @if(!$project->isArchived() && !$project->isProposal() && ($totalPhasesPercentage ?? 0) < 100)
                <a href="{{ route('project-phases.create', $project) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    إضافة جزء جديد
                </a>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
// Handle phase similarity check
function handleCheckPhaseSimilarity(event, form) {
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
});
</script>
@endsection
