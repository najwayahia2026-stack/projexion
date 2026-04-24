@extends('layouts.app')

@section('title', $project->title)

@section('content')

<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Enhanced Success Message with Similarity Data (مخفية عن الطالب لضمان نزاهة العمل) -->
        @if(session('success') && session('similarity_data') && !Auth::user()->hasRole('student'))
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
                                    تم فحص جميع ملفات الأجزاء وتحليلها بنجاح
                                </p>
                            </div>
                        </div>
                        
                        <!-- Statistics Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                            <!-- Files Count Card -->
                            <div class="bg-white dark:bg-[#161615] rounded-xl p-4 border border-indigo-200 dark:border-indigo-800 shadow-md">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-white">الملفات المفحوصة</span>
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $data['files_count'] }}</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-200">من {{ $data['total_files'] ?? $data['files_count'] }}</span>
                                </div>
                            </div>

                            <!-- Phases Count Card -->
                            @if(isset($data['phases_count']))
                            <div class="bg-white dark:bg-[#161615] rounded-xl p-4 border border-purple-200 dark:border-purple-800 shadow-md">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-white">الأجزاء المفحوصة</span>
                                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $data['phases_count'] }}</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-200">جزء</span>
                                </div>
                            </div>
                            @endif
                            
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
                                @if(isset($data['files_with_ai']) && $data['files_with_ai'] > 0)
                                    <p class="text-xs text-gray-900 dark:text-gray-200 mt-2">
                                        {{ $data['files_with_ai'] }} ملف يحتوي على احتمالية AI عالية
                                    </p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header Card -->
        <div class="mb-8 animate-fade-in">
            <div class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-2xl shadow-2xl p-8 lg:p-12 overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-10 rounded-full -ml-32 -mb-32"></div>
                <div class="relative z-10">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-bold mb-3 text-white">{{ $project->title }}</h1>
                            <div class="flex flex-wrap items-center gap-3">
                                @php
                                    $statusTranslations = [
                                        'pending' => 'اقتراح - انتظار موافقة المشرف',
                                        'approved' => 'مقبول',
                                        'rejected' => 'مرفوض',
                                        'in_progress' => 'قيد التنفيذ',
                                        'completed' => 'مكتمل',
                                        'archived' => 'مؤرشف',
                                    ];
                                    $statusText = $statusTranslations[$project->status] ?? $project->status;
                                @endphp
                                <span class="px-4 py-2 text-sm font-semibold rounded-lg backdrop-blur-sm
                                    @if($project->status == 'approved') bg-green-500/20 text-green-100 border border-green-300/30
                                    @elseif($project->status == 'rejected') bg-red-500/20 text-red-100 border border-red-300/30
                                    @elseif($project->status == 'pending') bg-yellow-500/20 text-yellow-100 border border-yellow-300/30
                                    @elseif($project->status == 'in_progress') bg-blue-500/20 text-blue-100 border border-blue-300/30
                                    @else bg-gray-500/20 text-gray-100 border border-gray-300/30
                                    @endif">
                                    {{ $statusText }}
                                </span>
                                @if($project->similarity_score)
                                    <span class="px-4 py-2 text-sm font-semibold rounded-lg backdrop-blur-sm bg-white/20 text-white border border-white/30">
                                        نسبة التشابه: <span class="{{ $project->similarity_score >= 70 ? 'text-red-200' : 'text-white' }}">{{ number_format($project->similarity_score, 1) }}%</span>
                                    </span>
                                @endif
                                @if($project->isArchived())
                                    <span class="px-4 py-2 text-sm font-semibold rounded-lg backdrop-blur-sm bg-amber-500/30 text-amber-100 border border-amber-300/30 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                        </svg>
                                        مؤرشف - {{ $project->archived_at->format('Y-m-d H:i') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($project->isProposal() && Auth::user()->hasRole('student'))
                        <div class="mt-4 p-4 bg-amber-50 dark:bg-amber-900/20 border-r-4 border-amber-400 rounded-xl">
                            <p class="text-sm text-amber-800 dark:text-amber-200">هذا المشروع قيد المراجعة. انتظر موافقة المشرف على الاقتراح لإضافة أجزاء المشروع ورفع الملفات.</p>
                        </div>
                        @endif
                        @if($project->status == 'in_progress' && !$project->isReadyForFinalApproval() && (Auth::user()->hasRole('supervisor') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('department_admin')))
                        <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border-r-4 border-blue-400 rounded-xl">
                            <p class="text-sm text-blue-800 dark:text-blue-200">قيد التنفيذ: نسبة الأجزاء المعتمدة {{ number_format($project->approved_phases_percentage, 1) }}%. أكمِل الموافقة على الأجزاء في صفحة أجزاء المشروع حتى 100% للتمكن من قبول أو رفض المشروع نهائياً.</p>
                        </div>
                        @endif
                        <div class="flex flex-wrap gap-2">
                            @php
                                // المشاريع المؤرشفة للقراءة فقط - إخفاء أزرار التعديل والحذف لجميع المستخدمين
                                $isArchivedReadOnly = $project->isArchived();
                            @endphp
                            
                            @if(!$isArchivedReadOnly)
                                @can('edit projects')
                                <a href="{{ route('projects.edit', $project) }}" class="px-5 py-2.5 bg-white/20 backdrop-blur-sm border border-white/30 hover:bg-white/30 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                                    تعديل
                                </a>
                                @endcan
                                @can('delete projects')
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟ سيتم حذف جميع البيانات المرتبطة به.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-5 py-2.5 bg-red-500/20 backdrop-blur-sm border border-red-300/30 hover:bg-red-500/30 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                                        حذف
                                    </button>
                                </form>
                                @endcan
                            @endif
                            @can('approve projects')
                            @if(!Auth::user()->hasRole('committee') || Auth::user()->hasRole('admin'))
                                {{-- مرحلة الاقتراح: قبول/رفض الاقتراح فقط --}}
                                @if($project->status == 'pending')
                                    <form action="{{ route('projects.approve-proposal', $project) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-5 py-2.5 bg-green-500/20 backdrop-blur-sm border border-green-300/30 hover:bg-green-500/30 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                                            قبول الاقتراح
                                        </button>
                                    </form>
                                    <button type="button" onclick="showRejectModal()" class="px-5 py-2.5 bg-red-500/20 backdrop-blur-sm border border-red-300/30 hover:bg-red-500/30 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                                        رفض الاقتراح
                                    </button>
                                @elseif($project->status == 'in_progress' && $project->isReadyForFinalApproval())
                                    {{-- اكتملت الأجزاء 100%: قبول/رفض المشروع نهائياً --}}
                                    <form action="{{ route('projects.approve', $project) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-5 py-2.5 bg-green-500/20 backdrop-blur-sm border border-green-300/30 hover:bg-green-500/30 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                                            قبول المشروع
                                        </button>
                                    </form>
                                    <button type="button" onclick="showRejectModal()" class="px-5 py-2.5 bg-red-500/20 backdrop-blur-sm border border-red-300/30 hover:bg-red-500/30 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                                        رفض المشروع
                                    </button>
                                @endif
                            @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Details Card -->
        <div class="bg-white dark:bg-[#161615] rounded-xl shadow-lg border border-[#e3e3e0] dark:border-[#3E3E3A] mb-6 overflow-hidden">
            <div class="p-6 lg:p-12">

                @php
                    $totalPhasesPercentage = $project->total_phases_percentage;
                @endphp
                @if($totalPhasesPercentage > 0 || $project->progress_percentage > 0)
                    <div class="mb-8">
                        @if($totalPhasesPercentage > 0)
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">نسبة الإنجاز من الأجزاء</span>
                                    <span class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ number_format($totalPhasesPercentage, 1) }}%</span>
                                </div>
                                <div class="w-full bg-[#e3e3e0] dark:bg-[#3E3E3A] rounded-full h-4 overflow-hidden shadow-inner">
                                    <div class="h-full rounded-full transition-all duration-500
                                        @if($totalPhasesPercentage >= 75) bg-gradient-to-r from-green-500 via-emerald-500 to-green-600
                                        @elseif($totalPhasesPercentage >= 50) bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600
                                        @else bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600
                                        @endif" 
                                        style="width: {{ $totalPhasesPercentage }}%"></div>
                                </div>
                            </div>
                        @endif
                        @if($project->progress_percentage > 0)
                            <div>
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">نسبة الإنجاز من الأقسام</span>
                                    <span class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $project->progress_percentage }}%</span>
                                </div>
                                <div class="w-full bg-[#e3e3e0] dark:bg-[#3E3E3A] rounded-full h-4 overflow-hidden shadow-inner">
                                    <div class="h-full rounded-full bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 transition-all duration-500" 
                                        style="width: {{ $project->progress_percentage }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-2 text-[#1b1b18] dark:text-[#EDEDEC]">الوصف</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $project->description }}</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2 text-[#1b1b18] dark:text-[#EDEDEC]">الأهداف</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $project->objectives }}</p>
                    </div>
                </div>

                @if($project->technologies)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2 text-[#1b1b18] dark:text-[#EDEDEC]">التقنيات المستخدمة</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $project->technologies }}</p>
                    </div>
                @endif

                @if($project->group)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2 text-[#1b1b18] dark:text-[#EDEDEC]">المجموعة</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $project->group->name }} ({{ $project->group->code }})</p>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">المشرف: {{ $project->group->supervisor->name }}</p>
                    </div>
                @endif

                @if($project->status == 'approved' && $project->supervisor_notes)
                    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold mb-2 text-green-800 dark:text-green-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ملاحظات المشرف
                        </h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $project->supervisor_notes }}</p>
                        @if($project->approved_at)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                تم القبول في: {{ $project->approved_at->format('Y-m-d H:i') }}
                            </p>
                        @endif
                    </div>
                @endif

                @if($project->status == 'rejected' && $project->rejection_reason)
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold mb-2 text-red-800 dark:text-red-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                            سبب الرفض
                        </h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $project->rejection_reason }}</p>
                        @if($project->rejected_at)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                تم الرفض في: {{ $project->rejected_at->format('Y-m-d H:i') }}
                            </p>
                        @endif
                    </div>
                @endif

                <div class="flex space-x-2 space-x-reverse mt-6">
                    <a href="{{ route('project-phases.index', $project) }}" class="px-5 py-1.5 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm text-sm leading-normal transition-all duration-200">
                        أجزاء المشروع
                    </a>
                    <div class="flex flex-wrap gap-4 mt-10 no-print">
    
    <a href="{{ route('projects.show', [$project->id, 'print' => 1]) }}" 
       target="_blank" 
       class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition-all">
       طباعة التقرير
    </a>

    <a href="{{ route('projects.show', [$project->id, 'print' => 1, 'download' => 1]) }}" 
       target="_blank" 
       class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg shadow-md transition-all">
       تحميل PDF
    </a>

