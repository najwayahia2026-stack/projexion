@extends('layouts.app')

@section('title', 'تعديل المشروع')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            <h1 class="text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                تعديل المشروع
            </h1>
            <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $project->title }}</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-[#161615] rounded-sm shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] p-8 lg:p-20 border border-[#e3e3e0] dark:border-[#3E3E3A] animate-fade-in" style="animation-delay: 0.1s">

            <form action="{{ route('projects.update', $project) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            عنوان المشروع *
                        </label>
                        <input type="text" name="title" id="title" required
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm focus:outline-none focus:border-[#19140035] dark:focus:border-[#3E3E3A] transition-all duration-200"
                               value="{{ old('title', $project->title) }}">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            وصف المشروع *
                        </label>
                        <textarea name="description" id="description" rows="5" required
                                  class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm focus:outline-none focus:border-[#19140035] dark:focus:border-[#3E3E3A] transition-all duration-200 resize-none">{{ old('description', $project->description) }}</textarea>
                    </div>

                    <div>
                        <label for="objectives" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            أهداف المشروع *
                        </label>
                        <textarea name="objectives" id="objectives" rows="4" required
                                  class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm focus:outline-none focus:border-[#19140035] dark:focus:border-[#3E3E3A] transition-all duration-200 resize-none">{{ old('objectives', $project->objectives) }}</textarea>
                    </div>

                    <div>
                        <label for="technologies" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            التقنيات المستخدمة
                        </label>
                        <textarea name="technologies" id="technologies" rows="3"
                                  class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm focus:outline-none focus:border-[#19140035] dark:focus:border-[#3E3E3A] transition-all duration-200 resize-none">{{ old('technologies', $project->technologies) }}</textarea>
                    </div>

                    @can('approve projects')
                    <div>
                        <label for="progress_percentage" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            نسبة الإنجاز (%)
                        </label>
                        <input type="number" name="progress_percentage" id="progress_percentage" min="0" max="100"
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm focus:outline-none focus:border-[#19140035] dark:focus:border-[#3E3E3A] transition-all duration-200"
                               value="{{ old('progress_percentage', $project->progress_percentage) }}">
                    </div>
                    @endcan
                </div>

                <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <a href="{{ route('projects.show', $project) }}" class="px-5 py-1.5 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm text-sm leading-normal transition-all duration-200">
                        إلغاء
                    </a>
                    <button type="submit" class="px-5 py-1.5 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] border border-black dark:border-[#eeeeec] hover:bg-black dark:hover:bg-white rounded-sm text-sm leading-normal transition-all duration-200">
                        حفظ التغييرات
                    </button>
                </div>
            </form>

                <!-- Project Phases -->
                <div class="mt-8 pt-8 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">أجزاء المشروع</h2>
                        @if(!$project->isProposal())
                        <a href="{{ route('project-phases.create', $project) }}" class="px-5 py-1.5 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] border border-black dark:border-[#eeeeec] hover:bg-black dark:hover:bg-white rounded-sm text-sm leading-normal transition-all duration-200">
                            + إضافة جزء
                        </a>
                        @else
                        <p class="text-sm text-amber-600 dark:text-amber-400">انتظر موافقة المشرف على الاقتراح لإضافة أجزاء</p>
                        @endif
                    </div>

                    @if($project->phases->isEmpty())
                        <p class="text-[#706f6c] dark:text-[#A1A09A] text-center py-8">لا توجد أجزاء بعد. قم بإضافة جزء جديد للمشروع.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($project->phases as $phase)
                                <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-4 bg-white dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)]">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 space-x-reverse mb-2">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] font-bold">
                                                    {{ $phase->phase_number }}
                                                </span>
                                                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $phase->title }}</h3>
                                                @if($phase->percentage)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-sm bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                                                        {{ $phase->percentage }}%
                                                    </span>
                                                @endif
                                            </div>
                                            @if($phase->description)
                                                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm mb-2">{{ $phase->description }}</p>
                                            @endif
                                            <div class="flex items-center justify-between mt-3">
                                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                    الحالة: 
                                                    <span class="font-semibold 
                                                        @if($phase->status == 'approved') text-green-600 dark:text-green-400
                                                        @elseif($phase->status == 'rejected') text-red-600 dark:text-red-400
                                                        @else text-yellow-600 dark:text-yellow-400
                                                        @endif">
                                                        {{ $phase->status }}
                                                    </span>
                                                </p>
                                                <a href="{{ route('project-phases.show', $phase) }}" class="px-5 py-1.5 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm text-sm leading-normal transition-all duration-200">
                                                    عرض التفاصيل
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
        </div>
    </div>
</div>

@endsection
