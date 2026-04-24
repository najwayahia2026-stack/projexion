@extends('layouts.app')

@section('title', 'المجموعات')

@section('content')
<style>
    /* تنسيق مخصص لشريط التمرير الجانبي */
    .custom-scrollbar {
        overflow-y: auto !important;
        scrollbar-width: thin !important;
        scrollbar-color: #cbd5e1 #f8fafc !important;
        max-height: 100% !important;
    }

    /* تنسيق شريط التمرير في Chrome/Safari/Edge */
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px !important;
        display: block !important;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1 !important;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1 !important;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8 !important;
    }

    /* تقليل ارتفاع الصناديق وضمان ظهور شريط التمرير */
    .compact-card {
        height: 320px !important;
        max-height: 320px !important;
        min-height: 320px !important;
        display: flex !important;
        flex-direction: column !important;
    }

    /* التأكد من أن المحتوى القابل للتمرير يأخذ المساحة المتبقية */
    .compact-card .overflow-y-auto {
        flex: 1 1 auto !important;
        min-height: 0 !important;
        overflow-y: auto !important;
    }

    /* تقليل حجم الخط والمسافات الداخلية */
    .compact-table th,
    .compact-table td {
        padding: 0.5rem !important;
    }

    .compact-table thead th {
        font-size: 0.7rem !important;
        padding: 0.5rem !important;
        position: sticky !important;
        top: 0 !important;
        background: #f8fafc !important;
        z-index: 10 !important;
    }

    .compact-table tbody td {
        font-size: 0.75rem !important;
    }

    /* تقليل حجم العناصر في قائمة المحجوزين */
    .compact-list li {
        padding: 0.5rem 0 !important;
    }

    .compact-list .avatar {
        width: 2rem !important;
        height: 2rem !important;
        font-size: 0.75rem !important;
        margin-left: 0.5rem !important;
    }

    .compact-list .student-name {
        font-size: 0.75rem !important;
    }

    .compact-list .student-email {
        font-size: 0.6rem !important;
    }

    .compact-list button svg {
        width: 1rem !important;
        height: 1rem !important;
    }
</style>

<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- الهيدر العلوي --}}
<div class="mb-8 animate-fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    إدارة المجموعات
                </span>
            </h1>
            <p class="text-gray-600">إدارة الطلاب المتاحين ومتابعة المجموعات والمشاريع</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
    {{-- زر التقارير باللون الأزرق الجديد --}}
    @if(Auth::user()->hasRole('supervisor'))
    <a href="{{ route('supervisor.report', Auth::id()) }}" 
       target="_blank"
       class="inline-flex items-center px-6 py-3 bg-white border-2 border-blue-600 text-blue-600 rounded-xl hover:bg-blue-50 transition-all duration-300 shadow-sm font-bold text-sm">
        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        تقارير المجموعات
    </a>
    @endif


            {{-- زر مجموعة جديدة --}}
            @can('create groups')
            <a href="{{ route('groups.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 font-bold text-sm">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                مجموعة جديدة
            </a>
            @endcan
        </div>
    </div>
</div>

       @if(Auth::user()->hasRole('supervisor'))
         <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 animate-fade-in">
    
    {{-- 1. صندوق الطلاب المتاحين (مصغر مع شريط تمرير) --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 compact-card overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="p-3 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-100 flex justify-between items-center shrink-0">
            <h2 class="font-bold text-gray-800 text-sm flex items-center">
                <svg class="w-4 h-4 ml-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                الطلاب المتاحون
            </h2>
            <span class="text-[8px] bg-blue-600 text-white px-2 py-0.5 rounded-full font-bold">متاح للاختيار</span>
        </div>
        
        <div class="overflow-y-auto custom-scrollbar flex-grow" style="flex: 1; min-height: 0;">
            <table class="w-full text-right compact-table">
                <thead class="bg-gray-50 text-gray-600 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="text-right font-bold">اسم الطالب</th>
                        <th class="text-center font-bold">الإجراء</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($availableStudents ?? [] as $student)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="font-medium text-gray-700">
                            {{ $student->name }}
                            <span class="block text-xs text-gray-400 font-normal">{{ $student->email }}</span>
                        </td>
                        <td class="text-center">
                          <form action="{{ route('students.addToPool', $student->id) }}" method="POST">
    @csrf
    <button type="submit" 
            class="inline-flex items-center justify-center bg-blue-600 text-white hover:bg-blue-700 font-bold text-[12px] px-3 py-1.5 rounded-lg border border-blue-600 transition-all shadow-sm active:scale-95">
        <span class="ml-1 text-white">+</span>
        <span class="text-white">اضافة طالب</span>
    </button>
</form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center py-6 text-gray-400 text-sm">
                            لا يوجد طلاب متاحون
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 2. صندوق الطلاب المحجوزين (مصغر مع شريط تمرير) --}}
    <div class="bg-white rounded-2xl shadow-lg border-2 border-purple-100 compact-card overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="p-3 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-purple-100 flex justify-between items-center shrink-0">
            <h2 class="font-bold text-purple-800 text-sm">
    قائمتي الخاصة (المجموع: {{ $totalOccupied }}/15)
