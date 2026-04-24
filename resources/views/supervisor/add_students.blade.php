@extends('layouts.app') {{-- تأكد أن هذا هو اسم ملف التنسيق الأساسي عندك --}}

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">قائمة الطلاب المتاحين لتخصص: {{ auth()->user()->specialty->name ?? 'تخصصك' }}</h4>
            <span class="badge bg-light text-dark">الحد الأقصى: 15 طالباً</span>
        </div>
        <div class="card-body">
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover border">
                    <thead class="table-light">
                        <tr>
                            <th>الرقم الجامعي</th>
                            <th>اسم الطالب</th>
                            <th>التخصص</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->specialty->name ?? 'غير محدد' }}</td>
                                <td>
                                    <form action="{{ route('supervisor.students.assign', $student->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-plus"></i> إضافة لمجموعتي
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    لا يوجد طلاب متاحون حالياً في تخصصك (قد يكونون محجوزين لمشرفين آخرين).
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection