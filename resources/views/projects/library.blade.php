@extends('layouts.app')

@section('title', 'مكتبة المشاريع')

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
                            <h1 class="text-4xl font-bold mb-2">📚 مكتبة المشاريع</h1>
                            <p class="text-purple-100 text-lg">استعرض جميع المشاريع المؤرشفة (المعتمدة والمرفوضة) للمراجعة والاستفادة</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="mb-6 bg-white dark:bg-[#161615] rounded-xl shadow-lg p-6 border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <form method="GET" action="{{ route('projects.library') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Input -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            البحث في المشاريع
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   value="{{ request('search') }}"
                                   placeholder="ابحث بالعنوان، الوصف، التقنيات، أو أسماء الطلاب..."
                                   class="w-full px-4 py-3 pl-12 pr-4 border border-gray-300 dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#2a2a28] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                            <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Department Filter -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            القسم
                        </label>
                        <select name="department" 
                                id="department"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#2a2a28] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                            <option value="">جميع الأقسام</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        بحث
                    </button>
                    @if(request('search') || request('department'))
                        <a href="{{ route('projects.library') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-[#3E3E3A] text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-[#4E4E4A] transition-all duration-200">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            مسح
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if($projects->count() > 0)
            <!-- Projects Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    <div class="bg-white dark:bg-[#161615] rounded-xl shadow-lg overflow-hidden border border-[#e3e3e0] dark:border-[#3E3E3A] hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 animate-fade-in">
                        <!-- Project Header -->
                        <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-6 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                            <div class="relative z-10">
                                <h3 class="text-xl font-bold mb-3 line-clamp-2">{{ $project->title }}</h3>
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-white/20 backdrop-blur-sm border border-white/30
                                        @if($project->status == 'rejected') bg-red-500/30 border-red-300
                                        @else bg-green-500/30 border-green-300
                                        @endif">
                                        @if($project->status == 'rejected')
                                            ✗ مرفوض
                                        @else
                                            ✓ معتمد
                                        @endif
                                    </span>
                                    @if($project->similarity_score)
                                        <span class="text-xs font-bold px-2 py-1 rounded-full {{ $project->similarity_score >= 70 ? 'bg-red-500/30 text-red-100 border border-red-300' : 'bg-blue-500/30 text-blue-100 border border-blue-300' }}">
                                            تشابه: {{ number_format($project->similarity_score, 1) }}%
                                        </span>
                                    @endif
                                    <span class="text-xs font-bold px-2 py-1 rounded-full bg-white/30 text-white border border-white/40" title="تم أرشفة المشروع في: {{ $project->archived_at->format('Y-m-d H:i') }}">
                                        📦 مؤرشف
                                    </span>
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
                                                <div class="h-full rounded-full transition-all duration-700 ease-out shadow-lg bg-gradient-to-r from-green-400 via-green-500 to-green-600" 
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
                                                <div class="h-full rounded-full transition-all duration-700 ease-out shadow-lg bg-gradient-to-r from-green-400 via-green-500 to-green-600" 
                                                     style="width: {{ $project->progress_percentage }}%">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($project->group)
                                <div class="mb-4 space-y-2">
                                    <div class="flex items-center text-sm text-gray-900 dark:text-gray-200 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <svg class="w-5 h-5 ml-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span class="font-medium">{{ $project->group->name }}</span>
                                    </div>
                                    
                                    @if($project->group->students->count() > 0)
                                        <div class="text-xs text-gray-600 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                            <span class="font-semibold">الطلاب: </span>
                                            {{ $project->group->students->pluck('name')->join('، ') }}
                                        </div>
                                        @if($project->group->students->first()->department)
                                            <div class="text-xs text-gray-600 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                                <span class="font-semibold">القسم: </span>
                                                {{ $project->group->students->first()->department }}
                                            </div>
                                        @endif
                                    @endif
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

                            @if($project->status == 'rejected' && $project->rejection_reason)
                                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                    <p class="text-xs font-semibold text-red-800 dark:text-red-200 mb-1">سبب الرفض:</p>
                                    <p class="text-xs text-red-700 dark:text-red-300">{{ Str::limit($project->rejection_reason, 80) }}</p>
                                </div>
                            @endif
                            @if($project->archived_at)
                                <div class="mb-4 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $project->status == 'rejected' ? 'تم الرفض' : 'تم الأرشفة' }}: {{ $project->archived_at->format('Y-m-d') }}
                                </div>
                            @endif

                            <!-- View Button Only -->
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('projects.show', $project) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white border border-indigo-700 dark:border-indigo-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105 shadow-md">
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($projects->hasPages())
                <div class="mt-8">
                    {{ $projects->links() }}
                </div>
            @endif
        @else
            <div class="bg-white dark:bg-[#161615] rounded-xl shadow-lg p-12 text-center border border-[#e3e3e0] dark:border-[#3E3E3A] animate-fade-in">
                <div class="max-w-md mx-auto">
                    <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">لا توجد مشاريع مؤرشفة</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        @if(request('search') || request('department'))
                            لم يتم العثور على مشاريع تطابق البحث
                        @else
                            لا توجد مشاريع مؤرشفة متاحة للعرض حالياً
                        @endif
                    </p>
                    @if(request('search') || request('department'))
                        <a href="{{ route('projects.library') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                            عرض جميع المشاريع
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
