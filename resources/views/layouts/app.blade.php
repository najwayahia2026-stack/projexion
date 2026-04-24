<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ProjexiOn') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen">
    <div class="min-h-screen">
        <!-- Navigation -->
        @auth
        <nav class="bg-white/95 dark:bg-[#161615]/95 backdrop-blur-md shadow-lg border-b border-[#e3e3e0] dark:border-[#3E3E3A] sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                             <img src="{{ asset('7.png')}}" width="80">
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 space-x-reverse group">
                             <!--   <div class="w-10 h-10 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-lg flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                </div>-->
                                <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                                    ProjexiOn
                                </span>
                            </a>
                        </div>
                        <div class="hidden sm:ml-8 sm:flex sm:space-x-3 space-x-reverse">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f5f5f3] dark:hover:bg-[#2a2a28]' }}">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                لوحة التحكم
                            </a>
                            @if(!Auth::user()->hasRole('committee'))
                            <a href="{{ route('groups.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('groups.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f5f5f3] dark:hover:bg-[#2a2a28]' }}">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                المجموعات
                            </a>
                            @endif
                            <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('projects.index') || (request()->routeIs('projects.*') && !request()->routeIs('projects.library')) ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f5f5f3] dark:hover:bg-[#2a2a28]' }}">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                المشاريع
                            </a>
                            @if(Auth::user()->hasRole('student'))
                            <a href="{{ route('projects.library') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('projects.library') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f5f5f3] dark:hover:bg-[#2a2a28]' }}">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                مكتبة المشاريع
                            </a>
                            @endif
                            @if(!Auth::user()->hasRole('student'))
                            <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-gradient-to-r from-teal-600 to-cyan-600 text-white shadow-lg' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f5f5f3] dark:hover:bg-[#2a2a28]' }}">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                التقارير
                            </a>
                            @endif
                            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('department_admin'))
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f5f5f3] dark:hover:bg-[#2a2a28]' }}">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                المستخدمين
                            </a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f5f5f3] dark:hover:bg-[#2a2a28]' }}">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                الملف الشخصي
                            </a>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <div class="ml-3 relative">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ Auth::user()->email }}</div>
                                </div>
                                @php
                                    $userRoleKey = Auth::user()->getRoleNames()->first() ?? 'User';
                                    $roleTranslations = [
                                        'student' => 'طالب',
                                        'supervisor' => 'مشرف',
                                        'committee' => 'لجنة التقييم',
                                        'admin' => 'مدير النظام',
                                        'department_admin' => 'إدارة القسم',
                                    ];
                                    $userRoleName = $roleTranslations[$userRoleKey] ?? $userRoleKey;
                                @endphp
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 text-blue-800 dark:text-blue-200 border border-blue-200 dark:border-blue-800">
                                    {{ $userRoleName }}
                                </span>
                                <!-- Notifications Icon -->
                                <a href="{{ route('notifications.index') }}" class="relative p-2 text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f5f5f3] dark:hover:bg-[#2a2a28] rounded-lg transition-all duration-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <span id="notificationBadge" class="absolute top-0 right-0 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full" style="display: none;"></span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 border border-transparent hover:border-red-200 dark:hover:border-red-800">
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        @endauth

        <!-- Page Content -->
        <main class="animate-fade-in">
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-4 rounded-sm shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] animate-slide-in">
                        <div class="flex items-center">
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-4 rounded-sm shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] animate-slide-in">
                        <div class="flex items-center">
                            <p class="text-[#f53003] dark:text-[#FF4433] font-medium text-sm">{{ session('error') }}</p>
                        </div>
                        @if(session('error_details'))
                            <div class="mt-2 pt-2 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ session('error_details') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 p-4 rounded-sm shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] animate-slide-in">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-yellow-800 dark:text-yellow-200 font-medium text-sm">{{ session('warning') }}</p>
                                @if(session('error_details'))
                                    <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">{{ session('error_details') }}</p>
                                @endif
                                @if(session('help'))
                                    <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1 font-mono bg-yellow-100 dark:bg-yellow-900/40 p-2 rounded mt-2">{{ session('help') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-4 rounded-sm shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] animate-slide-in">
                        <div class="flex items-start">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">يرجى تصحيح الأخطاء التالية:</h3>
                                <ul class="list-disc list-inside text-sm text-[#706f6c] dark:text-[#A1A09A] space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
</div>

<!-- Notifications JavaScript -->
<script>
let notificationCheckInterval;

function updateNotificationBadge() {
    fetch('{{ route("notifications.unread-count") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notificationBadge');
            if (!badge) return;
            const count = typeof data.count === 'number' ? data.count : 0;
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = '';
            } else {
                badge.textContent = '';
                badge.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error updating notification badge:', error);
            const badge = document.getElementById('notificationBadge');
            if (badge) { badge.textContent = ''; badge.style.display = 'none'; }
        });
}

function loadNotifications() {
    fetch('{{ route("notifications.list") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('notificationsList');
            if (data.notifications && data.notifications.length > 0) {
                container.innerHTML = data.notifications.map(notif => `
                    <div class="p-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A] hover:bg-gray-50 dark:hover:bg-[#1f1f1e] transition-colors ${!notif.read ? 'bg-blue-50/50 dark:bg-blue-900/20' : ''}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] ${!notif.read ? 'font-bold' : ''}">${notif.title}</h4>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">${notif.message}</p>
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-2 block">${notif.created_at}</span>
                            </div>
                            ${!notif.read ? `
                                <button onclick="markNotificationAsRead(${notif.id})" class="mr-2 text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = '<div class="p-4 text-center text-gray-500 dark:text-gray-400">لا توجد إشعارات جديدة</div>';
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            document.getElementById('notificationsList').innerHTML = '<div class="p-4 text-center text-red-500">حدث خطأ في تحميل الإشعارات</div>';
        });
}

function markNotificationAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateNotificationBadge();
            loadNotifications();
        }
    })
    .catch(error => console.error('Error marking notification as read:', error));
}

function markAllAsRead() {
    fetch('{{ route("notifications.read-all") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateNotificationBadge();
            loadNotifications();
        }
    })
    .catch(error => console.error('Error marking all as read:', error));
}

function toggleNotifications() {
    const dropdown = document.getElementById('notificationsDropdownContent');
    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        loadNotifications();
    } else {
        dropdown.classList.add('hidden');
    }
}

function closeNotifications() {
    document.getElementById('notificationsDropdownContent').classList.add('hidden');
}

// Close on outside click
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notificationsDropdown');
    const content = document.getElementById('notificationsDropdownContent');
    if (dropdown && content && !dropdown.contains(event.target)) {
        content.classList.add('hidden');
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateNotificationBadge();
    
    // Update every 30 seconds
    notificationCheckInterval = setInterval(() => {
        updateNotificationBadge();
        // Reload notifications if dropdown is open
        const dropdown = document.getElementById('notificationsDropdownContent');
        if (dropdown && !dropdown.classList.contains('hidden')) {
            loadNotifications();
        }
    }, 30000);
});

</script>

</body>
</html>
