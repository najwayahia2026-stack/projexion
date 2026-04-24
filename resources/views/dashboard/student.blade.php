<div>
    <!-- إحصائيات - صف واحد -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_6px_0px_rgba(0,0,0,0.06)] p-5 flex items-center justify-between gap-3 hover:border-blue-500/50 dark:hover:border-blue-400/50 transition-all duration-300 card-hover">
            <div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm font-medium">إجمالي المشاريع</p>
                <p class="text-2xl sm:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $stats['total_projects'] }}</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
        <div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_6px_0px_rgba(0,0,0,0.06)] p-5 flex items-center justify-between gap-3 hover:border-green-500/50 dark:hover:border-green-400/50 transition-all duration-300 card-hover">
            <div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm font-medium">المشاريع النشطة</p>
                <p class="text-2xl sm:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $stats['active_projects'] }}</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_6px_0px_rgba(0,0,0,0.06)] p-5 flex items-center justify-between gap-3 hover:border-purple-500/50 dark:hover:border-purple-400/50 transition-all duration-300 card-hover col-span-2 lg:col-span-1">
            <div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm font-medium">المشاريع المكتملة</p>
                <p class="text-2xl sm:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $stats['completed_projects'] }}</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>
    </div>

    <!-- Groups Section -->
    @if(isset($groups) && $groups->count() > 0)
        <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 lg:p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl lg:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center ml-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    مجموعاتي
                </h2>
                <a href="{{ route('groups.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 font-medium">
                    عرض الكل
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($groups->take(3) as $group)
                    <a href="{{ route('groups.show', $group) }}" class="group relative bg-gradient-to-br from-white to-gray-50 dark:from-[#1a1a18] dark:to-[#161615] rounded-xl shadow-lg p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] hover:shadow-2xl hover:border-blue-300 dark:hover:border-blue-700 transition-all duration-300 transform hover:-translate-y-2">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-bl-full"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $group->name }}</h3>
                                <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-md">
                                    {{ $group->code }}
                                </span>
                            </div>
                            <div class="flex items-center text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center ml-2">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">{{ $group->supervisor->name ?? 'بدون مشرف' }}</span>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A] font-medium">{{ $group->projects->count() }} مشروع</span>
                                <span class="text-blue-600 dark:text-blue-400 font-bold group-hover:translate-x-1 transition-transform inline-flex items-center">
                                    عرض
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            @if($groups->count() > 3)
                <div class="mt-6 text-center">
                    <a href="{{ route('groups.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold">
                        عرض جميع المجموعات ({{ $groups->count() }})
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    @endif

    <!-- Projects Section -->
    <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 lg:p-8 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl lg:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center ml-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                مشاريعي
            </h2>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                مشروع جديد
            </a>
        </div>

        @if($projects->count() > 0)
            <div class="space-y-4">
                @foreach($projects as $project)
                    <div class="group relative bg-gradient-to-br from-white to-gray-50 dark:from-[#1a1a18] dark:to-[#161615] rounded-xl shadow-lg p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] hover:shadow-2xl hover:border-green-300 dark:hover:border-green-700 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-green-500/5 to-emerald-500/5 rounded-bl-full"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 space-x-reverse mb-3">
                                        <h3 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">{{ $project->title }}</h3>
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg 
                                            @if($project->status == 'approved') bg-gradient-to-r from-green-500 to-emerald-600 text-white
                                            @elseif($project->status == 'rejected') bg-gradient-to-r from-red-500 to-rose-600 text-white
                                            @elseif($project->status == 'pending') bg-gradient-to-r from-yellow-500 to-orange-600 text-white
                                            @elseif($project->status == 'in_progress') bg-gradient-to-r from-blue-500 to-indigo-600 text-white
                                            @else bg-gradient-to-r from-gray-500 to-gray-600 text-white
                                            @endif shadow-md">
                                            {{ $project->status }}
                                        </span>
                                    </div>
                                    <p class="text-[#706f6c] dark:text-[#A1A09A] mb-4 line-clamp-2 leading-relaxed">{{ Str::limit($project->description, 120) }}</p>
                                    
                                    @if($project->progress_percentage > 0)
                                        <div class="mb-4">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">نسبة الإنجاز</span>
                                                <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $project->progress_percentage }}%</span>
                                            </div>
                                            <div class="w-full bg-[#e3e3e0] dark:bg-[#3E3E3A] rounded-full h-3 overflow-hidden shadow-inner">
                                                <div class="h-full rounded-full bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 transition-all duration-500 shadow-lg" 
                                                    style="width: {{ $project->progress_percentage }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <a href="{{ route('projects.show', $project) }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 text-sm font-semibold">
                                        عرض
                                    </a>
                                    @if(!$project->isArchived())
                                    @can('edit projects')
                                    <a href="{{ route('projects.edit', $project) }}" class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 text-sm font-semibold">
                                        تعديل
                                    </a>
                                    @endcan
                                    @can('delete projects')
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟ سيتم حذف جميع البيانات المرتبطة به.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-lg hover:from-red-600 hover:to-rose-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 text-sm font-semibold">
                                            حذف
                                        </button>
                                    </form>
                                    @endcan
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/20 dark:to-emerald-900/20 rounded-full mb-6">
                    <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-xl font-semibold mb-2">لا توجد مشاريع بعد</p>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm mb-6">ابدأ بإنشاء مشروعك الأول الآن</p>
                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    إنشاء مشروع جديد
                </a>
            </div>
        @endif
    </div>

    <!-- Upcoming Deadlines -->
    @if(isset($upcomingDeadlines) && $upcomingDeadlines->count() > 0)
        <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 lg:p-8">
            <h2 class="text-2xl lg:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6 flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center ml-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                المواعيد القادمة
            </h2>
            <div class="space-y-4">
                @foreach($upcomingDeadlines as $deadline)
                    <div class="group relative bg-gradient-to-br from-white to-orange-50 dark:from-[#1a1a18] dark:to-orange-900/10 rounded-xl shadow-lg p-5 border border-orange-200 dark:border-orange-800/30 hover:shadow-xl hover:border-orange-300 dark:hover:border-orange-700 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-orange-500/10 to-amber-500/10 rounded-bl-full"></div>
                        <div class="relative z-10">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">{{ $deadline->title }}</h4>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3 leading-relaxed">{{ $deadline->description }}</p>
                                    <div class="flex items-center text-sm font-semibold text-orange-600 dark:text-orange-400">
                                        <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center ml-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        {{ $deadline->deadline_date->format('Y-m-d H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
