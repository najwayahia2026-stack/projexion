<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="ProjexiOn منصة شاملة لإدارة مشاريع التخرج - تعاون، تقييم، وفحص الانتحال. للطلاب والمشرفين ولجان التقييم.">

    <title>ProjexiOn - منصة ذكية لإدارة مشاريع التخرج</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Cairo', sans-serif; }
        .nav-blur { backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .animate-float { animation: float 5s ease-in-out infinite; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
        .stagger-1 { animation-delay: 0.1s; opacity: 0; }
        .stagger-2 { animation-delay: 0.2s; opacity: 0; }
        .stagger-3 { animation-delay: 0.3s; opacity: 0; }
        .stagger-4 { animation-delay: 0.4s; opacity: 0; }
        @keyframes hero-image-glow {
            0%, 100% { box-shadow: 0 0 0 1px rgba(99, 102, 241, 0.2), 0 8px 24px rgba(0,0,0,0.08), 0 24px 48px -12px rgba(139, 92, 246, 0.15); }
            50% { box-shadow: 0 0 0 1px rgba(99, 102, 241, 0.35), 0 8px 24px rgba(0,0,0,0.1), 0 24px 48px -12px rgba(139, 92, 246, 0.25); }
        }
        .dark .hero-image-glow {
            box-shadow: 0 0 0 1px rgba(255,255,255,0.08), 0 8px 24px rgba(0,0,0,0.4);
        }
        .hero-image-frame { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%); }
    </style>
</head>
<body class="font-sans antialiased bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen overflow-x-hidden">
    {{-- Background Decoration (same as login) --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 dark:opacity-40 animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 dark:opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 dark:opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    {{-- Navbar --}}
    <nav class="fixed top-0 left-0 right-0 z-50 nav-blur bg-white/95 dark:bg-[#161615]/95 shadow-lg border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                                                 <img src="{{ asset('7.png')}}" width="80">

                <!--    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-lg flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>-->
                    <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                        ProjexiOn
                    </span>
                </a>

                {{-- Auth Buttons --}}
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg transition-all duration-200 hover:shadow-xl">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            لوحة التحكم
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 border border-transparent hover:border-red-200 dark:hover:border-red-800">
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                تسجيل الخروج
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] transition-all duration-200">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            تسجيل الدخول
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2 rounded-lg text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] transition-all duration-200">
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            إنشاء حساب
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section - منتصف الصفحة --}}
    <main class="relative min-h-[calc(100vh-4rem)] flex flex-col">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 flex items-center justify-center py-20">
            <div class="w-full max-w-4xl mx-auto text-center animate-fade-in">
                <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-blue-100/80 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-medium mb-6 border border-blue-200/50 dark:border-blue-800/50">
                    <span class="w-2.5 h-2.5 bg-blue-500 rounded-full animate-pulse"></span>
                    منصة شاملة لمشاريع التخرج
                </div>
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold leading-tight mb-6 tracking-tight">
                    <span class="text-[#1b1b18] dark:text-[#EDEDEC]">من فكرة إلى </span>
                    <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">مشروع مكتمل</span>
                </h1>
                <p class="text-lg sm:text-xl md:text-2xl text-[#706f6c] dark:text-[#A1A09A] mb-8 max-w-3xl mx-auto leading-relaxed">
                    إدارة ذكية، تعاون فعال، وتقييم عادل. ProjexiOn يوحد الطلاب والمشرفين ولجان التقييم في منصة واحدة لتتبع التقدم، رفع الملفات، وفحص أصالة المحتوى.
                </p>
                @guest
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-10 py-4 rounded-xl text-lg font-semibold text-white bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 hover:from-blue-700 hover:via-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98]">
                        <span>ابدأ الآن</span>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-10 py-4 rounded-xl text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] border-2 border-[#19140035] dark:border-[#3E3E3A] hover:border-purple-500 dark:hover:border-purple-500 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-300">
                        إنشاء حساب جديد
                    </a>
                </div>
                @else
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-10 py-4 rounded-xl text-lg font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02]">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    الانتقال للوحة التحكم
                </a>
                @endguest
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
            {{-- المميزات الثلاثة في سطر واحد تحت الهيرو --}}
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20">
                <div class="flex items-center gap-4 p-5 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_8px_0px_rgba(0,0,0,0.06)] hover:shadow-lg hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg flex-shrink-0 group-hover:scale-105 transition-transform duration-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-[#1b1b18] dark:text-[#EDEDEC]">إدارة المشاريع</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-0.5">تنظيم وتتبع مشاريعك بسهولة</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-5 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_8px_0px_rgba(0,0,0,0.06)] hover:shadow-lg hover:border-purple-300 dark:hover:border-purple-600 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white shadow-lg flex-shrink-0 group-hover:scale-105 transition-transform duration-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-[#1b1b18] dark:text-[#EDEDEC]">التقييم والمراجعة</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-0.5">تقييم متكامل من المشرفين واللجان</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-5 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_2px_8px_0px_rgba(0,0,0,0.06)] hover:shadow-lg hover:border-emerald-300 dark:hover:border-emerald-600 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg flex-shrink-0 group-hover:scale-105 transition-transform duration-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-[#1b1b18] dark:text-[#EDEDEC]">فحص الانتحال</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-0.5">ضمان أصالة المحتوى</p>
                    </div>
                </div>
            </section>

            {{-- صورة رئيسية --}}
            <section class="mb-20">
                <div class="group relative max-w-3xl mx-auto">
                    {{-- إطار متدرج --}}
                    <div class="hero-image-frame hero-image-glow p-1 sm:p-1.5 rounded-3xl animate-[hero-image-glow_4s_ease-in-out_infinite] dark:animate-none">
                        <div class="rounded-2xl overflow-hidden bg-white dark:bg-[#161615] shadow-xl transition-all duration-500 group-hover:shadow-2xl group-hover:shadow-purple-500/10">
                            <div class="relative overflow-hidden">
                                <img src="{{ asset('1.jpg') }}" alt="ProjexiOn - منصة مشاريع التخرج" class="w-full h-auto object-cover max-h-[240px] sm:max-h-[280px] lg:max-h-[320px] transition-transform duration-700 group-hover:scale-105" loading="lazy">
                                {{-- تظليل خفيف من الأسفل مع شعار --}}
                                <div class="absolute inset-0 top-1/2 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-6 pointer-events-none">
                                    <p class="text-white/95 text-lg sm:text-xl font-bold drop-shadow-lg">من فكرة إلى مشروع مكتمل</p>
                                    <p class="text-white/80 text-sm mt-1 drop-shadow">ProjexiOn</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- زوايا زخرفية خفيفة (اختياري) --}}
                    <div class="absolute -z-10 -inset-4 bg-gradient-to-r from-blue-500/5 via-purple-500/5 to-pink-500/5 dark:from-blue-500/10 dark:via-purple-500/10 dark:to-pink-500/10 rounded-[2rem] blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
            </section>

            {{-- Features Grid --}}
            <section class="mb-20">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-center mb-10 tracking-tight">
                    <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">مميزات المنصة</span>
                </h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="group p-6 rounded-xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] hover:shadow-lg dark:hover:shadow-[inset_0px_0px_0px_1px_#fffaed2d] hover:border-blue-300 dark:hover:border-blue-700 transition-all duration-300 hover:-translate-y-1">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg mb-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">المجموعات</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">تعاون مع زملائك في مجموعات لإنجاز المشاريع</p>
                    </div>
                    <div class="group p-6 rounded-xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] hover:shadow-lg dark:hover:shadow-[inset_0px_0px_0px_1px_#fffaed2d] hover:border-purple-300 dark:hover:border-purple-700 transition-all duration-300 hover:-translate-y-1">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white shadow-lg mb-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">التقارير</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">تقارير تفصيلية وتصدير PDF للمشاريع</p>
                    </div>
                    <div class="group p-6 rounded-xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] hover:shadow-lg dark:hover:shadow-[inset_0px_0px_0px_1px_#fffaed2d] hover:border-pink-300 dark:hover:border-pink-700 transition-all duration-300 hover:-translate-y-1">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center text-white shadow-lg mb-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">الإشعارات</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">متابعة التحديثات والإشعارات الفورية</p>
                    </div>
                    <div class="group p-6 rounded-xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] hover:shadow-lg dark:hover:shadow-[inset_0px_0px_0px_1px_#fffaed2d] hover:border-amber-300 dark:hover:border-amber-700 transition-all duration-300 hover:-translate-y-1">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-lg mb-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">مكتبة المشاريع</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">استعرض ومراجع المشاريع المُنجزة</p>
                    </div>
                </div>
            </section>

            {{-- CTA Section --}}
            @guest
            <section class="p-8 sm:p-12 rounded-3xl bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-white/5"></div>
                <div class="relative z-10">
                    <h2 class="text-2xl sm:text-3xl font-bold mb-4">هل أنت مستعد لبدء رحلتك؟</h2>
                    <p class="text-lg text-white/90 mb-8 max-w-2xl mx-auto">انضم الآن وابدأ إدارة مشاريع التخرج بسهولة واحترافية</p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 rounded-xl text-base font-semibold bg-white text-purple-600 hover:bg-white/95 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            إنشاء حساب مجاني
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 rounded-xl text-base font-semibold border-2 border-white/80 text-white hover:bg-white/10 transition-all duration-300">
                            لديك حساب بالفعل؟
                        </a>
                    </div>
                </div>
            </section>
            @endguest
        </div>
    </main>

    {{-- Footer --}}
    <footer class="relative border-t border-[#e3e3e0] dark:border-[#3E3E3A] py-16 mt-16 bg-[#fafaf9] dark:bg-[#0d0d0d]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
                <div>
                    <a href="{{ url('/') }}" class="flex items-center gap-2 mb-4">
                    <img src="{{ asset('7.png')}}" width="80">

                    <!--  <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>-->
                        <span class="text-xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">ProjexiOn</span>
                    </a>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">
                        منصة شاملة لإدارة مشاريع التخرج بالتعاون بين الطلاب والمشرفين ولجان التقييم.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">روابط سريعة</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('login') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-purple-600 dark:hover:text-purple-400 transition-colors">تسجيل الدخول</a></li>
                        <li><a href="{{ route('register') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-purple-600 dark:hover:text-purple-400 transition-colors">إنشاء حساب</a></li>
                        @auth
                        <li><a href="{{ route('dashboard') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-purple-600 dark:hover:text-purple-400 transition-colors">لوحة التحكم</a></li>
                        <li><a href="{{ route('projects.index') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-purple-600 dark:hover:text-purple-400 transition-colors">المشاريع</a></li>
                        <li><a href="{{ route('groups.index') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-purple-600 dark:hover:text-purple-400 transition-colors">المجموعات</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">المميزات</h4>
                    <ul class="space-y-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        <li>إدارة المشاريع والتقدم</li>
                        <li>فحص الانتحال بالذكاء الاصطناعي</li>
                        <li>التقييم من المشرفين واللجان</li>
                        <li>مكتبة المشاريع المؤرشفة</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">للأدوار</h4>
                    <ul class="space-y-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        <li>طالب — إنشاء ومتابعة المشاريع</li>
                        <li>مشرف — إدارة المجموعات والتقييم</li>
                        <li>لجنة — تقييم المشاريع المؤرشفة</li>
                    </ul>
                </div>
            </div>
            <div class="pt-10 border-t border-[#e3e3e0] dark:border-[#3E3E3A] flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    © {{ date('Y') }} ProjexiOn. جميع الحقوق محفوظة
                </p>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    منصة ذكية لإدارة مشاريع التخرج
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
