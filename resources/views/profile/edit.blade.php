@extends('layouts.app')

@section('title', 'تعديل الملف الشخصي')

@section('content')
@php
    $isStudent = $user->hasRole('student');
    $isSupervisor = $user->hasRole('supervisor');
    $isCommittee = $user->hasRole('committee');
    $isAdmin = $user->hasRole('admin') || $user->hasRole('department_admin');

    $roleTheme = $isStudent
        ? 'from-blue-600 to-purple-600'
        : ($isSupervisor
            ? 'from-emerald-600 to-teal-600'
            : ($isCommittee
                ? 'from-amber-600 to-orange-600'
                : 'from-rose-600 to-pink-600'));
@endphp

<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 animate-fade-in">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                <span class="bg-gradient-to-r {{ $roleTheme }} bg-clip-text text-transparent">
                    تعديل الملف الشخصي
                </span>
            </h1>
            <p class="text-gray-600">
                قم بتحديث معلوماتك الشخصية لضمان دقة البيانات في النظام.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-200">
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="bg-red-50 border-r-4 border-red-400 p-4 rounded-lg">
                        <div class="flex">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-red-800 mb-2">يرجى تصحيح الأخطاء التالية:</h3>
                                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- المعلومات الأساسية - متاحة للجميع --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        المعلومات الأساسية
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل *</label>
                            <input type="text" name="name" id="name" required 
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900" 
                                   value="{{ old('name', $user->name) }}">
                        </div>

                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">اسم المستخدم</label>
                            <input type="text" name="username" id="username" 
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900" 
                                   value="{{ old('username', $user->username) }}">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني *</label>
                        <input type="email" name="email" id="email" required 
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900" 
                               value="{{ old('email', $user->email) }}">
                    </div>
                </div>

                {{-- التخصص والمعلومات الأكاديمية - تخفى عن الأدمن --}}
                @if(!$isAdmin)
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        التخصص والمعلومات الأكاديمية
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">الكلية *</label>
                            <select name="department" id="college_select" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                                <option value="">-- اختر الكلية --</option>
                                <option value="it" {{ old('department', $user->department) == 'it' ? 'selected' : '' }}>كلية الحاسوب وتكنولوجيا المعلومات</option>
                                <option value="engineering" {{ old('department', $user->department) == 'engineering' ? 'selected' : '' }}>كلية الهندسة والعمارة</option>
                                <option value="business" {{ old('department', $user->department) == 'business' ? 'selected' : '' }}>كلية العلوم الإدارية</option>
                                <option value="medical" {{ old('department', $user->department) == 'medical' ? 'selected' : '' }}>كلية الطب والعلوم الصحية</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">التخصص الأكاديمي *</label>
                            <select name="specialty_id" id="specialty_id" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                                <option value="">-- اختر التخصص --</option>
                                @foreach(\App\Models\Specialty::all() as $s)
                                    <option value="{{ $s->id }}" data-name="{{ $s->name }}" 
                                        {{ old('specialty_id', $user->specialty_id) == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                {{-- معلومات التواصل --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        معلومات التواصل
                    </h3>
                    <div class="grid grid-cols-1 {{ $isAdmin ? 'md:grid-cols-1' : 'md:grid-cols-2' }} gap-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                            <input type="tel" name="phone" id="phone" dir="ltr"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900" 
                                   value="{{ old('phone', $user->phone) }}" placeholder="05xxxxxxxx">
                        </div>

                        {{-- سنة التخرج تخفى عن الأدمن --}}
                        @if(!$isAdmin)
                        <div>
                            <label for="graduation_year" class="block text-sm font-medium text-gray-700 mb-2">سنة التخرج</label>
                            <input type="number" name="graduation_year" id="graduation_year" min="2000" max="2100" dir="ltr"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900" 
                                   value="{{ old('graduation_year', $user->graduation_year) }}" placeholder="مثال: 2024">
                        </div>
                        @endif
                    </div>
                </div>

                {{-- حقول الطالب فقط --}}
                @if($isStudent)
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        معلومات الطالب الإضافية
                    </h3>
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">رقم الطالب</label>
                        <input type="text" name="student_id" id="student_id" dir="ltr"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900" 
                               value="{{ old('student_id', $user->student_id) }}" placeholder="مثال: 20201234">
                    </div>
                </div>
                @endif

                {{-- تغيير كلمة المرور --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        تغيير كلمة المرور
                    </h3>
                    <input type="password" name="password" id="password" 
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900" 
                           placeholder="اتركها فارغة إذا لم تكن تريد تغييرها">
                </div>

                {{-- نبذة تعريفية --}}
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">نبذة تعريفية</label>
                    <textarea name="bio" id="bio" rows="3" 
                              class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900" 
                              placeholder="نبذة تعريفية عنك (اختياري)">{{ old('bio', $user->bio) }}</textarea>
                </div>

                {{-- أزرار التحكم --}}
                <div class="flex items-center justify-between pt-6">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all">
                        إلغاء
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r {{ $roleTheme }} text-white rounded-lg hover:opacity-90 transition-all shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- الجافا سكريبت فقط للمستخدمين الذين لديهم تخصص (ليس الأدمن) --}}
@if(!$isAdmin)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const collegeSelect = document.getElementById('college_select');
    const specialtySelect = document.getElementById('specialty_id');
    const specialtyOptions = Array.from(specialtySelect.options);
    const currentSpecialtyId = "{{ old('specialty_id', $user->specialty_id) }}";

    const collegeKeywords = {
        'it': ['حاسوب', 'برمجيات', 'نظم', 'it', 'شبكات', 'تقنية', 'معلومات', 'computer', 'software'],
        'engineering': ['هندسة', 'عمارة', 'مدني', 'كهرباء', 'ميكانيك', 'engineering'],
        'business': ['إدارة', 'محاسبة', 'اقتصاد', 'تسويق', 'مالية', 'business'],
        'medical': ['طب', 'صيدلة', 'تمريض', 'مختبرات', 'health', 'medical']
    };

    function filterSpecialties(collegeValue, isInitialLoad = false) {
        if (!collegeValue) {
            specialtyOptions.forEach(opt => opt.style.display = 'block');
            return;
        }

        const keywords = collegeKeywords[collegeValue] || [];
        specialtyOptions.forEach(option => {
            if (option.value === "") {
                option.style.display = 'block';
                return;
            }
            const specialtyName = option.getAttribute('data-name').toLowerCase();
            const isMatch = keywords.some(key => specialtyName.includes(key));
            option.style.display = isMatch ? 'block' : 'none';
        });

        if (!isInitialLoad) {
            specialtySelect.value = "";
        }
    }

    if (collegeSelect && specialtySelect) {
        collegeSelect.addEventListener('change', function() {
            filterSpecialties(this.value, false);
        });

        if (collegeSelect.value) {
            filterSpecialties(collegeSelect.value, true);
            specialtySelect.value = currentSpecialtyId;
        }
    }
});
</script>
@endif
@endsection