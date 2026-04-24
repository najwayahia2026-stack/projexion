@extends('layouts.app')

@section('title', 'إنشاء مشروع جديد')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            <h1 class="text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                إنشاء مشروع جديد
            </h1>
            <p class="text-[#706f6c] dark:text-[#A1A09A]">ابدأ مشروع التخرج الخاص بك</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-[#161615] rounded-sm shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] p-8 lg:p-20 border border-[#e3e3e0] dark:border-[#3E3E3A] animate-fade-in" style="animation-delay: 0.1s">
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            عنوان المشروع *
                        </label>
                        <input type="text" name="title" id="title" required
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm focus:outline-none focus:border-[#19140035] dark:focus:border-[#3E3E3A] transition-all duration-200"
                               placeholder="مثال: نظام ذكي لإدارة المكتبات" value="{{ old('title') }}">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            وصف المشروع *
                        </label>
                        <textarea name="description" id="description" rows="5" required
                                  class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm focus:outline-none focus:border-[#19140035] dark:focus:border-[#3E3E3A] transition-all duration-200 resize-none"
                                  placeholder="وصف شامل للمشروع وأهدافه...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label for="objectives" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            أهداف المشروع *
                        </label>
                        <textarea name="objectives" id="objectives" rows="4" required
                                  class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm focus:outline-none focus:border-[#19140035] dark:focus:border-[#3E3E3A] transition-all duration-200 resize-none"
                                  placeholder="اذكر الأهداف الرئيسية للمشروع...">{{ old('objectives') }}</textarea>
                    </div>

                    <div>
                        <label for="technologies" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            التقنيات المستخدمة
                        </label>
                        <textarea name="technologies" id="technologies" rows="3"
                                  class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm focus:outline-none focus:border-[#19140035] dark:focus:border-[#3E3E3A] transition-all duration-200 resize-none"
                                  placeholder="مثال: Laravel, React, MySQL, Python, TensorFlow">{{ old('technologies') }}</textarea>
                    </div>

                    <div>
                        <label for="specialty_id" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            تخصص المشروع *
                        </label>
                        <div class="relative">
                            <input type="text" id="specialty_search" placeholder="ابحث عن التخصص..." 
                                   class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm focus:outline-none focus:border-[#19140035] dark:focus:border-[#3E3E3A] transition-all duration-200 mb-2"
                                   autocomplete="off">
                            <select name="specialty_id" id="specialty_id" required
                                    class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm focus:outline-none focus:border-[#19140035] dark:focus:border-[#3E3E3A] transition-all duration-200">
                                <option value="">اختر تخصص المشروع</option>
                                @foreach($specialties as $s)
                                    <option value="{{ $s->id }}" {{ old('specialty_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if($groups->count() > 0)
                    <div>
                        <label for="group_id" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            المجموعة *
                        </label>
                        <select name="group_id" id="group_id" required
                                class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm focus:outline-none focus:border-[#19140035] dark:focus:border-[#3E3E3A] transition-all duration-200">
                            <option value="">اختر المجموعة</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }} - {{ $group->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border-r-4 border-yellow-400 rounded-xl">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-400 ml-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">يجب أن تكون عضو في مجموعة قبل إنشاء مشروع. يرجى التواصل مع المشرف.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Info Box -->
                    <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border-r-4 border-amber-400 rounded-xl">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-amber-400 ml-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-amber-800 dark:text-amber-200 mb-1">اقتراح مشروع</p>
                                <p class="text-xs text-amber-700 dark:text-amber-300">سيتم إنشاء المشروع كاقتراح. بعد موافقة المشرف على الاقتراح، يمكنك إضافة أجزاء المشروع ورفع الملفات.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <a href="{{ route('projects.index') }}" 
                       class="px-5 py-1.5 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm text-sm leading-normal transition-all duration-200">
                        إلغاء
                    </a>
                    <button type="submit" 
                            class="px-5 py-1.5 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] border border-black dark:border-[#eeeeec] hover:bg-black dark:hover:bg-white rounded-sm text-sm leading-normal transition-all duration-200">
                        إنشاء المشروع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Specialty search filter
document.getElementById('specialty_search')?.addEventListener('input', function() {
    const search = this.value.toLowerCase();
    const select = document.getElementById('specialty_id');
    Array.from(select.options).forEach(opt => {
        if (opt.value === '') {
            opt.style.display = 'block';
            return;
        }
        opt.style.display = opt.textContent.toLowerCase().includes(search) ? 'block' : 'none';
    });
});
</script>
@endsection
