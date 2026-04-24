@extends('layouts.app')

@section('title', 'تفاصيل المستخدم - ' . $user->name)

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-2xl shadow-xl p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-10 rounded-full -ml-32 -mb-32"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-6">
                            <div class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white text-4xl font-bold border-4 border-white/30">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <h1 class="text-4xl font-bold mb-2">{{ $user->name }}</h1>
                                <div class="flex items-center gap-4 flex-wrap">
                                    @if($user->hasRole('student'))
                                        <span class="px-4 py-1.5 bg-green-500/30 backdrop-blur-sm border border-green-300/30 rounded-full text-sm font-semibold">
                                            طالب
                                        </span>
                                    @elseif($user->hasRole('supervisor'))
                                        <span class="px-4 py-1.5 bg-purple-500/30 backdrop-blur-sm border border-purple-300/30 rounded-full text-sm font-semibold">
                                            مشرف
                                        </span>
                                    @elseif($user->hasRole('admin'))
                                        <span class="px-4 py-1.5 bg-red-500/30 backdrop-blur-sm border border-red-300/30 rounded-full text-sm font-semibold">
                                            مدير النظام
                                        </span>
                                    @elseif($user->hasRole('committee'))
                                        <span class="px-4 py-1.5 bg-blue-500/30 backdrop-blur-sm border border-blue-300/30 rounded-full text-sm font-semibold">
                                            لجنة التقييم
                                        </span>
                                    @endif
                                    @if($user->isBanned())
                                        <span class="px-4 py-1.5 bg-red-500/30 backdrop-blur-sm border border-red-300/30 rounded-full text-sm font-semibold">
                                            محظور
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @if(Auth::user()->hasRole('supervisor') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('department_admin'))
                                @if($user->hasRole('student'))
                                <button 
                                    type="button"
                                    onclick="openMessageModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"
                                    class="px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 hover:bg-white/30 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    إرسال رسالة
                                </button>
                                @endif
                            @endif
                            <a href="{{ url()->previous() }}" 
                               class="px-4 py-3 bg-white/20 backdrop-blur-sm border border-white/30 hover:bg-white/30 text-white font-semibold rounded-lg transition-all duration-200">
                                رجوع
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Send Message Form (for supervisors) -->
        @if(Auth::user()->hasRole('supervisor') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('department_admin'))
        @if($user->hasRole('student'))
        <div class="bg-white dark:bg-[#161615] rounded-xl shadow-lg p-5 border border-[#e3e3e0] dark:border-[#3E3E3A] mb-6">
            <h2 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 flex items-center">
                <svg class="w-5 h-5 ml-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                إرسال رسالة إلى {{ $user->name }}
            </h2>
            
            @if($errors->any())
                <div class="mb-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400 ml-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <h4 class="text-xs font-semibold text-red-800 dark:text-red-200 mb-1">يرجى تصحيح الأخطاء التالية:</h4>
                            <ul class="list-disc list-inside text-xs text-red-700 dark:text-red-300 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(session('success'))
                <div class="mb-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-600 dark:text-green-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xs text-green-700 dark:text-green-300">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xs text-red-700 dark:text-red-300">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('notifications.send') }}" method="POST" enctype="multipart/form-data" id="directMessageForm">
                @csrf
                <input type="hidden" name="recipient_type" value="specific">
                <input type="hidden" name="recipient_id" value="{{ $user->id }}">
                
                <div class="space-y-3">
                    <div>
                        <label for="direct_title" class="block text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-1.5">
                            العنوان <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="title" 
                            id="direct_title" 
                            required
                            value="{{ old('title') }}"
                            class="w-full px-3 py-2 text-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-[#1a1a18] dark:text-[#EDEDEC] placeholder:text-gray-400 {{ $errors->has('title') ? 'border-red-500' : '' }}"
                            placeholder="عنوان الرسالة"
                        >
                        @error('title')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="direct_message" class="block text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-1.5">
                            المحتوى <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="message" 
                            id="direct_message" 
                            rows="4" 
                            required
                            class="w-full px-3 py-2 text-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-[#1a1a18] dark:text-[#EDEDEC] placeholder:text-gray-400 resize-none {{ $errors->has('message') ? 'border-red-500' : '' }}"
                            placeholder="اكتب محتوى الرسالة هنا..."
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="direct_attachment" class="block text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-1.5">
                            إرفاق ملف (اختياري)
                        </label>
                        <div class="flex items-center gap-3">
                            <label class="flex-1 px-3 py-2 border-2 border-dashed border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg hover:border-purple-500 dark:hover:border-purple-500 transition-colors cursor-pointer bg-[#fafafa] dark:bg-[#1a1a18]">
                                <div class="flex items-center justify-center gap-2 text-[#706f6c] dark:text-[#A1A09A]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <span id="fileLabel" class="text-xs">اختر ملف أو اسحب الملف هنا</span>
                                </div>
                                <input 
                                    type="file" 
                                    name="attachment" 
                                    id="direct_attachment" 
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,.rar"
                                    class="sr-only"
                                    onchange="updateFileLabel(this)"
                                >
                            </label>
                        </div>
                        <p class="mt-1.5 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                            PDF, DOC, DOCX, JPG, PNG, ZIP, RAR (حد أقصى 10 ميجابايت)
                        </p>
                        @error('attachment')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end pt-2">
                        <button 
                            type="submit" 
                            id="directSendBtn"
                            class="px-5 py-2.5 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all font-medium text-sm shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span id="directSendBtnText">إرسال الرسالة</span>
                            <span id="directSendBtnLoader" class="hidden">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif
        @endif

        <!-- User Information Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Basic Information -->
            <div class="lg:col-span-2 bg-white dark:bg-[#161615] rounded-xl shadow-lg p-6 border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6 flex items-center">
                    <svg class="w-6 h-6 ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    المعلومات الأساسية
                </h2>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">البريد الإلكتروني</p>
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-semibold">{{ $user->email }}</p>
                        </div>
                    </div>
                    @if($user->username)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">اسم المستخدم</p>
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-semibold">@{{ $user->username }}</p>
                        </div>
                    </div>
                    @endif
                    @if($user->student_id)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">رقم الطالب</p>
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-semibold">{{ $user->student_id }}</p>
                        </div>
                    </div>
                    @endif
                    @if($user->phone)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">رقم الهاتف</p>
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-semibold">{{ $user->phone }}</p>
                        </div>
                    </div>
                    @endif
                    @if($user->department)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">القسم</p>
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-semibold">{{ $user->department }}</p>
                        </div>
                    </div>
                    @endif
                    @if($user->graduation_year)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">سنة التخرج</p>
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-semibold">{{ $user->graduation_year }}</p>
                        </div>
                    </div>
                    @endif
                    @if($user->bio)
                    <div>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-2">نبذة</p>
                        <p class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $user->bio }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="bg-white dark:bg-[#161615] rounded-xl shadow-lg p-6 border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6 flex items-center">
                    <svg class="w-6 h-6 ml-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    الإحصائيات
                </h2>
                <div class="space-y-4">
                    <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">المجموعات</p>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $user->groups->count() }}</p>
                    </div>
                    @if($user->hasRole('student'))
                    <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg border border-green-200 dark:border-green-800">
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">المشاريع</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ isset($projects) ? $projects->count() : 0 }}</p>
                    </div>
                    @endif
                    @if($user->hasRole('supervisor'))
                    <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-1">المجموعات المشرفة عليها</p>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $user->supervisedGroups->count() }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Groups Section -->
        @if($user->groups->count() > 0)
        <div class="bg-white dark:bg-[#161615] rounded-xl shadow-lg p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] mb-6">
            <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6 flex items-center">
                <svg class="w-6 h-6 ml-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                المجموعات
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($user->groups as $group)
                <a href="{{ route('groups.show', $group) }}" class="block p-4 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-lg border border-indigo-200 dark:border-indigo-800 hover:shadow-lg transition-all">
                    <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">{{ $group->name }}</h3>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $group->code }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Projects Section -->
        @if($user->hasRole('student') && isset($projects) && $projects->count() > 0)
        <div class="bg-white dark:bg-[#161615] rounded-xl shadow-lg p-6 border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6 flex items-center">
                <svg class="w-6 h-6 ml-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                المشاريع
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($projects as $project)
                <a href="{{ route('projects.show', $project) }}" class="block p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg border border-green-200 dark:border-green-800 hover:shadow-lg transition-all">
                    <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">{{ $project->title }}</h3>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ Str::limit($project->description, 80) }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Message Modal -->
