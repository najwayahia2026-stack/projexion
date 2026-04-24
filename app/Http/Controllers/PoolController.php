<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PoolController extends Controller
{
    /**
     * عرض صفحة الحوض (الطلاب المتاحين + حوضي الخاص)
     */
  public function index()
{
    $user = auth()->user();

    // 1. جلب تخصص المشرف الحالي
    $mySpecialtyId = $user->specialty_id;

    // 2. تحديد الطلاب المتاحين مع فلترة صارمة
    if ($user->hasRole('admin')) {
        // الأدمن يرى الجميع (بدون مشرف)
        $availableStudents = User::role('student')
            ->whereNull('supervisor_id')
            ->get();
    } else {
        // المشرف يرى فقط طلاب تخصصه (بدون مشرف)
        $availableStudents = User::role('student')
            ->where('specialty_id', $mySpecialtyId) // الفلتر الأساسي
            ->whereNull('supervisor_id')
            ->get();
    }

    // 3. جلب طلاب الحوض الخاص بالمشرف
    $myPool = User::where('supervisor_id', $user->id)->get();

    return view('pool.index', compact('availableStudents', 'myPool'));
}
    /**
     * إضافة طالب إلى الحوض الخاص بالمشرف
     */
    public function addToPool(User $user)
    {
        $supervisor = Auth::user();

        // تأمين: لا يمكن إضافة طالب من تخصص مختلف
        if ($user->specialty_id !== $supervisor->specialty_id) {
            return back()->with('error', 'لا يمكنك حجز طالب من تخصص مختلف.');
        }

        // تأمين: حد أقصى 15 طالب
        $currentCount = User::where('supervisor_id', $supervisor->id)->count();
        if ($currentCount >= 15) {
            return back()->with('error', 'لقد وصلت للحد الأقصى (15 طالب) في حوضك.');
        }

        // تحديث بيانات الطالب ليرتبط بهذا المشرف
        $user->update([
            'supervisor_id' => $supervisor->id
        ]);

        return back()->with('success', "تم إضافة الطالب {$user->name} إلى حوضك بنجاح.");
    }

    /**
     * إزالة طالب من الحوض وإعادته للقائمة العامة
     */
    public function removeFromPool(User $user)
    {
        // التأكد أن المشرف يحذف طالبه هو فقط
        if ($user->supervisor_id !== Auth::id()) {
            return back()->with('error', 'غير مسموح لك بهذا الإجراء.');
        }

        $user->update([
            'supervisor_id' => null
        ]);

        return back()->with('success', 'تم إرجاع الطالب للقائمة العامة بنجاح.');
    }
}