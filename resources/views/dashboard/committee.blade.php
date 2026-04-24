<div>
    <!-- إحصائيات - صف واحد -->
    <div class="grid grid-cols-2 gap-4 sm:gap-6 mb-6">
        <div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_6px_0px_rgba(0,0,0,0.06)] p-5 flex items-center justify-between gap-3 hover:border-blue-500/50 dark:hover:border-blue-400/50 transition-all duration-300 card-hover">
            <div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm font-medium">إجمالي المشاريع</p>
                <p class="text-2xl sm:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $stats['total_projects'] }}</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
        <div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_6px_0px_rgba(0,0,0,0.06)] p-5 flex items-center justify-between gap-3 hover:border-amber-500/50 dark:hover:border-amber-400/50 transition-all duration-300 card-hover">
            <div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm font-medium">التقييمات المعلقة</p>
                <p class="text-2xl sm:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $stats['pending_evaluations'] }}</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
        </div>
    </div>

    @if(isset($pendingEvaluations) && $pendingEvaluations->count() > 0)
        <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 lg:p-8">
            <h2 class="text-2xl lg:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6 flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center ml-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                مشاريع تحتاج تقييم
            </h2>
            <div class="space-y-4">
                @foreach($pendingEvaluations as $project)
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
                                <a href="{{ route('evaluations.create', $project) }}" class="px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-600 text-white rounded-lg hover:from-orange-600 hover:to-amber-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold">
                                    تقييم
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
