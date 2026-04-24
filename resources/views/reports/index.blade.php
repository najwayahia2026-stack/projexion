@extends('layouts.app')

@section('title', 'التقارير الإحصائية')

@section('content')
<div class="py-8 main-reports-container">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 animate-fade-in no-print">
            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
                <div class="relative z-10">
                    <h1 class="text-4xl font-bold mb-2">التقارير</h1>
                    <p class="text-purple-100 text-lg">إحصاءات فقط - نسبة التشابه والإنجاز دون نصوص المشاريع الأصلية</p>
                </div>
            </div>
        </div>

        <div class="mb-6 bg-white dark:bg-[#161615] rounded-xl shadow-lg p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] no-print">
            <form action="{{ route('reports.index') }}" method="GET" class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-5 h-5 ml-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    البحث والاستعلام
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اسم المشروع</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="ابحث باسم المشروع..."
                               class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#2a2a28] focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label for="similarity_min" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نسبة التشابه (أكبر من)</label>
                        <input type="number" name="similarity_min" id="similarity_min" value="{{ request('similarity_min') }}" placeholder="مثال: 50"
                               class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#2a2a28] outline-none">
                    </div>
                    <div>
                        <label for="progress_min" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نسبة الإنجاز (أكبر من)</label>
                        <input type="number" name="progress_min" id="progress_min" value="{{ request('progress_min') }}" placeholder="مثال: 20"
                               class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#2a2a28] outline-none">
                    </div>
                </div>
                <div class="bg-white dark:bg-[#161615] p-6 rounded-xl shadow-sm mb-6 border border-gray-200 dark:border-[#3E3E3A]">
    <form action="{{ route('reports.index') }}" method="GET" id="searchForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        <div class="bg-white dark:bg-[#161615] p-6 rounded-xl shadow-sm mb-6 border border-gray-200 dark:border-[#3E3E3A]">
    <form action="{{ route('reports.index') }}" method="GET" id="searchForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        {{-- لا يظهر إلا للأدمن --}}
        @if(auth()->user()->hasRole('admin'))
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">تصفية حسب التخصص (للإدارة)</label>
            <select name="specialty_id" onchange="document.getElementById('searchForm').submit()" 
                    class="w-full p-2.5 border rounded-lg bg-gray-50 dark:bg-[#2a2a28] dark:text-white border-gray-300 dark:border-gray-600 focus:ring-indigo-500">
                <option value="">-- عرض كل التخصصات --</option>
                @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}" {{ request('specialty_id') == $specialty->id ? 'selected' : '' }}>
                        {{ $specialty->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-all shadow-md">
                        بحث وتحديث
                    </button>
                    <a href="{{ route('reports.index') }}" class="inline-flex items-center px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg">
                        مسح الفلاتر
                    </a>
                    
                    <a href="{{ request()->fullUrlWithQuery(['print_mode' => 1]) }}" 
                       target="_blank"
                       class="inline-flex items-center px-6 py-2.5 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-all shadow-md">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        طباعة التقرير
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-[#161615] rounded-xl shadow-lg overflow-hidden border border-[#e3e3e0] dark:border-[#3E3E3A]">
            @if($projects->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-indigo-50 dark:bg-indigo-900/20">
                            <tr>
                                <th class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-white border-b">#</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-white border-b">اسم المشروع</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-white border-b">رمز المجموعة</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-white border-b">نسبة التشابه</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-white border-b">نسبة الإنجاز</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-transparent">
                            @foreach($projects as $index => $project)
                                <tr class="hover:bg-gray-50 dark:hover:bg-[#2a2a28] transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $projects->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">
                                        {{ $project->title ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-mono text-gray-700 dark:text-gray-300">
                                        {{ $project->group_id ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php $sim = $project->similarity_score; @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold
                                            @if($sim >= 70) bg-red-100 text-red-700 @elseif($sim >= 50) bg-amber-100 text-amber-700 @else bg-green-100 text-green-700 @endif">
                                            {{ $sim !== null ? number_format($sim, 1).'%' : 'لم يُفحص' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php $progress = (int) ($project->report_progress ?? 0); @endphp
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                                <div class="h-full rounded-full @if($progress >= 75) bg-green-500 @elseif($progress >= 50) bg-yellow-500 @else bg-orange-500 @endif"
                                                     style="width: {{ $progress }}%"></div>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $progress }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 no-print">
                    {{ $projects->links() }}
                </div>
            @else
                <div class="p-16 text-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">لا توجد نتائج تطابق بحثك</h3>
                    <a href="{{ route('reports.index') }}" class="text-indigo-600 mt-2 inline-block hover:underline">عرض جميع المشاريع</a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    /* تحسينات العرض والطباعة */
    @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fade-in 0.5s ease-out; }

    @media print {
        /* إخفاء القوائم العلوية والجانبية من ملف الـ Layout الأساسي */
        nav, .no-print, header, footer, aside, [role="navigation"] {
            display: none !important;
        }
        
        /* تصفير الهوامش وجعل المحتوى يملأ الصفحة */
        .main-reports-container {
            margin: 0 !important;
            padding: 0 !important;
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
        }

        body { background: white !important; }
        
        /* إظهار حدود الجدول بوضوح في الورقة */
        table { border: 1px solid #000 !important; }
        th, td { border-bottom: 1px solid #000 !important; color: #000 !important; }
    }
</style>
@endpush
@endsection