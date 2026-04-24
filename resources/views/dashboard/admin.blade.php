<div>
    <!-- إحصائيات - صف واحد -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
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
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm font-medium">إجمالي المجموعات</p>
                <p class="text-2xl sm:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $stats['total_groups'] }}</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_6px_0px_rgba(0,0,0,0.06)] p-5 flex items-center justify-between gap-3 hover:border-purple-500/50 dark:hover:border-purple-400/50 transition-all duration-300 card-hover">
            <div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm font-medium">إجمالي الطلاب</p>
                <p class="text-2xl sm:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $stats['total_students'] }}</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_6px_0px_rgba(0,0,0,0.06)] p-5 flex items-center justify-between gap-3 hover:border-amber-500/50 dark:hover:border-amber-400/50 transition-all duration-300 card-hover">
            <div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm font-medium">إجمالي المشرفين</p>
                <p class="text-2xl sm:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $stats['total_supervisors'] }}</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>

    @if(isset($projectsByStatus) && $projectsByStatus->count() > 0)
        <div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_6px_0px_rgba(0,0,0,0.06)] p-6 mb-6">
            <h2 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 flex items-center gap-2">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </span>
                توزيع المشاريع حسب الحالة
            </h2>
            <div class="flex flex-wrap gap-3">
                @foreach($projectsByStatus as $status => $count)
                    <div class="inline-flex items-center gap-3 rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#f5f5f3] dark:bg-[#1f1f1e] px-5 py-3 hover:border-blue-400 dark:hover:border-blue-600 transition-all">
                        <span class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $count }}</span>
                        <span class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">{{ $status }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- إجراءات سريعة - في صف واحد -->
    <div class="flex flex-wrap gap-4 sm:gap-6">
        <a href="{{ route('users.index') }}" class="flex-1 min-w-[280px] bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_6px_0px_rgba(0,0,0,0.06)] p-6 flex items-center gap-4 hover:border-blue-400 dark:hover:border-blue-600 hover:shadow-lg transition-all duration-300 card-hover">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div class="min-w-0">
                <h3 class="text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC]">إدارة المستخدمين</h3>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-0.5">عرض وتعديل وحذف وحظر المستخدمين</p>
            </div>
        </a>
        <a href="{{ route('groups.index') }}" class="flex-1 min-w-[280px] bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_6px_0px_rgba(0,0,0,0.06)] p-6 flex items-center gap-4 hover:border-green-500 dark:hover:border-green-600 hover:shadow-lg transition-all duration-300 card-hover">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="min-w-0">
                <h3 class="text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC]">إدارة المجموعات</h3>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-0.5">عرض وتعديل وحذف المجموعات</p>
            </div>
        </a>
    </div>
</div>
