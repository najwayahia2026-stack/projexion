@extends('layouts.app')

@section('title', 'المشاريع')

@section('content')

<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-10 rounded-full -ml-32 -mb-32"></div>
                <div class="relative z-10">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">المشاريع</h1>
                            <p class="text-purple-100 text-lg">إدارة ومتابعة جميع مشاريع التخرج</p>
                        </div>
                        @if(Auth::user()->hasRole('student') && $filter !== 'archived')
                        <a href="{{ route('projects.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 hover:bg-white/30 text-white font-semibold rounded-full transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            مشروع جديد
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('department_admin'))
    <div class="mb-6 flex flex-wrap items-center gap-3 bg-gray-900/50 p-3 rounded-xl border border-gray-700">
        <form action="{{ route('projects.index') }}" method="GET" id="projectFilterForm" class="flex flex-1 items-center gap-3">
            <input type="hidden" name="filter" value="{{ request('filter', 'all') }}">
            
            <div class="flex-1">
                <select name="specialty_id" onchange="this.form.submit()" 
                        class="w-full bg-gray-800 text-white border-gray-700 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500 py-2">
                    <option value="">-- جميع التخصصات --</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}" {{ request('specialty_id') == $specialty->id ? 'selected' : '' }}>
                            {{ $specialty->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if(request('specialty_id'))
                <a href="{{ route('projects.index', ['filter' => request('filter', 'all')]) }}" 
                   class="px-4 py-2 bg-red-600/20 text-red-400 text-sm rounded-lg hover:bg-red-600/40 transition">
                    إلغاء
                </a>
            @endif
        </form>
    </div>
@endif

        <!-- Filter Tabs (الكل والنشطة لا تظهر للجنة التقييم) -->
        <div class="mb-6 bg-white dark:bg-[#161615] rounded-xl shadow-lg p-4 border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="flex flex-wrap gap-3">
                @if(!Auth::user()->hasRole('committee'))
                <a href="{{ route('projects.index', ['filter' => 'all']) }}" 
                   class="px-5 py-2.5 rounded-lg font-semibold text-sm transition-all duration-200 {{ $filter === 'all' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-[#3E3E3A] text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-[#4E4E4A]' }}">
                    📋 الكل
                </a>
                <a href="{{ route('projects.index', ['filter' => 'active']) }}" 
                   class="px-5 py-2.5 rounded-lg font-semibold text-sm transition-all duration-200 {{ $filter === 'active' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-[#3E3E3A] text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-[#4E4E4A]' }}">
                    ✅ النشطة
                </a>
                @endif
                <a href="{{ route('projects.index', ['filter' => 'archived']) }}" 
                   class="px-5 py-2.5 rounded-lg font-semibold text-sm transition-all duration-200 {{ $filter === 'archived' ? 'bg-gradient-to-r from-amber-600 to-orange-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-[#3E3E3A] text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-[#4E4E4A]' }}">
                    📦 المأرشفة
                </a>
            </div>
        </div>

        @if($projects->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 animate-fade-in">
                        <!-- Project Header -->
                        <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-6 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                            <div class="relative z-10">
                                <h3 class="text-xl font-bold mb-3 line-clamp-2">{{ $project->title }}</h3>
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-white/20 backdrop-blur-sm border border-white/30
                                        @if($project->status == 'approved') 
                                            bg-green-500/30 border-green-300
                                        @elseif($project->status == 'rejected') 
                                            bg-red-500/30 border-red-300
                                        @elseif($project->status == 'pending') 
                                            bg-yellow-500/30 border-yellow-300
                                        @else 
                                            bg-blue-500/30 border-blue-300
                                        @endif">
                                        @if($project->status == 'approved')
                                            ✓ معتمد
                                        @elseif($project->status == 'rejected')
                                            ✗ مرفوض
                                        @elseif($project->status == 'pending')
                                            ⏳ قيد المراجعة
                                        @else
                                            {{ $project->status }}
                                        @endif
                                    </span>
                                    @if($project->similarity_score)
                                        <span class="text-xs font-bold px-2 py-1 rounded-full {{ $project->similarity_score >= 70 ? 'bg-red-500/30 text-red-100 border border-red-300' : 'bg-blue-500/30 text-blue-100 border border-blue-300' }}">
                                            تشابه: {{ number_format($project->similarity_score, 1) }}%
                                        </span>
                                    @endif
                                    @if($project->isArchived())
                                        <span class="text-xs font-bold px-2 py-1 rounded-full bg-amber-500/30 text-amber-100 border border-amber-300" title="تم أرشفة المشروع في: {{ $project->archived_at->format('Y-m-d H:i') }}">
                                            📦 مؤرشف
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Project Body -->
                        <div class="p-6">
                            <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3 text-sm leading-relaxed">
                                {{ Str::limit($project->description, 120) }}
                            </p>

                            @php
                                $totalPhasesPercentage = $project->total_phases_percentage;
                            @endphp
                            @if($totalPhasesPercentage > 0 || $project->progress_percentage > 0)
                                <div class="mb-4">
                                    @if($totalPhasesPercentage > 0)
                                        <div class="mb-3">
                                            <div class="flex justify-between items-center text-xs mb-2">
                                                <span class="font-semibold text-gray-700 dark:text-gray-300">نسبة الإنجاز من الأجزاء</span>
                                                <span class="font-bold text-gray-900 dark:text-white">{{ number_format($totalPhasesPercentage, 1) }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden shadow-inner">
                                                <div class="h-full rounded-full transition-all duration-700 ease-out shadow-lg
                                                    @if($totalPhasesPercentage >= 75) 
                                                        bg-gradient-to-r from-green-400 via-green-500 to-green-600
                                                    @elseif($totalPhasesPercentage >= 50) 
                                                        bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600
                                                    @else 
                                                        bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600
                                                    @endif" 
                                                    style="width: {{ $totalPhasesPercentage }}%">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($project->progress_percentage > 0)
                                        <div>
                                            <div class="flex justify-between items-center text-xs mb-2">
                                                <span class="font-semibold text-gray-700 dark:text-gray-300">نسبة الإنجاز من الأقسام</span>
                                                <span class="font-bold text-gray-900 dark:text-white">{{ $project->progress_percentage }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden shadow-inner">
                                                <div class="h-full rounded-full transition-all duration-700 ease-out shadow-lg
                                                    @if($project->progress_percentage >= 75) 
                                                        bg-gradient-to-r from-green-400 via-green-500 to-green-600
                                                    @elseif($project->progress_percentage >= 50) 
                                                        bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600
                                                    @else 
                                                        bg-gradient-to-r from-red-400 via-red-500 to-red-600
                                                    @endif" 
                                                    style="width: {{ $project->progress_percentage }}%">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($project->group)
                                <div class="flex items-center text-sm text-gray-900 dark:text-gray-200 mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <svg class="w-5 h-5 ml-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="font-medium">{{ $project->group->name }}</span>
                                </div>
                            @endif

                            @if($project->technologies)
                                <div class="mb-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(explode(',', $project->technologies) as $tech)
                                            <span class="px-2 py-1 text-xs bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-full">
                                                {{ trim($tech) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <a href="{{ route('projects.show', $project) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        عرض
                                    </a>
                                    @if(!$project->isArchived())
                                    @can('edit projects')
                                    <a href="{{ route('projects.edit', $project) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-purple-600 dark:bg-purple-700 text-white border border-purple-700 dark:border-purple-600 hover:bg-purple-700 dark:hover:bg-purple-600 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105 shadow-md">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        تعديل
                                    </a>
                                    @endcan
                                    @php
                                        $canDelete = false;
                                        if (auth()->user()->can('delete projects')) {
                                            $canDelete = true;
                                        } elseif (auth()->user()->hasRole('student') && $project->group && $project->group->students->contains(auth()->id())) {
                                            $canDelete = true;
                                        }
                                    @endphp
                                    @if($canDelete)
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟ سيتم حذف جميع البيانات المرتبطة به بشكل نهائي.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105">
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            حذف
                                        </button>
                                    </form>
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination (if needed) -->
            @if(method_exists($projects, 'links'))
                <div class="mt-8">
                    {{ $projects->links() }}
                </div>
            @endif
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center border border-gray-200 dark:border-gray-700 animate-fade-in">
                <div class="max-w-md mx-auto">
                    <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">لا توجد مشاريع بعد</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">ابدأ بإنشاء مشروع جديد لإدارة مشروع التخرج الخاص بك</p>
                    @if(Auth::user()->hasRole('student') && $filter !== 'archived')
                    <a href="{{ route('projects.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-full hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        إنشاء مشروع جديد
                    </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }
</style>
@endpush
@endsection