</h2>
            <span class="text-[10px] bg-purple-600 text-white px-2 py-0.5 rounded-full font-bold">
                {{ ($myPoolStudents ?? collect())->count() }} طلاب
            </span>
        </div>

        <div class="overflow-y-auto custom-scrollbar flex-grow" style="flex: 1; min-height: 0;">
            <ul class="divide-y divide-gray-100 px-3 compact-list">
                @forelse($myPoolStudents ?? [] as $reserved)
                <li class="py-2 flex justify-between items-center group hover:bg-purple-50/30 transition-all rounded-lg px-1">
                    <div class="flex items-center">
                        <div class="avatar w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold ml-2 shadow-inner border border-purple-200 text-xs">
                            {{ mb_substr($reserved->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="student-name text-xs font-bold text-gray-800">{{ $reserved->name }}</p>
                            <p class="student-email text-[10px] text-gray-500">{{ $reserved->email }}</p>
                        </div>
                    </div>
                    <form action="{{ route('students.removeFromPool', $reserved->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="p-1 text-red-400 hover:text-red-600 hover:bg-red-100 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </li>
                @empty
                <li class="py-6 text-center text-gray-400 text-sm">
                    لا يوجد طلاب محجوزون
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endif

        <div class="relative flex py-3 items-center">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="flex-shrink mx-4 text-gray-400 font-bold text-xs uppercase tracking-widest">مجموعات العمل الحالية</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        {{-- فلتر التخصصات --}}
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('department_admin'))
            <div class="mb-6 flex flex-wrap items-center gap-3 bg-gray-900/50 p-3 rounded-xl border border-gray-700">
                <form action="{{ route('groups.index') }}" method="GET" id="groupFilterForm" class="flex flex-1 items-center gap-3">
                    <div class="flex-1">
                        <select name="specialty_id" onchange="this.form.submit()" 
                                class="w-full bg-gray-800 text-white border-gray-700 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500 py-2 px-3">
                            <option value="">-- جميع التخصصات --</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}" {{ request('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(request('specialty_id'))
                        <a href="{{ route('groups.index') }}" 
                           class="px-4 py-2 bg-red-600/20 text-red-400 text-sm rounded-lg hover:bg-red-600/40 transition">
                            إلغاء
                        </a>
                    @endif
                </form>
            </div>
        @endif

        {{-- عرض المجموعات --}}
        @if($groups->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                @foreach($groups as $group)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-6 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-xl font-bold line-clamp-1">{{ $group->name }}</h3>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-white/20 backdrop-blur-sm border border-white/30">
                                        {{ $group->code }}
                                    </span>
                                </div>
                                <p class="text-sm text-purple-100">السنة الأكاديمية: {{ $group->academic_year }}</p>
                            </div>
                        </div>

                        <div class="p-6">
                            @if($group->supervisor)
                                <div class="flex items-center mb-4 pb-4 border-b border-blue-100">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg ml-3 shadow-inner">
                                        {{ mb_substr($group->supervisor->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">{{ $group->supervisor->name }}</p>
                                        <p class="text-xs text-blue-600 font-medium">مشرف المجموعة</p>
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg p-3 text-center border border-blue-100">
                                    <div class="text-2xl font-bold text-blue-600">{{ $group->students->count() }}</div>
                                    <div class="text-xs text-blue-700 mt-1 font-medium">طلاب</div>
                                </div>
                                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-3 text-center border border-purple-100">
                                    <div class="text-2xl font-bold text-purple-600">{{ $group->projects->count() }}</div>
                                    <div class="text-xs text-purple-700 mt-1 font-medium">مشاريع</div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center gap-2 pt-4 border-t border-indigo-100">
                                <a href="{{ route('groups.show', $group) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md flex-1 justify-center">
                                    <span>التفاصيل</span>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                @if((Auth::user()->hasRole('supervisor') && $group->supervisor_id == Auth::id()) || Auth::user()->hasRole('admin'))
                                    <a href="{{ route('groups.edit', $group) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-all shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center mt-8 border border-gray-100">
                <div class="max-w-md mx-auto text-gray-400">
                    <svg class="mx-auto h-24 w-24 mb-6 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد مجموعات حالية</h3>
                    <p class="mb-6">لم يتم إنشاء أي مجموعات عمل بعد في النظام.</p>
                </div>
            </div>
        @endif

        {{-- قسم انضمام الطلاب --}}
        @if(Auth::user()->hasRole('student'))
            <div class="mt-12 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl shadow-xl p-8 border border-blue-100">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center ml-4 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">الانضمام لمجموعة</h2>
                        <p class="text-gray-600">أدخل كود المجموعة المكون من 6 أرقام</p>
                    </div>
                </div>
                <form action="{{ route('groups.join') }}" method="POST" class="flex flex-col sm:flex-row gap-4">
                    @csrf
                    <div class="flex-1">
                        <input type="text" name="code" required placeholder="مثال: GR-123"
                               class="w-full px-5 py-4 border-2 border-white bg-white rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm uppercase">
                    </div>
                    <button type="submit"
                            class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg font-bold">
                        انضم للمجموعة
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection