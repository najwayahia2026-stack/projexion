@extends('layouts.app')

@section('title', 'أعضاء المجموعة - ' . $group->name)

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-10 rounded-full -ml-32 -mb-32"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">{{ $group->name }}</h1>
                            <p class="text-purple-100 text-lg">أعضاء المجموعة</p>
                        </div>
                        <a href="{{ route('groups.show', $group) }}" class="px-4 py-2 text-sm font-semibold rounded-full bg-white/20 backdrop-blur-sm border border-white/30 hover:bg-white/30 transition-colors">
                            العودة للمجموعة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6 animate-fade-in">
            <form method="GET" action="{{ route('groups.members', $group) }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Search Input -->
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search ?? '' }}"
                            placeholder="ابحث بالاسم، البريد الإلكتروني، أو رقم الطالب..." 
                            class="w-full pr-10 pl-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all"
                        >
                    </div>

                    <!-- Role Filter -->
                    <div class="relative">
                        <select 
                            name="role" 
                            class="w-full pl-4 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white appearance-none cursor-pointer transition-all"
                        >
                            <option value="">جميع الأدوار</option>
                            <option value="supervisor" {{ ($roleFilter ?? '') === 'supervisor' ? 'selected' : '' }}>مشرف</option>
                            <option value="student" {{ ($roleFilter ?? '') === 'student' ? 'selected' : '' }}>طالب</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-900 dark:text-gray-200">
                        <span class="font-semibold">{{ $members->count() }}</span> عضو
                    </div>
                    <div class="flex space-x-2 space-x-reverse">
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105"
                        >
                            بحث
                        </button>
                        @if($search || ($roleFilter ?? ''))
                        <a 
                            href="{{ route('groups.members', $group) }}" 
                            class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-all"
                        >
                            إعادة تعيين
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Members Grid -->
        @if($members->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($members as $member)
            @php
                $role = $group->getUserRoleInGroup($member);
                $roleColors = [
                    'supervisor' => ['border' => 'border-purple-500', 'bg' => 'from-purple-500 to-indigo-600', 'text' => 'text-purple-600'],
                    'student' => ['border' => 'border-green-500', 'bg' => 'from-green-500 to-emerald-600', 'text' => 'text-green-600'],
                ];
                $colors = $roleColors[$role] ?? $roleColors['student'];
                $roleNames = [
                    'supervisor' => 'مشرف',
                    'student' => 'طالب',
                ];
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-2 {{ $colors['border'] }} transform hover:scale-105 transition-all duration-300 animate-fade-in">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br {{ $colors['bg'] }} rounded-full flex items-center justify-center text-white text-2xl font-bold ml-4">
                        {{ substr($member->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $member->name }}</h3>
                        <p class="{{ $colors['text'] }} font-semibold">{{ $roleNames[$role] ?? 'عضو' }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm text-gray-900 dark:text-gray-200">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                        {{ $member->email }}
                    </div>
                    @if($member->student_id)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                        رقم الطالب: {{ $member->student_id }}
                    </div>
                    @endif
                    @if($member->department)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        {{ $member->department }}
                    </div>
                    @endif
                </div>
                @if(auth()->user()->hasRole('supervisor') && ($group->supervisor_id === auth()->id() || $group->isManager(auth()->user())))
                @if($member->id !== $group->supervisor_id)
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
                    @if($role === 'student')
                    <a 
                        href="{{ route('users.show', $member) }}"
                        class="w-full px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all text-sm font-semibold shadow-md hover:shadow-lg flex items-center justify-center"
                    >
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        عرض الطالب
                    </a>
                    @endif
                    <form 
                        action="{{ route('groups.remove-member', [$group, $member]) }}" 
                        method="POST" 
                        onsubmit="return confirm('هل أنت متأكد من حذف {{ $member->name }} من المجموعة؟')"
                        class="w-full"
                    >
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit"
                            class="w-full px-4 py-2 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-lg hover:from-red-600 hover:to-rose-700 transition-all text-sm font-semibold"
                        >
                            حذف العضو
                        </button>
                    </form>
                </div>
                @endif
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center animate-fade-in">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="text-gray-900 dark:text-gray-200 text-lg">
                @if($search || ($roleFilter ?? ''))
                    لم يتم العثور على أعضاء يطابقون معايير البحث
                @else
                    لا يوجد أعضاء في هذه المجموعة
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

@endsection
