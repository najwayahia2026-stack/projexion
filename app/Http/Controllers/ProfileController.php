<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Specialty;

class ProfileController extends Controller
{
    /**
     * عرض نموذج تعديل الملف الشخصي
     */
    public function edit()
    {
        $user = Auth::user();
        // جلب التخصصات مرتبة
        $specialties = Specialty::orderBy('name')->get();

        return view('profile.edit', compact('user', 'specialties'));
    }

    /**
     * تحديث بيانات الملف الشخصي
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // قواعد التحقق مع استثناء السجل الحالي للمستخدم لتجنب خطأ "مستخدم بالفعل"
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'student_id' => ['nullable', 'string', 'max:255', 'unique:users,student_id,' . $user->id],
            'phone' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'graduation_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'bio' => ['nullable', 'string'],
            'specialty_id' => ['nullable', 'exists:specialties,id'], // جعلناه متاحاً للتحقق بشكل عام
        ];

        // رسائل الخطأ بالعربي
        $messages = [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل لدى مستخدم آخر',
            'username.unique' => 'اسم المستخدم هذا محجوز بالفعل، اختر اسماً آخر',
            'student_id.unique' => 'رقم الطالب هذا مسجل لمستخدم آخر',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'specialty_id.exists' => 'التخصص المختار غير موجود',
        ];

        $validated = $request->validate($rules, $messages);

        // تجهيز البيانات للتحديث
        $userData = [
            'name' => $validated['name'],
            'username' => $validated['username'] ?? $user->username, // إذا كان فارغاً يحتفظ بالقديم
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ];

        // تحديث الحقول الأكاديمية فقط إذا لم يكن المستخدم أدمن (حسب واجهة الـ Blade)
        if (!$user->hasRole('admin') && !$user->hasRole('department_admin')) {
            $userData['department'] = $validated['department'] ?? null;
            $userData['specialty_id'] = $validated['specialty_id'] ?? null;
            $userData['graduation_year'] = $validated['graduation_year'] ?? null;
            $userData['student_id'] = $validated['student_id'] ?? null;
        }

        // تحديث كلمة المرور فقط إذا تم إدخالها
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        // تنفيذ التحديث
        $user->update($userData);

        return redirect()->route('profile.edit')
            ->with('success', 'تم تحديث ملفك الشخصي بنجاح');
    }
}