</div>
                    @if(!$project->isArchived())
                    @if(!Auth::user()->hasRole('student'))
                    <form action="{{ route('projects.check-similarity', $project) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-1.5 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] border border-black dark:border-[#eeeeec] hover:bg-black dark:hover:bg-white rounded-sm text-sm leading-normal transition-all duration-200">
                            فحص التشابه
                        </button>
                    </form>
                    @endif
    
                    @if(!Auth::user()->hasRole('student') && $project->phases->count() > 0 && $project->phases->sum(function($phase) { return $phase->files->count(); }) > 0)
                    <form action="{{ route('projects.check-all-phases-similarity', $project) }}" method="POST" class="inline check-all-similarity-form" onsubmit="return handleCheckAllSimilarity(event)">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 hover:from-indigo-600 hover:via-purple-600 hover:to-pink-600 text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl flex items-center gap-2 group relative overflow-hidden">
                            <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
                            <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="relative z-10">اختبار التشابه على جميع الأجزاء</span>
                            <svg class="w-5 h-5 hidden loading-spinner absolute right-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </form>
                    @endif
                    @endif
                    {{-- اللجنة تُقيّم المشاريع المؤرشفة فقط --}}
                    @if(Auth::user()->hasRole('committee') && $project->isArchived())
                    @php
                        $userEvaluated = $project->evaluations->contains('evaluator_id', Auth::id());
                    @endphp
                    @if($userEvaluated)
                        <a href="{{ route('evaluations.index') }}" class="px-5 py-1.5 bg-green-600 dark:bg-green-500 text-white border border-green-700 dark:border-green-400 rounded-sm text-sm leading-normal transition-all duration-200">
                            تم التقييم ✓
                        </a>
                    @else
                        <a href="{{ route('evaluations.create', $project) }}" class="px-5 py-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white border border-blue-700 dark:border-blue-500 hover:from-blue-700 hover:to-indigo-700 rounded-sm text-sm leading-normal transition-all duration-200 font-medium">
                            تقييم المشروع
                        </a>
                    @endif
                    @endif
                    @if(Auth::user()->hasRole('supervisor') && $project->group)
                        @php
                            $supervisedGroups = Auth::user()->supervisedGroups()->pluck('id');
                            $canEvaluate = in_array($project->group_id, $supervisedGroups->toArray());
                            $existingSupervisorEvaluation = $project->supervisorEvaluations()->where('supervisor_id', Auth::id())->first();
                        @endphp
                        @if($canEvaluate)
                            @if($existingSupervisorEvaluation)
                                <a href="{{ route('supervisor-evaluations.show', $existingSupervisorEvaluation) }}" class="px-5 py-1.5 bg-green-600 dark:bg-green-500 text-white border border-green-700 dark:border-green-400 hover:bg-green-700 dark:hover:bg-green-600 rounded-sm text-sm leading-normal transition-all duration-200">
                                    عرض تقييم المشرف
                                </a>
                            @elseif(!$project->isArchived())
                                <a href="{{ route('supervisor-evaluations.create', $project) }}" class="px-5 py-1.5 bg-green-600 dark:bg-green-500 text-white border border-green-700 dark:border-green-400 hover:bg-green-700 dark:hover:bg-green-600 rounded-sm text-sm leading-normal transition-all duration-200">
                                    تقييم المشرف
                                </a>
                            @endif
                        @endif
                    @endif
                    @if($project->evaluations->count() > 0)
                    <a href="{{ route('evaluations.pdf', $project) }}" class="px-5 py-1.5 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm text-sm leading-normal transition-all duration-200">
                        تحميل PDF
                    </a>
                    @endif
                </div>

                @if($project->evaluations->count() > 0 || $project->supervisorEvaluations->count() > 0)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-3 text-[#1b1b18] dark:text-[#EDEDEC]">التقييمات</h3>
                        
                        <!-- تقييمات المقيمين -->
                        @if($project->evaluations->count() > 0)
                        <div class="mb-6">
                            <h4 class="text-md font-medium mb-2 text-[#706f6c] dark:text-[#A1A09A]">تقييمات المقيمين</h4>
                            <div class="space-y-4">
                                @foreach($project->evaluations as $evaluation)
                                <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-4 bg-white dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)]">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <span class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $evaluation->evaluator->name }}</span>
                                            <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">- {{ $evaluation->evaluated_at ? $evaluation->evaluated_at->format('Y-m-d') : 'N/A' }}</span>
                                            @if($evaluation->evaluation_type)
                                                <span class="text-xs px-2 py-1 rounded-full {{ $evaluation->evaluation_type === 'committee' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }} mr-2">
                                                    {{ $evaluation->evaluation_type === 'committee' ? 'تقييم اللجنة' : 'تقييم المشرف' }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ number_format($evaluation->total_score ?? 0, 2) }} / 100</span>
                                    </div>
                                    <div class="grid grid-cols-4 gap-4 text-sm mb-2 text-[#706f6c] dark:text-[#A1A09A]">
                                        <div>الاقتراح: {{ $evaluation->proposal_score ?? '-' }}</div>
                                        <div>تحقيق الأهداف: {{ $evaluation->objectives_achievement ?? '-' }}</div>
                                        <div>النهائي: {{ $evaluation->final_score ?? '-' }}</div>
                                        <div>التقييم العام: {{ $evaluation->general_score ?? '-' }}</div>
                                    </div>
                                    @if($evaluation->comments)
                                        <div class="mt-2 p-2 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm">
                                            <strong class="text-[#1b1b18] dark:text-[#EDEDEC]">تعليقات:</strong> <span class="text-[#706f6c] dark:text-[#A1A09A]">{{ $evaluation->comments }}</span>
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- تقييمات المشرفين -->
                        @if($project->supervisorEvaluations->count() > 0)
                        <div>
                            <h4 class="text-md font-medium mb-2 text-[#706f6c] dark:text-[#A1A09A]">تقييمات المشرفين</h4>
                            <div class="space-y-4">
                                @foreach($project->supervisorEvaluations as $supervisorEvaluation)
                                    <div class="border border-green-200 dark:border-green-800 rounded-sm p-4 bg-green-50 dark:bg-green-900/20 shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)]">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <span class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $supervisorEvaluation->supervisor->name }}</span>
                                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">- {{ $supervisorEvaluation->evaluated_at ? $supervisorEvaluation->evaluated_at->format('Y-m-d') : 'N/A' }}</span>
                                                <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 mr-2">
                                                    تقييم المشرف
                                                </span>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ number_format($supervisorEvaluation->total_score ?? 0, 2) }} / 100</span>
                                                <div class="text-xs text-green-600 dark:text-green-400 mt-1">{{ $supervisorEvaluation->rating_text }}</div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-3 gap-4 text-sm mb-2 text-[#706f6c] dark:text-[#A1A09A]">
                                            <div>جودة العمل: {{ $supervisorEvaluation->work_quality ?? '-' }}</div>
                                            <div>الالتزام: {{ $supervisorEvaluation->punctuality ?? '-' }}</div>
                                            <div>التعاون: {{ $supervisorEvaluation->teamwork ?? '-' }}</div>
                                            <div>الإبداع: {{ $supervisorEvaluation->innovation ?? '-' }}</div>
                                            <div>المهارات التقنية: {{ $supervisorEvaluation->technical_skills ?? '-' }}</div>
                                            <div>التواصل: {{ $supervisorEvaluation->communication ?? '-' }}</div>
                                        </div>
                                        <div class="mt-2 text-sm text-green-700 dark:text-green-400 font-medium">
                                            التقييم الشامل: {{ $supervisorEvaluation->overall_assessment ?? '-' }} / 10
                                        </div>
                                        @if($supervisorEvaluation->strengths || $supervisorEvaluation->weaknesses || $supervisorEvaluation->recommendations || $supervisorEvaluation->general_comments)
                                            <div class="mt-3 pt-3 border-t border-green-200 dark:border-green-800">
                                                @if($supervisorEvaluation->strengths)
                                                    <div class="mb-2">
                                                        <strong class="text-green-700 dark:text-green-400">نقاط القوة:</strong>
                                                        <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">{{ Str::limit($supervisorEvaluation->strengths, 150) }}</p>
                                                    </div>
                                                @endif
                                                @if($supervisorEvaluation->weaknesses)
                                                    <div class="mb-2">
                                                        <strong class="text-orange-700 dark:text-orange-400">نقاط الضعف:</strong>
                                                        <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">{{ Str::limit($supervisorEvaluation->weaknesses, 150) }}</p>
                                                    </div>
                                                @endif
                                                @if($supervisorEvaluation->recommendations)
                                                    <div class="mb-2">
                                                        <strong class="text-blue-700 dark:text-blue-400">التوصيات:</strong>
                                                        <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">{{ Str::limit($supervisorEvaluation->recommendations, 150) }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="mt-3">
                                            <a href="{{ route('supervisor-evaluations.show', $supervisorEvaluation) }}" class="text-sm text-green-600 dark:text-green-400 hover:underline">
                                                عرض التفاصيل الكاملة →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if($project->similarityChecks->count() > 0)
<div class="bg-white dark:bg-[#161615] overflow-hidden shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] mb-6">
                <div class="p-6 lg:p-20">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">نتائج فحص التشابه</h2>
                            @if($project->isArchived())
                                <p class="text-sm text-amber-600 dark:text-amber-400 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                    تم أرشفة هذا المشروع في: {{ $project->archived_at->format('Y-m-d H:i') }}
                                </p>
                            @endif
                        </div>
                        
                    @foreach($project->similarityChecks as $check)
                        <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-4 mb-4 bg-white dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)]">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">نسبة التشابه: {{ number_format($check->similarity_percentage, 1) }}%</span>
                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $check->checked_at ? $check->checked_at->format('Y-m-d H:i') : 'N/A' }}</span>
                            </div>
                            @if($check->source_comparison && !Auth::user()->hasRole('student'))
                                <div class="mt-3 p-3 bg-gray-50 dark:bg-[#1f1f1d] rounded-sm">
                                    <h4 class="font-semibold mb-2 text-[#1b1b18] dark:text-[#EDEDEC]">مصدر المقارنة:</h4>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] whitespace-pre-line">{{ $check->source_comparison }}</p>
                                </div>
                            @endif
                            @if($check->similar_projects)
                                <div class="mt-3">
                                    <h4 class="font-semibold mb-2 text-[#1b1b18] dark:text-[#EDEDEC]">مشاريع مشابهة:</h4>
                                    <ul class="list-disc list-inside space-y-1 text-[#706f6c] dark:text-[#A1A09A]">
                                        @foreach($check->similar_projects as $similar)
                                            @if(Auth::user()->hasRole('student'))
                                                <li>مشروع مشابه - {{ number_format($similar['similarity'], 1) }}%</li>
                                            @else
                                                <li>{{ $similar['title'] }} - {{ number_format($similar['similarity'], 1) }}%</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if($check->details)
                                <div class="mt-3">
                                    <h4 class="font-semibold mb-2 text-[#1b1b18] dark:text-[#EDEDEC]">تفاصيل:</h4>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $check->details }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <style>
                @media print {
                    .print-button,
                    a[href*="similarity-pdf"] {
                        display: none !important;
                    }
                    body {
                        background: white;
                    }
                    .bg-white,
                    .bg-gray-50,
                    .dark\:bg-\[#161615\] {
                        background: white !important;
                    }
                    .text-gray-900,
                    .text-\[#1b1b18\] {
                        color: #000 !important;
                    }
                }
            </style>
        @endif

        @if($project->sections->count() > 0)
            <div class="bg-white dark:bg-[#161615] overflow-hidden shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-sm mb-6 border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="p-6 lg:p-20">
                    <h2 class="text-xl font-semibold mb-4 text-[#1b1b18] dark:text-[#EDEDEC]">أقسام المشروع وملفاتها</h2>
                    @foreach($project->sections as $section)
                        <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-4 mb-4 bg-white dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)]">
                            <div class="flex items-center space-x-2 space-x-reverse mb-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-sm bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] font-bold">
                                    {{ $section->order_number }}
                                </span>
                                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $section->name }}</h3>
                            </div>
                            @if($section->description)
                                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm mb-3">{{ $section->description }}</p>
                            @endif
                            
                            @if($section->files->count() > 0)
                                <div class="space-y-2">
                                    @foreach($section->files as $file)
                                        <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-3 bg-white dark:bg-[#161615]">
                                            <div class="flex justify-between items-center mb-2">
                                                <div>
                                                    <span class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $file->original_name }}</span>
                                                    <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">({{ $file->formatted_size }})</span>
                                                </div>
                                                @if($file->similarity_score !== null && $file->similarity_score > 0)
                                                    <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                        نسبة التشابه: <span class="font-semibold {{ $file->similarity_score >= 50 ? 'text-[#f53003] dark:text-[#FF4433]' : 'text-[#1b1b18] dark:text-[#EDEDEC]' }}">{{ number_format($file->similarity_score, 1) }}%</span>
                                                        @if($file->ai_probability !== null && $file->ai_probability > 0)
                                                            | AI: <span class="font-semibold text-[#f53003] dark:text-[#FF4433]">{{ number_format($file->ai_probability, 1) }}%</span>
                                                        @endif
                                                    </span>
                                                @elseif($file->ai_probability !== null && $file->ai_probability > 0)
                                                    <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                                        AI: <span class="font-semibold text-[#f53003] dark:text-[#FF4433]">{{ number_format($file->ai_probability, 1) }}%</span>
                                                    </span>
                                                @endif
                                            </div>
                                            @if($file->status == 'rejected' && $file->rejection_reason)
                                                <div class="mt-2 p-2 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm">
                                                    <p class="text-sm text-[#f53003] dark:text-[#FF4433]">{{ $file->rejection_reason }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">لا توجد ملفات في هذا القسم</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($project->files->whereNull('section_id')->count() > 0)
            <div class="bg-white dark:bg-[#161615] overflow-hidden shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-sm mb-6 border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="p-6 lg:p-20">
                    <h2 class="text-xl font-semibold mb-4 text-[#1b1b18] dark:text-[#EDEDEC]">ملفات المشروع (بدون قسم)</h2>
                    @foreach($project->files->whereNull('section_id') as $file)
                        <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-4 mb-4 bg-white dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)]">
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <span class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $file->original_name }}</span>
                                    <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">({{ $file->formatted_size }})</span>
                                </div>
                                @if($file->similarity_score !== null && $file->similarity_score > 0)
                                    <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                        نسبة التشابه: <span class="font-semibold {{ $file->similarity_score >= 50 ? 'text-[#f53003] dark:text-[#FF4433]' : 'text-[#1b1b18] dark:text-[#EDEDEC]' }}">{{ number_format($file->similarity_score, 1) }}%</span>
                                        @if($file->ai_probability !== null && $file->ai_probability > 0)
                                            | AI: <span class="font-semibold text-[#f53003] dark:text-[#FF4433]">{{ number_format($file->ai_probability, 1) }}%</span>
                                        @endif
                                    </span>
                                @elseif($file->ai_probability !== null && $file->ai_probability > 0)
                                    <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                        AI: <span class="font-semibold text-[#f53003] dark:text-[#FF4433]">{{ number_format($file->ai_probability, 1) }}%</span>
                                    </span>
                                @endif
                            </div>
                            @if($file->status == 'rejected' && $file->rejection_reason)
                                <div class="mt-2 p-2 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm">
                                    <p class="text-sm text-[#f53003] dark:text-[#FF4433]">{{ $file->rejection_reason }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
@can('approve projects')
@if($project->status == 'pending' || ($project->status == 'in_progress' && $project->isReadyForFinalApproval()))
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4" onclick="if(event.target === this) closeRejectModal()">
    <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-2xl max-w-md w-full p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] relative z-[10000]" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">رفض المشروع</h3>
            <button type="button" onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('projects.reject', $project) }}" method="POST" id="rejectForm">
            @csrf
            <div class="mb-4">
                <label for="rejection_reason" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">سبب الرفض <span class="text-red-500">*</span></label>
                <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                    class="block w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-[#2a2a28] text-[#1b1b18] dark:text-[#EDEDEC] placeholder:text-gray-500 dark:placeholder:text-gray-400"
                    placeholder="أدخل سبب الرفض..."></textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeRejectModal()" 
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

<script>
function showRejectModal() {
    const modal = document.getElementById('rejectModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
        // Focus on textarea
        setTimeout(() => {
            document.getElementById('rejection_reason')?.focus();
        }, 100);
    }
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
        // Clear form
        const form = document.getElementById('rejectForm');
        if (form) {
            form.reset();
        }
    }
}

function handleCheckAllSimilarity(event) {
    const form = event.target.closest('form');
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

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeRejectModal();
    }
});

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
