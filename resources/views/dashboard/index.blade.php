@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header - متناسق مع شعار الموقع -->
        <div class="mb-6 sm:mb-8 animate-fade-in">
            <div class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-2xl shadow-xl overflow-hidden border border-white/10">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-80"></div>
                <div class="relative z-10 flex flex-wrap items-center justify-between gap-4 p-6 sm:p-8 lg:p-10">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/20">
                            <span class="text-3xl sm:text-4xl">👋</span>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white">مرحباً، {{ $user->name }}</h1>
                            <p class="text-white/90 text-sm sm:text-base mt-1">نتمنى لك يوم عمل مثمر</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Content - العناصر في صفوف -->
        <div class="animate-fade-in" style="animation-delay: 0.15s">
            @if($user->hasRole('admin') || $user->hasRole('department_admin'))
                @include('dashboard.admin')
            @elseif($user->hasRole('student'))
                @include('dashboard.student')
            @elseif($user->hasRole('supervisor'))
                @include('dashboard.supervisor')
            @elseif($user->hasRole('committee'))
                @include('dashboard.committee')
            @endif
        </div>
    </div>
</div>
@endsection
