<div>
    <!-- إحصائيات - صف واحد -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
        <div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_6px_0px_rgba(0,0,0,0.06)] p-5 flex items-center justify-between gap-3 hover:border-blue-500/50 dark:hover:border-blue-400/50 transition-all duration-300 card-hover">
            <div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm font-medium">إجمالي المجموعات</p>
                <p class="text-2xl sm:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $stats['total_groups'] }}</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_6px_0px_rgba(0,0,0,0.06)] p-5 flex items-center justify-between gap-3 hover:border-green-500/50 dark:hover:border-green-400/50 transition-all duration-300 card-hover">
            <div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm font-medium">إجمالي المشاريع</p>
                <p class="text-2xl sm:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $stats['total_projects'] }}</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
        <div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_6px_0px_rgba(0,0,0,0.06)] p-5 flex items-center justify-between gap-3 hover:border-amber-500/50 dark:hover:border-amber-400/50 transition-all duration-300 card-hover">
            <div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm font-medium">قيد المراجعة</p>
                <p class="text-2xl sm:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $stats['pending_reviews'] }}</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_6px_0px_rgba(0,0,0,0.06)] p-5 flex items-center justify-between gap-3 hover:border-purple-500/50 dark:hover:border-purple-400/50 transition-all duration-300 card-hover">
            <div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm font-medium">المشاريع النشطة</p>
                <p class="text-2xl sm:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $stats['active_projects'] }}</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Pending Projects -->
    @if($pendingProjects->count() > 0)
        <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 lg:p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl lg:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center ml-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    مشاريع قيد المراجعة
                </h2>
            </div>
            <div class="space-y-4">
                @foreach($pendingProjects as $project)
                    <div class="group relative bg-gradient-to-br from-white to-yellow-50 dark:from-[#1a1a18] dark:to-yellow-900/10 rounded-xl shadow-lg p-6 border border-yellow-200 dark:border-yellow-800/30 hover:shadow-xl hover:border-yellow-300 dark:hover:border-yellow-700 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-yellow-500/10 to-orange-500/10 rounded-bl-full"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2 group-hover:text-yellow-600 dark:group-hover:text-yellow-400 transition-colors">{{ $project->title }}</h3>
                                    <p class="text-[#706f6c] dark:text-[#A1A09A] mb-3 line-clamp-2 leading-relaxed">{{ Str::limit($project->description, 100) }}</p>
                                    <div class="flex items-center text-sm font-semibold text-yellow-600 dark:text-yellow-400">
                                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center ml-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-semibold">المجموعة:</span> {{ $project->group->name }}
                                    </div>
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
        </div>
    @endif

    <!-- Active Projects -->
    @if($inProgressProjects->count() > 0)
        <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 lg:p-8">
            <h2 class="text-2xl lg:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6 flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center ml-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                المشاريع النشطة
            </h2>
            <div class="space-y-4">
                @foreach($inProgressProjects as $project)
                    <div class="group relative bg-gradient-to-br from-white to-purple-50 dark:from-[#1a1a18] dark:to-purple-900/10 rounded-xl shadow-lg p-6 border border-purple-200 dark:border-purple-800/30 hover:shadow-xl hover:border-purple-300 dark:hover:border-purple-700 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-bl-full"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">{{ $project->title }}</h3>
                                    <p class="text-[#706f6c] dark:text-[#A1A09A] mb-4 line-clamp-2 leading-relaxed">{{ Str::limit($project->description, 100) }}</p>
                                    @if($project->progress_percentage > 0)
                                        <div class="mb-4">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">نسبة الإنجاز</span>
                                                <span class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $project->progress_percentage }}%</span>
                                            </div>
                                            <div class="w-full bg-[#e3e3e0] dark:bg-[#3E3E3A] rounded-full h-3 overflow-hidden shadow-inner">
                                                <div class="h-full rounded-full bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500 transition-all duration-500 shadow-lg" 
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
        </div>
    @endif
</div>
