@extends('layouts.app')

@section('title', 'التسجيل')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden" dir="rtl">
    {{-- Animated Background Blobs --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>

    <div class="max-w-2xl w-full space-y-8 relative z-10 animate-fade-in">
        {{-- Header --}}
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('7.png') }}" width="150" alt="ProjexiOn Logo">
            </div>
            <h2 class="text-4xl font-extrabold text-gray-900 mb-2">
                <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                    إنشاء حساب جديد
                </span>
            </h2>
            <p class="text-gray-600">انضم إلى منصة ProjexiOn لإدارة مشاريع التخرج</p>
        </div>

        {{-- Registration Form --}}
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl p-8 border border-gray-200">
            <form class="space-y-6" action="{{ route('register') }}" method="POST" id="registerForm">
                @csrf

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="bg-red-50 border-r-4 border-red-400 p-4 rounded-lg mb-4">
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Basic Information --}}
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        المعلومات الأساسية
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل *</label>
                            <input type="text" name="name" required value="{{ old('name') }}"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">اسم المستخدم</label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني *</label>
                        <input type="email" name="email" required value="{{ old('email') }}"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور *</label>
                            <input type="password" name="password" required
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور *</label>
                            <input type="password" name="password_confirmation" required
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>
                    </div>
                </div>

                {{-- User Role --}}
                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">نوع المستخدم *</label>
                    <select id="role" name="role" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                        <option value="">اختر نوع المستخدم</option>
                        <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>طالب</option>
                        <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>مشرف</option>
                        <option value="committee" {{ old('role') == 'committee' ? 'selected' : '' }}>لجنة التقييم</option>
                    </select>
                </div>

                {{-- College & Specialty --}}
                <div class="border-b border-gray-200 pb-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">الكلية *</label>
                        <select name="department" id="college_select" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                            <option value="">-- اختر الكلية --</option>
                            <option value="it" {{ old('department') == 'it' ? 'selected' : '' }}>كلية الحاسوب وتكنولوجيا المعلومات</option>
                            <option value="engineering" {{ old('department') == 'engineering' ? 'selected' : '' }}>كلية الهندسة والعمارة</option>
                            <option value="business" {{ old('department') == 'business' ? 'selected' : '' }}>كلية العلوم الإدارية</option>
                            <option value="medical" {{ old('department') == 'medical' ? 'selected' : '' }}>كلية الطب والعلوم الصحية</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">التخصص الأكاديمي *</label>
                        <select name="specialty_id" id="specialty_id" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                            <option value="">-- اختر التخصص --</option>
                            @foreach(\App\Models\Specialty::all() as $specialty)
                                <option value="{{ $specialty->id }}" data-name="{{ $specialty->name }}" {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Contact Information (for all users) --}}
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        معلومات التواصل
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" dir="ltr"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="05xxxxxxxx">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">سنة التخرج</label>
                            <input type="number" name="graduation_year" id="graduation_year" min="2000" max="2100" value="{{ old('graduation_year') }}" dir="ltr"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="مثال: 2024">
                        </div>
                    </div>
                </div>

                {{-- Student Specific Fields (only for students) --}}
                <div id="studentFields" class="border-b border-gray-200 pb-4 hidden">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        معلومات الطالب الإضافية
                    </h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">رقم الطالب</label>
                        <input type="text" name="student_id" id="student_id" value="{{ old('student_id') }}" dir="ltr"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                               placeholder="مثال: 20201234">
                    </div>
                </div>

                {{-- Additional Information for All Users --}}
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        نبذة تعريفية
                    </h3>
                    <div>
                        <textarea name="bio" id="bio" rows="3" placeholder="نبذة تعريفية عنك (اختياري)"
                                  class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">{{ old('bio') }}</textarea>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div>
                    <button type="submit" id="registerButton"
                            class="group relative w-full flex justify-center items-center py-3 px-4 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 hover:from-blue-700 hover:via-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="absolute right-0 inset-y-0 flex items-center pr-3">
                            <svg class="h-5 w-5 text-white group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                        <span id="registerButtonText">إنشاء الحساب</span>
                        <span id="registerButtonLoader" class="hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors">
                        لديك حساب بالفعل؟ <span class="font-semibold">سجل الدخول</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-blob { animation: blob 7s infinite; }
