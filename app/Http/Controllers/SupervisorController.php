<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupervisorController extends Controller
{
    // 1. عرض الطلاب المتاحين (نفس التخصص + غير محجوزين)
    public function getAvailableStudents()
    {
        $supervisor = Auth::user();

        $students = User::where('role', 'student')
            ->where('specialty_id', $supervisor->specialty_id) // نفس التخصص
            ->whereNull('supervisor_id') // غير محجوز لمشرف آخر
            ->get();

        return view('supervisor.add_students', compact('students'));
    }

    // 2. عملية "حجز" الطالب للمشرف
    public function assignStudent($studentId)
    {
        $student = User::findOrFail($studentId);
        
        // التحقق من العدد (مثلاً 15 طالب كحد أقصى)
        $currentCount = User::where('supervisor_id', Auth::id())->count();
        if ($currentCount >= 15) {
            return back()->with('error', 'لقد وصلت للحد الأقصى من الطلاب (15 طالباً)');
        }

        $student->update([
            'supervisor_id' => Auth::id()
        ]);

        return back()->with('success', 'تم إضافة الطالب لمجموعتك الخاصة بنجاح');
    }

    // 3. حذف الطالب من مجموعة المشرف (ليصبح متاحاً للآخرين)
    public function removeStudent($studentId)
    {
        $student = User::where('id', $studentId)
                       ->where('supervisor_id', Auth::id())
                       ->firstOrFail();

        $student->update([
            'supervisor_id' => null
        ]);

        return back()->with('success', 'تم حذف الطالب وأصبح متاحاً للمشرفين الآخرين');
    }
}