@if(Auth::user()->hasRole('supervisor') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('department_admin'))
@if($user->hasRole('student'))
<div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[9999] flex items-center justify-center p-4" onclick="if(event.target === this) closeMessageModal()">
    <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-2xl max-w-md w-full p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] relative z-[10000]" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">إرسال رسالة</h3>
            <button type="button" onclick="closeMessageModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 ml-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-red-800 dark:text-red-200 mb-2">يرجى تصحيح الأخطاء التالية:</h4>
                        <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
        @endif
        
        <form id="messageForm" method="POST" action="{{ route('notifications.send') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="recipient_type" value="specific">
            <input type="hidden" name="recipient_id" id="message_recipient_id" required>
            
            <div class="mb-4 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-lg border border-indigo-200 dark:border-indigo-800">
                <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">إلى:</label>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div>
                        <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-semibold" id="message_recipient_name"></p>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]" id="message_recipient_email"></p>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="message_title" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    العنوان <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="message_title" 
                    required
                    value="{{ old('title') }}"
                    class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-[#1a1a18] dark:text-[#EDEDEC] placeholder:text-gray-400 {{ $errors->has('title') ? 'border-red-500' : '' }}"
                    placeholder="عنوان الرسالة"
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="message_content" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    الرسالة <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="message" 
                    id="message_content" 
                    rows="4" 
                    required
                    class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-[#1a1a18] dark:text-[#EDEDEC] placeholder:text-gray-400 resize-none {{ $errors->has('message') ? 'border-red-500' : '' }}"
                    placeholder="اكتب رسالتك هنا..."
                >{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <span class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    إرفاق ملف (اختياري)
                </span>
                <label for="message_attachment" class="block w-full px-4 py-3 border-2 border-dashed border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg hover:border-purple-500 dark:hover:border-purple-500 transition-colors cursor-pointer bg-[#fafafa] dark:bg-[#1a1a18]">
                    <div class="flex items-center justify-center gap-2 text-[#706f6c] dark:text-[#A1A09A]">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <span id="modalFileLabel" class="text-sm">اختر ملف أو اسحب الملف هنا</span>
                    </div>
                    <input 
                        type="file" 
                        name="attachment" 
                        id="message_attachment" 
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,.rar"
                        class="sr-only"
                        onchange="updateModalFileLabel(this)"
                    >
                </label>
                <p class="mt-1.5 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    PDF, DOC, DOCX, JPG, PNG, ZIP, RAR (حد أقصى 10 ميجابايت)
                </p>
                @error('attachment')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex space-x-3 space-x-reverse">
                <button 
                    type="submit" 
                    id="sendMessageBtn"
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all font-semibold shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span id="sendBtnText">إرسال</span>
                    <span id="sendBtnLoader" class="hidden">
                        <svg class="animate-spin h-5 w-5 inline-block ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
                <button 
                    type="button" 
                    onclick="closeMessageModal()"
                    class="px-6 py-3 bg-gray-100 dark:bg-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg hover:bg-gray-200 dark:hover:bg-[#4E4E4A] transition-all font-medium"
                >
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endif

@push('scripts')
<script>
// Update file label when file is selected
function updateFileLabel(input) {
    const label = document.getElementById('fileLabel');
    if (label) updateFileLabelFor(label, input, 'اختر ملف أو اسحب الملف هنا');
}

// Update modal file label when file is selected
function updateModalFileLabel(input) {
    const label = document.getElementById('modalFileLabel');
    if (label) updateFileLabelFor(label, input, 'اختر ملف أو اسحب الملف هنا');
}

function updateFileLabelFor(labelEl, input, defaultText) {
    if (input.files && input.files[0]) {
        labelEl.textContent = input.files[0].name;
        labelEl.classList.add('text-purple-600', 'dark:text-purple-400');
    } else {
        labelEl.textContent = defaultText;
        labelEl.classList.remove('text-purple-600', 'dark:text-purple-400');
    }
}

// Handle direct message form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('directMessageForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const button = document.getElementById('directSendBtn');
            const buttonText = document.getElementById('directSendBtnText');
            const buttonLoader = document.getElementById('directSendBtnLoader');
            
            // Show loading state
            if (button && buttonText && buttonLoader) {
                button.disabled = true;
                buttonText.classList.add('hidden');
                buttonLoader.classList.remove('hidden');
            }
            
            // Allow form to submit
            return true;
        });
    }
});
// Open message modal
function openMessageModal(userId, userName, userEmail) {
    const modal = document.getElementById('messageModal');
    const recipientIdField = document.getElementById('message_recipient_id');
    const recipientNameField = document.getElementById('message_recipient_name');
    const recipientEmailField = document.getElementById('message_recipient_email');
    const form = document.getElementById('messageForm');
    
    if (!modal || !recipientIdField || !recipientNameField || !form) {
        console.error('Modal elements not found');
        return;
    }
    
    // Set recipient data
    recipientIdField.value = userId;
    recipientNameField.textContent = userName || 'مستخدم';
    if (recipientEmailField) {
        recipientEmailField.textContent = userEmail || '';
    }
    
    // Reset form but keep recipient data
    const titleField = document.getElementById('message_title');
    const messageField = document.getElementById('message_content');
    if (titleField) titleField.value = '';
    if (messageField) messageField.value = '';
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Focus on title input
    if (titleField) {
        setTimeout(() => titleField.focus(), 100);
    }
}

// Close message modal
function closeMessageModal() {
    const modal = document.getElementById('messageModal');
    const form = document.getElementById('messageForm');
    
    if (modal) {
        modal.classList.add('hidden');
    }
    
    document.body.style.overflow = 'auto';
    
    if (form) {
        form.reset();
        // Reset recipient fields
        const recipientIdField = document.getElementById('message_recipient_id');
        const recipientNameField = document.getElementById('message_recipient_name');
        const recipientEmailField = document.getElementById('message_recipient_email');
        
        if (recipientIdField) recipientIdField.value = '';
        if (recipientNameField) recipientNameField.textContent = '';
        if (recipientEmailField) recipientEmailField.textContent = '';
        
        // Reset button state
        resetMessageButton();
    }
}

// Reset message button state
function resetMessageButton() {
    const button = document.getElementById('sendMessageBtn');
    const buttonText = document.getElementById('sendBtnText');
    const buttonLoader = document.getElementById('sendBtnLoader');
    
    if (button && buttonText && buttonLoader) {
        button.disabled = false;
        buttonText.classList.remove('hidden');
        buttonLoader.classList.add('hidden');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('messageForm');
    
    // Handle form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            const recipientIdField = document.getElementById('message_recipient_id');
            const titleField = document.getElementById('message_title');
            const messageField = document.getElementById('message_content');
            const button = document.getElementById('sendMessageBtn');
            const buttonText = document.getElementById('sendBtnText');
            const buttonLoader = document.getElementById('sendBtnLoader');
            
            // Validate recipient_id
            if (!recipientIdField || !recipientIdField.value) {
                e.preventDefault();
                alert('خطأ: لم يتم تحديد المستلم');
                return false;
            }
            
            // Validate title and message
            if (!titleField || !titleField.value.trim()) {
                e.preventDefault();
                alert('يرجى إدخال عنوان الرسالة');
                if (titleField) titleField.focus();
                return false;
            }
            
            if (!messageField || !messageField.value.trim()) {
                e.preventDefault();
                alert('يرجى إدخال محتوى الرسالة');
                if (messageField) messageField.focus();
                return false;
            }
            
            // Show loading state
            if (button && buttonText && buttonLoader) {
                button.disabled = true;
                buttonText.classList.add('hidden');
                buttonLoader.classList.remove('hidden');
            }
            
            // Allow form to submit
            return true;
        });
    }
    
    // Open modal if there are validation errors
    @if($errors->any() || session('error'))
        const modalElement = document.getElementById('messageModal');
        if (modalElement) {
            modalElement.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            // Set recipient data if available
            const recipientIdField = document.getElementById('message_recipient_id');
            if (recipientIdField && !recipientIdField.value) {
                recipientIdField.value = {{ $user->id }};
            }
            const recipientNameField = document.getElementById('message_recipient_name');
            if (recipientNameField && !recipientNameField.textContent) {
                recipientNameField.textContent = '{{ addslashes($user->name) }}';
            }
            const recipientEmailField = document.getElementById('message_recipient_email');
            if (recipientEmailField && !recipientEmailField.textContent) {
                recipientEmailField.textContent = '{{ addslashes($user->email) }}';
            }
        }
    @endif
});
</script>
@endpush
@endsection
