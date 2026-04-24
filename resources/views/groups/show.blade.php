@extends('layouts.app')

@section('title', $group->name)

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- الهيدر العلوي --}}
        <div class="mb-8 animate-fade-in">
            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl p-8 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">{{ $group->name }}</h1>
                            <p class="text-purple-100 text-lg">كود المجموعة: <span class="font-mono font-bold">{{ $group->code }}</span></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('groups.members', $group) }}" class="px-4 py-2 text-sm font-semibold rounded-full bg-white/20 backdrop-blur-sm border border-white/30 hover:bg-white/30 transition-colors text-white no-underline">
                                الأعضاء
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- العمود الأيمن: المحتوى الرئيسي (الطلاب والمشاريع) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- بطاقة المشرف --}}
                @if($group->supervisor)
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-blue-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">المشرف المسؤول</h2>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold ml-4">
                            {{ substr($group->supervisor->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $group->supervisor->name }}</p>
                            <p class="text-sm text-gray-500">{{ $group->supervisor->email }}</p>
                        </div>
                    </div>
                </div>
                @endif
                {{-- بطاقة طلبات الانضمام (تظهر للمشرف فقط) --}}
@if(Auth::user()->hasRole('supervisor') && $group->supervisor_id == Auth::id())
<div class="bg-white rounded-2xl shadow-xl p-6 border border-yellow-100 mb-6">
    <h2 class="text-xl font-bold text-yellow-700 mb-4 flex items-center">
        <span class="ml-2">🔔</span> طلبات الانضمام المعلقة
    </h2>
    @if($group->pendingJoinRequests->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="text-sm text-gray-500 border-b">
                        <th class="pb-2 font-semibold">اسم الطالب</th>
                        <th class="pb-2 font-semibold">الإجراء</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($group->pendingJoinRequests as $request)
                    <tr>
                        <td class="py-3">
                            <p class="font-bold text-gray-800">{{ $request->student->name }}</p>
                            <p class="text-xs text-gray-500">{{ $request->student->email }}</p>
                        </td>
                        <td class="py-3">
                            <div class="flex gap-2">
                                {{-- زر القبول --}}
                                <form action="{{ route('groups.accept-student', [$group->id, $request->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 font-bold">
                                        موافقة
                                    </button>
                                </form>
                                {{-- زر الرفض --}}
                                <form action="{{ route('groups.reject-student', $request->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-red-100 text-red-600 text-xs rounded hover:bg-red-600 hover:text-white font-bold">
                                        رفض
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-center text-gray-400 py-4 italic">لا توجد طلبات معلقة حالياً</p>
    @endif
</div>
@endif

                {{-- بطاقة الطلاب --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-green-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center text-green-600">الطلاب في هذه المجموعة</h2>
                    @if($group->students->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($group->students as $student)
                                <div class="flex items-center p-3 bg-green-50 rounded-lg border border-green-100">
                                    <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold ml-3 text-sm">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">{{ $student->name }}</p>
                                        <p class="text-[11px] text-gray-500">{{ $student->email }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-400 py-4 italic">لا يوجد طلاب مضافين حالياً</p>
                    @endif
                </div>

                {{-- بطاقة المشاريع --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-purple-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center text-purple-600">مشاريع المجموعة</h2>
                    @if($group->projects->count() > 0)
                        <div class="space-y-3">
                            @foreach($group->projects as $project)
                                <div class="p-4 bg-purple-50 rounded-xl border border-purple-100">
                                    <h3 class="font-bold text-gray-900 mb-1">{{ $project->title }}</h3>
                                    <p class="text-sm text-gray-600 leading-relaxed">{{ Str::limit($project->description, 150) }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-400 py-4 italic">لم يتم إنشاء مشاريع لهذه المجموعة بعد</p>
                    @endif
                </div>
            </div>

            {{-- العمود الأيسر: الإجراءات والإدارة --}}
            <div class="space-y-6">
                
                {{-- 1. بطاقة إجراءات سريعة --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-indigo-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">إجراءات سريعة</h3>
                    <div class="space-y-3">
                        
                        {{-- للمشرف والإدارة: تعديل وحذف --}}
                        @if((Auth::user()->hasRole('supervisor') && $group->supervisor_id == Auth::id()) || Auth::user()->hasRole('admin'))
                            <a href="{{ route('groups.edit', $group) }}" class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-center text-sm font-bold no-underline transition-colors shadow-sm">
                                تعديل بيانات المجموعة
                            </a>
                            
                            <form action="{{ route('groups.destroy', $group) }}" method="POST" onsubmit="return confirm('⚠️ تحذير: هل أنت متأكد من حذف المجموعة نهائياً؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full mt-2 px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all text-sm font-bold border border-red-100 shadow-sm">
                                    حذف المجموعة نهائياً
                                </button>
                            </form>
                        @endif

                        {{-- للطالب: مغادرة المجموعة --}}
                        @if(Auth::user()->hasRole('student') && $group->students->contains(Auth::id()))
                            <form action="{{ route('groups.leave', $group) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من مغادرة هذه المجموعة؟')">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-orange-50 text-orange-600 rounded-lg hover:bg-orange-600 hover:text-white transition-all text-sm font-bold border border-orange-100 shadow-sm">
                                    مغادرة المجموعة
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- 2. بطاقة إضافة من الحوض (للمشرف) --}}
                @if(Auth::user()->hasRole('supervisor') && ($group->supervisor_id == Auth::id()))
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-blue-50">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">إضافة من الحوض</h3>
                    <form action="{{ route('groups.add-user', $group) }}" method="POST" class="space-y-4">
                        @csrf
                        <select name="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:bg-white transition-all">
                            <option value="">اختر طالباً...</option>
                            @foreach($myPoolStudents as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="user_type" value="student">
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 shadow-md transition-all">
                            تأكيد الإضافة للمجموعة
                        </button>
                    </form>
                </div>
                @endif

                {{-- 3. بطاقة كود المجموعة --}}
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-xl p-6 border border-blue-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-2 text-center text-blue-800">كود الانضمام</h3>
                    <div class="bg-white rounded-xl p-4 border-2 border-dashed border-blue-300 shadow-inner">
                        <p class="text-3xl font-mono font-bold text-center text-blue-600 tracking-widest">{{ $group->code }}</p>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-3 text-center italic leading-relaxed">شارك هذا الكود مع الطلاب ليتمكنوا من الانضمام لهذه المجموعة</p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection