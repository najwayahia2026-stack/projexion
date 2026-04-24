@extends('layouts.app')

@section('title', 'تعديل المستخدم')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">
                        <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            تعديل المستخدم
                        </span>
                    </h1>
                    <p class="text-gray-600">تحديث معلومات المستخدم: {{ $user->name }}</p>
                </div>
                <a href="{{ route('users.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    رجوع
                </a>
            </div>
        </div>

        <!-- User Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-200">
            <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="bg-red-50 border-r-4 border-red-400 p-4 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 ml-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
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

                <!-- Basic Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        المعلومات الأساسية
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                الاسم الكامل *
                            </label>
                            <input type="text" name="name" id="name" required
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder:text-gray-400"
                                   value="{{ old('name', $user->name) }}">
                        </div>

                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم المستخدم
                            </label>
                            <input type="text" name="username" id="username"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder:text-gray-400"
                                   value="{{ old('username', $user->username) }}">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            البريد الإلكتروني *
                        </label>
                        <input type="email" name="email" id="email" required
                               class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-[#2a2a28] text-[#1b1b18] dark:text-[#EDEDEC] placeholder:text-gray-400"
                               value="{{ old('email', $user->email) }}">
                    </div>

                    <div class="mt-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            كلمة المرور (اتركها فارغة إذا لم ترد تغييرها)
                        </label>
                        <input type="password" name="password" id="password"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder:text-gray-400"
                               placeholder="••••••••">
                    </div>

                    <div class="mt-4">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            الدور *
                        </label>
                        <select name="role" id="role" required
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $user->getRoleNames()->first()) == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="committeeSpecialtyField" class="mt-4 {{ $user->hasRole('committee') ? '' : 'hidden' }}">
                        <label for="specialty_id" class="block text-sm font-medium text-gray-700 mb-2">
                            التخصص (لجنة التقييم) *
                        </label>
                        <div>
                            <input type="text" id="edit_specialty_search" placeholder="ابحث عن التخصص..."
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 mb-2">
                            <select name="specialty_id" id="specialty_id"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900">
                                <option value="">اختر التخصص</option>
                                @foreach($specialties ?? [] as $s)
                                    <option value="{{ $s->id }}" {{ old('specialty_id', $user->specialty_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 ml-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        معلومات إضافية
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الطالب
                            </label>
                            <input type="text" name="student_id" id="student_id"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder:text-gray-400"
                                   value="{{ old('student_id', $user->student_id) }}">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الهاتف
                            </label>
                            <input type="text" name="phone" id="phone"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder:text-gray-400"
                                   value="{{ old('phone', $user->phone) }}">
                        </div>

                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                القسم
                            </label>
                            <input type="text" name="department" id="department"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder:text-gray-400"
                                   value="{{ old('department', $user->department) }}">
                        </div>

                        <div>
                            <label for="graduation_year" class="block text-sm font-medium text-gray-700 mb-2">
                                سنة التخرج
                            </label>
                            <input type="number" name="graduation_year" id="graduation_year" min="2020" max="2100"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder:text-gray-400"
                                   value="{{ old('graduation_year', $user->graduation_year) }}">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                            نبذة تعريفية
                        </label>
                        <textarea name="bio" id="bio" rows="3"
                                  class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder:text-gray-400">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                </div>

                <script>
                document.getElementById('role')?.addEventListener('change', function() {
                    const field = document.getElementById('committeeSpecialtyField');
                    const specialtySelect = document.getElementById('specialty_id');
                    if (this.value === 'committee') {
                        field?.classList.remove('hidden');
                        specialtySelect?.setAttribute('required', 'required');
                    } else {
                        field?.classList.add('hidden');
                        specialtySelect?.removeAttribute('required');
                        if (specialtySelect) specialtySelect.value = '';
                    }
                });
                document.getElementById('edit_specialty_search')?.addEventListener('input', function() {
                    const search = this.value.toLowerCase();
                    const select = document.getElementById('specialty_id');
                    if (select) {
                        Array.from(select.options).forEach(opt => {
                            if (opt.value === '') { opt.style.display = 'block'; return; }
                            opt.style.display = opt.textContent.toLowerCase().includes(search) ? 'block' : 'none';
                        });
                    }
                });
                </script>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-6">
                    <a href="{{ route('users.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                        إلغاء
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
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
@endsection