.animation-delay-2000 { animation-delay: 2s; }
.animation-delay-4000 { animation-delay: 4s; }
.animate-fade-in { animation: fade-in 0.6s ease-out forwards; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const roleSelect = document.getElementById('role');
    const studentFields = document.getElementById('studentFields');
    const collegeSelect = document.getElementById('college_select');
    const specialtySelect = document.getElementById('specialty_id');
    const registerForm = document.getElementById('registerForm');
    const registerButton = document.getElementById('registerButton');
    const registerButtonText = document.getElementById('registerButtonText');
    const registerButtonLoader = document.getElementById('registerButtonLoader');
    const passwordInput = document.querySelector('input[name="password"]');
    const passwordConfirmation = document.querySelector('input[name="password_confirmation"]');

    // Store original specialty options for filtering
    let specialtyOptions = [];
    if (specialtySelect) {
        specialtyOptions = Array.from(specialtySelect.options).map(opt => ({
            value: opt.value,
            text: opt.text,
            name: opt.getAttribute('data-name') || opt.text,
            display: true
        }));
    }

    // College keywords mapping for specialty filtering
    const collegeKeywords = {
        'it': ['حاسوب', 'برمجيات', 'نظم', 'it', 'شبكات', 'تقنية', 'معلومات', 'computer', 'software'],
        'engineering': ['هندسة', 'عمارة', 'مدني', 'كهرباء', 'ميكانيك', 'engineering'],
        'business': ['إدارة', 'محاسبة', 'اقتصاد', 'تسويق', 'مالية', 'business'],
        'medical': ['طب', 'صيدلة', 'تمريض', 'مختبرات', 'health', 'medical']
    };

    // Function to filter specialties based on selected college
    function filterSpecialtiesByCollege() {
        if (!specialtySelect) return;
        
        const selectedCollege = collegeSelect ? collegeSelect.value : '';
        
        // Clear current options except the first placeholder
        while (specialtySelect.options.length > 1) {
            specialtySelect.remove(1);
        }
        
        // Add filtered options back
        specialtyOptions.forEach(opt => {
            if (opt.value === '') return;
            
            const specialtyName = opt.name.toLowerCase();
            let shouldDisplay = true;
            
            if (selectedCollege) {
                const keywords = collegeKeywords[selectedCollege] || [];
                shouldDisplay = keywords.some(keyword => specialtyName.includes(keyword));
            }
            
            if (shouldDisplay) {
                const option = document.createElement('option');
                option.value = opt.value;
                option.textContent = opt.text;
                option.setAttribute('data-name', opt.name);
                specialtySelect.appendChild(option);
            }
        });
        
        // Reset selected value
        specialtySelect.value = '';
    }

    // Function to toggle student fields visibility
    function toggleStudentFields() {
        if (!studentFields) return;
        const isStudent = roleSelect && roleSelect.value === 'student';
        
        if (isStudent) {
            studentFields.classList.remove('hidden');
            // Enable student-specific inputs
            const studentIdInput = document.getElementById('student_id');
            if (studentIdInput) studentIdInput.disabled = false;
        } else {
            studentFields.classList.add('hidden');
            // Disable and clear student-specific inputs
            const studentIdInput = document.getElementById('student_id');
            if (studentIdInput) {
                studentIdInput.disabled = true;
                studentIdInput.value = '';
            }
        }
    }

    // Function to reset button state
    function resetRegisterButton() {
        if (registerButton && registerButtonText && registerButtonLoader) {
            registerButton.disabled = false;
            registerButtonText.classList.remove('hidden');
            registerButtonLoader.classList.add('hidden');
        }
    }

    // Event Listeners
    if (roleSelect) {
        roleSelect.addEventListener('change', toggleStudentFields);
    }
    
    if (collegeSelect) {
        collegeSelect.addEventListener('change', filterSpecialtiesByCollege);
    }

    // Form submission handler
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            // Basic client-side validation
            const name = document.querySelector('input[name="name"]')?.value.trim();
            const email = document.querySelector('input[name="email"]')?.value.trim();
            const role = roleSelect?.value;
            
            if (!name || !email || !role) {
                e.preventDefault();
                alert('يرجى ملء جميع الحقول المطلوبة');
                resetRegisterButton();
                return false;
            }
            
            // Check password match
            if (passwordInput && passwordConfirmation && passwordInput.value !== passwordConfirmation.value) {
                e.preventDefault();
                alert('كلمة المرور غير متطابقة');
                resetRegisterButton();
                return false;
            }
            
            // Show loading state
            if (registerButton && registerButtonText && registerButtonLoader) {
                registerButton.disabled = true;
                registerButtonText.classList.add('hidden');
                registerButtonLoader.classList.remove('hidden');
                
                // Timeout protection: re-enable button after 30 seconds
                setTimeout(function() {
                    if (registerButton && registerButton.disabled) {
                        resetRegisterButton();
                        alert('انتهت مهلة الانتظار. يرجى المحاولة مرة أخرى.');
                    }
                }, 30000);
            }
            
            return true;
        });
    }

    // Initialize on page load
    toggleStudentFields();
    if (collegeSelect && collegeSelect.value) {
        filterSpecialtiesByCollege();
    }
    
    // Reset button on page load (in case of validation errors)
    resetRegisterButton();
    
    // Also reset on window load
    window.addEventListener('load', resetRegisterButton);
});
</script>
@endsection