@extends('layouts.app')

@section('title', 'الإشعارات')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-2xl shadow-2xl p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-10 rounded-full -ml-32 -mb-32"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold mb-3">الإشعارات</h1>
                            <p class="text-purple-100 text-lg">عرض جميع إشعاراتك</p>
                        </div>
                        <div class="hidden lg:block">
                            <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Bar -->
        <div class="mb-6 bg-white dark:bg-[#161615] rounded-xl shadow-lg p-4 border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('department_admin') || Auth::user()->hasRole('supervisor'))
                    <button onclick="document.getElementById('sendModal').classList.remove('hidden')" 
                            class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 font-semibold text-sm flex items-center gap-2 shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        إرسال إشعار جديد
                    </button>
                    @endif
                    @if($notifications->where('read', false)->count() > 0)
                    <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-semibold text-sm flex items-center gap-2 shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            تم قراءة الجميع
                        </button>
                    </form>
                    @endif
                </div>
                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A] font-medium">
                    <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $notifications->where('read', false)->count() }}</span>
                    إشعار غير مقروء من أصل
                    <span class="font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $notifications->total() }}</span>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        @if($notifications->count() > 0)
            <div class="space-y-3">
                @foreach($notifications as $notification)
                    <div class="bg-white dark:bg-[#161615] rounded-xl shadow-md p-5 border border-[#e3e3e0] dark:border-[#3E3E3A] hover:shadow-xl transition-all duration-200 {{ !$notification->read ? 'border-r-4 border-r-blue-500 bg-gradient-to-l from-blue-50/50 to-transparent dark:from-blue-900/20 dark:to-transparent' : '' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start gap-3">
                                    @if(!$notification->read)
                                        <div class="mt-2 w-3 h-3 bg-blue-500 rounded-full flex-shrink-0 animate-pulse"></div>
                                    @else
                                        <div class="mt-2 w-3 h-3 bg-gray-300 dark:bg-gray-600 rounded-full flex-shrink-0"></div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-3 mb-2">
                                            <h3 class="text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC] leading-tight {{ !$notification->read ? 'text-blue-900 dark:text-blue-100' : '' }}">
                                                {{ $notification->title }}
                                            </h3>
                                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A] font-medium whitespace-nowrap flex-shrink-0">
                                                {{ $notification->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                        <p class="text-base text-[#1b1b18] dark:text-[#EDEDEC] mb-3 leading-relaxed font-medium">
                                            {{ $notification->message }}
                                        </p>
                                        @if($notification->data && isset($notification->data['attachment']))
                                        <div class="mb-3 p-3 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-lg border border-indigo-200 dark:border-indigo-800">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-indigo-900 dark:text-indigo-200 mb-1">ملف مرفق</p>
                                                    <a href="{{ route('notifications.attachment-download', $notification) }}" 
                                                       class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 underline flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        {{ $notification->data['attachment']['file_name'] }}
                                                        <span class="text-xs text-gray-500">({{ number_format($notification->data['attachment']['file_size'] / 1024, 2) }} KB)</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="flex items-center gap-3 text-sm">
                                            <span class="text-[#706f6c] dark:text-[#A1A09A] font-medium">
                                                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                            @if($notification->type !== 'custom')
                                                <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300">
                                                    {{ $notification->type }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-start gap-2 flex-shrink-0">
                                @if(!$notification->read)
                                    <form action="{{ route('notifications.read', $notification) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 text-sm font-semibold shadow-sm hover:shadow-md flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            تم القراءة
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الإشعار؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all duration-200 text-sm font-semibold shadow-sm hover:shadow-md flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-[#161615] rounded-xl shadow-lg p-16 text-center border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">لا توجد إشعارات</h3>
                <p class="text-base text-[#706f6c] dark:text-[#A1A09A] font-medium">لم تتلق أي إشعارات حتى الآن</p>
            </div>
        @endif
    </div>
</div>

<!-- Send Notification Modal -->
@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('department_admin') || Auth::user()->hasRole('supervisor'))
<div id="sendModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4" onclick="if(event.target === this) closeSendModal()">
    <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-2xl max-w-2xl w-full p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] relative z-[10000]" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">إرسال إشعار</h3>
            <button type="button" onclick="closeSendModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
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
        
        <form action="{{ route('notifications.send') }}" method="POST" id="sendNotificationForm" onsubmit="return true;">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">المستلم <span class="text-red-500">*</span></label>
                <select name="recipient_type" id="recipient_type" required onchange="toggleRecipientFields()"
                    class="block w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-[#2a2a28] text-[#1b1b18] dark:text-[#EDEDEC] {{ $errors->has('recipient_type') ? 'border-red-500' : '' }}">
                    <option value="all" {{ old('recipient_type') == 'all' ? 'selected' : '' }}>جميع المستخدمين</option>
                    <option value="specific" {{ old('recipient_type') == 'specific' ? 'selected' : '' }}>مستخدم محدد</option>
                    <option value="role" {{ old('recipient_type') == 'role' ? 'selected' : '' }}>حسب الدور</option>
                </select>
                @error('recipient_type')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div id="specificUserField" class="mb-4 {{ old('recipient_type') == 'specific' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">اختر المستخدم</label>
                <select name="recipient_id" id="recipient_id"
                    class="block w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-900 bg-white dark:bg-white {{ $errors->has('recipient_id') ? 'border-red-500' : '' }}">
                    <option value="">-- اختر المستخدم --</option>
                    @foreach(\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}" {{ old('recipient_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('recipient_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div id="roleField" class="mb-4 {{ old('recipient_type') == 'role' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">اختر الدور</label>
                <select name="role" id="role"
                    class="block w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-900 bg-white dark:bg-white {{ $errors->has('role') ? 'border-red-500' : '' }}">
                    <option value="">-- اختر الدور --</option>
                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>طالب</option>
                    <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>مشرف</option>
                    <option value="committee" {{ old('role') == 'committee' ? 'selected' : '' }}>لجنة التقييم</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>مدير النظام</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">العنوان <span class="text-red-500">*</span></label>
                <input type="text" name="title" required value="{{ old('title') }}"
                    class="block w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-900 bg-white dark:bg-white placeholder:text-gray-400 {{ $errors->has('title') ? 'border-red-500' : '' }}"
                    placeholder="عنوان الإشعار">
                @error('title')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">الرسالة <span class="text-red-500">*</span></label>
                <textarea name="message" rows="4" required
                    class="block w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-900 bg-white dark:bg-white placeholder:text-gray-400 {{ $errors->has('message') ? 'border-red-500' : '' }}"
                    placeholder="محتوى الإشعار">{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeSendModal()" 
                    class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-[#3E3E3A] rounded-lg hover:bg-gray-200 dark:hover:bg-[#4E4E4A] transition-all duration-200">
                    إلغاء
                </button>
                <button type="submit" id="sendNotificationButton"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="sendButtonText">إرسال</span>
                    <span id="sendButtonLoader" class="hidden inline-block">
                        <svg class="animate-spin h-4 w-4 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
// Open modal if there are validation errors
@if($errors->any() || session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('sendModal');
        if (modal) {
            modal.classList.remove('hidden');
            // Restore form fields visibility based on old recipient_type
            const recipientType = document.getElementById('recipient_type').value;
            toggleRecipientFields();
        }
    });
@endif

function closeSendModal() {
    document.getElementById('sendModal').classList.add('hidden');
    const form = document.getElementById('sendNotificationForm');
    if (form) {
        form.reset();
    }
    document.getElementById('specificUserField').classList.add('hidden');
    document.getElementById('roleField').classList.add('hidden');
    resetNotificationButton();
}

function toggleRecipientFields() {
    const type = document.getElementById('recipient_type').value;
    const specificField = document.getElementById('specificUserField');
    const roleField = document.getElementById('roleField');
    
    if (type === 'specific') {
        specificField.classList.remove('hidden');
        roleField.classList.add('hidden');
        // Make recipient_id required when specific is selected
        const recipientId = document.getElementById('recipient_id');
        if (recipientId) {
            recipientId.setAttribute('required', 'required');
        }
        const role = document.getElementById('role');
        if (role) {
            role.removeAttribute('required');
        }
    } else if (type === 'role') {
        specificField.classList.add('hidden');
        roleField.classList.remove('hidden');
        // Make role required when role is selected
        const role = document.getElementById('role');
        if (role) {
            role.setAttribute('required', 'required');
        }
        const recipientId = document.getElementById('recipient_id');
        if (recipientId) {
            recipientId.removeAttribute('required');
        }
    } else {
        specificField.classList.add('hidden');
        roleField.classList.add('hidden');
        // Remove required attributes
        const recipientId = document.getElementById('recipient_id');
        const role = document.getElementById('role');
        if (recipientId) recipientId.removeAttribute('required');
        if (role) role.removeAttribute('required');
    }
}

// Handle form submission - simplified version
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('sendNotificationForm');
    const button = document.getElementById('sendNotificationButton');
    const buttonText = document.getElementById('sendButtonText');
    const buttonLoader = document.getElementById('sendButtonLoader');
    
    if (form && button && buttonText && buttonLoader) {
        // Reset button state on page load
        resetNotificationButton();
        
        // Handle form submission
        form.addEventListener('submit', function() {
            // Show loading state
            button.disabled = true;
            buttonText.classList.add('hidden');
            buttonLoader.classList.remove('hidden');
        });
    }
});

function resetNotificationButton() {
    const button = document.getElementById('sendNotificationButton');
    const buttonText = document.getElementById('sendButtonText');
    const buttonLoader = document.getElementById('sendButtonLoader');
    
    if (button && buttonText && buttonLoader) {
        button.disabled = false;
        buttonText.classList.remove('hidden');
        buttonLoader.classList.add('hidden');
    }
}
</script>
@endsection

