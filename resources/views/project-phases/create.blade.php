@extends('layouts.app')

@section('title', 'إضافة جزء جديد')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            <h1 class="text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                إضافة جزء جديد
            </h1>
            <p class="text-[#706f6c] dark:text-[#A1A09A]">مشروع: {{ $project->title }}</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-[#161615] rounded-xl shadow-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-8 lg:p-12 animate-fade-in" style="animation-delay: 0.1s">

            <form action="{{ route('project-phases.store', $project) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            عنوان الجزء *
                        </label>
                        <input type="text" name="title" id="title" required
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               placeholder="مثال: الفصل الأول - المقدمة" value="{{ old('title') }}">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            وصف الجزء
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                  placeholder="وصف مختصر للجزء...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label for="percentage" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            نسبة هذا الجزء من المشروع (%) *
                        </label>
                        <input type="number" name="percentage" id="percentage" required min="0" max="{{ $remainingPercentage }}" step="0.01"
                               class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               placeholder="مثال: 25" value="{{ old('percentage') }}"
                               data-remaining="{{ $remainingPercentage }}">
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-2">
                            النسبة الحالية للمشروع: <span class="font-semibold">{{ number_format($currentTotalPercentage, 1) }}%</span> —
                            المتبقي للإضافة: <span class="font-bold text-blue-600 dark:text-blue-400">{{ number_format($remainingPercentage, 1) }}%</span>
                        </p>
                        <p id="percentageError" class="text-sm text-red-600 dark:text-red-400 mt-1 hidden">تجاوزت النسبة المتبقية. الحد الأقصى المسموح: <span id="maxAllowed"></span>%</p>
                    </div>

                    <div>
                        <label for="file" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            ملف الجزء (Word أو PDF) *
                        </label>
                        <div id="file-upload-area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg hover:border-blue-400 dark:hover:border-blue-600 transition-colors">
                            <div class="space-y-1 text-center w-full">
                                <svg id="file-icon" class="mx-auto h-12 w-12 text-[#706f6c] dark:text-[#A1A09A]" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div id="file-upload-text" class="flex text-sm text-[#706f6c] dark:text-[#A1A09A] justify-center">
                                    <label for="file" class="relative cursor-pointer bg-white dark:bg-[#161615] rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>اختر ملف</span>
                                        <input type="file" name="file" id="file" required accept=".doc,.docx,.pdf" class="sr-only">
                                    </label>
                                    <p class="mr-1">أو اسحب الملف هنا</p>
                                </div>
                                <p id="file-info" class="text-xs text-[#706f6c] dark:text-[#A1A09A]">DOC, DOCX, PDF حتى 10MB</p>
                                <div id="selected-file" class="hidden mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div class="text-right">
                                                <p id="file-name" class="text-sm font-semibold text-green-800 dark:text-green-200"></p>
                                                <p id="file-size" class="text-xs text-green-600 dark:text-green-400"></p>
                                            </div>
                                        </div>
                                        <button type="button" id="remove-file" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-red-600 dark:text-red-400 mt-2 flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            سيتم فحص الملف تلقائياً للتأكد من عدم وجود تشابه مع ملفات الطلاب الآخرين
                        </p>
                    </div>

                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border-r-4 border-yellow-400 dark:border-yellow-600 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 ml-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-1">ملاحظة مهمة</p>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300">إذا كانت نسبة التشابه مع ملفات الطلاب الآخرين أكثر من 50%، سيتم رفض الجزء تلقائياً.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <a href="{{ route('project-phases.index', $project) }}" class="px-6 py-3 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg text-sm font-semibold transition-all duration-200">
                        إلغاء
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 text-sm font-semibold">
                        رفع الجزء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation: percentage must not exceed remaining (total with other phases <= 100%)
    const percentageInput = document.getElementById('percentage');
    const percentageError = document.getElementById('percentageError');
    const maxAllowedSpan = document.getElementById('maxAllowed');
    const phaseForm = percentageInput ? percentageInput.closest('form') : null;

    if (percentageInput && phaseForm) {
        const maxRemaining = parseFloat(percentageInput.getAttribute('data-remaining') || 100);

        function validatePercentage() {
            const value = parseFloat(percentageInput.value) || 0;
            if (value > maxRemaining) {
                percentageError.classList.remove('hidden');
                maxAllowedSpan.textContent = maxRemaining;
                return false;
            }
            percentageError.classList.add('hidden');
            return true;
        }

        percentageInput.addEventListener('input', validatePercentage);
        percentageInput.addEventListener('change', validatePercentage);

        phaseForm.addEventListener('submit', function(e) {
            if (!validatePercentage()) {
                e.preventDefault();
                percentageInput.focus();
                return false;
            }
        });
    }

    const fileInput = document.getElementById('file');
    const fileUploadArea = document.getElementById('file-upload-area');
    const fileUploadText = document.getElementById('file-upload-text');
    const fileInfo = document.getElementById('file-info');
    const fileIcon = document.getElementById('file-icon');
    const selectedFileDiv = document.getElementById('selected-file');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const removeFileBtn = document.getElementById('remove-file');

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            const allowedTypes = ['.doc', '.docx', '.pdf'];
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
            
            if (!allowedTypes.includes(fileExtension)) {
                alert('نوع الملف غير مدعوم. يرجى اختيار ملف DOC, DOCX, أو PDF');
                fileInput.value = '';
                return;
            }

            // Validate file size (10MB)
            const maxSize = 10 * 1024 * 1024; // 10MB in bytes
            if (file.size > maxSize) {
                alert('حجم الملف كبير جداً. الحد الأقصى هو 10MB');
                fileInput.value = '';
                return;
            }

            // Display selected file
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            
            // Hide upload text and show selected file
            fileUploadText.classList.add('hidden');
            fileInfo.classList.add('hidden');
            fileIcon.classList.add('hidden');
            selectedFileDiv.classList.remove('hidden');
            
            // Change border color
            fileUploadArea.classList.remove('border-[#e3e3e0]', 'dark:border-[#3E3E3A]');
            fileUploadArea.classList.add('border-green-400', 'dark:border-green-600', 'bg-green-50', 'dark:bg-green-900/10');
        }
    });

    // Handle remove file
    removeFileBtn.addEventListener('click', function() {
        fileInput.value = '';
        selectedFileDiv.classList.add('hidden');
        fileUploadText.classList.remove('hidden');
        fileInfo.classList.remove('hidden');
        fileIcon.classList.remove('hidden');
        
        // Reset border color
        fileUploadArea.classList.remove('border-green-400', 'dark:border-green-600', 'bg-green-50', 'dark:bg-green-900/10');
        fileUploadArea.classList.add('border-[#e3e3e0]', 'dark:border-[#3E3E3A]');
    });

    // Handle drag and drop
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        fileUploadArea.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/10');
    });

    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        if (!selectedFileDiv.classList.contains('hidden')) {
            fileUploadArea.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/10');
            fileUploadArea.classList.add('border-green-400', 'dark:border-green-600', 'bg-green-50', 'dark:bg-green-900/10');
        } else {
            fileUploadArea.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/10');
        }
    });

    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
});
</script>
@endsection
