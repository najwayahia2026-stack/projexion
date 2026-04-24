<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
public function login(Request $request)
{
    // 1. التحقق من البيانات المدخلة
    $validated = $request->validate([
        'login'    => ['required', 'string'], // قد يكون إيميل أو اسم مستخدم
        'password' => ['required', 'string'],
    ], [
        'login.required'    => 'البريد الإلكتروني أو اسم المستخدم مطلوب',
        'password.required' => 'كلمة المرور مطلوبة',
    ]);

    $login = $validated['login'];
    $password = $validated['password'];

    // 2. تحديد نوع الحقل (هل هو إيميل أم اسم مستخدم؟)
    $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    // 3. محاولة تسجيل الدخول
    $credentials = [$fieldType => $login, 'password' => $password];

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $user = Auth::user();

        // منع دخول الأدمن من هذه الصفحة إذا كان لديك صفحة خاصة للأدمن
        if ($user->hasRole('admin')) {
            Auth::logout();
            return back()->withErrors(['login' => 'حساب الإدارة يدخل من صفحته المخصصة فقط.']);
        }

        // منع المستخدمين المحظورين
        if (method_exists($user, 'isBanned') && $user->isBanned()) {
            Auth::logout();
            return back()->withErrors(['login' => 'هذا الحساب محظور حالياً.']);
        }

        $request->session()->regenerate();
        return redirect()->intended('/dashboard')->with('success', 'تم تسجيل الدخول بنجاح.');
    }

    // 4. فشل تسجيل الدخول
    return back()->withErrors([
        'login' => 'بيانات الدخول غير صحيحة، يرجى التأكد والمحاولة مرة أخرى.',
    ])->onlyInput('login');
}/**
 * تسجيل دخول الإدارة (Admin Login)
 */
public function adminLogin(Request $request)
{
    // 1. التحقق من البيانات
    $validated = $request->validate([
        'login'    => ['required', 'string'],
        'password' => ['required', 'string'],
    ], [
        'login.required'    => 'البريد الإلكتروني أو اسم المستخدم مطلوب',
        'password.required' => 'كلمة المرور مطلوبة',
    ]);

    $login = $validated['login'];
    $password = $validated['password'];

    // 2. تحديد نوع الحقل
    $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    $credentials = [$fieldType => $login, 'password' => $password];

    // 3. محاولة تسجيل الدخول
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $user = Auth::user();

        // تأكد أن المستخدم لديه صلاحية "admin" فعلاً
        if (!$user->hasRole('admin')) {
            Auth::logout();
            return back()->withErrors([
                'login' => 'عذراً، ليس لديك صلاحية الوصول كمسؤول.',
            ])->onlyInput('login');
        }

        // منع المحظورين
        if (method_exists($user, 'isBanned') && $user->isBanned()) {
            Auth::logout();
            return back()->withErrors(['login' => 'هذا الحساب محظور.']);
        }

        $request->session()->regenerate();
        return redirect()->intended('/dashboard')->with('success', 'مرحباً بك أيها المسؤول! تم الدخول بنجاح.');
    }

    // 4. فشل الدخول
    return back()->withErrors([
        'login' => 'بيانات الدخول غير صحيحة.',
    ])->onlyInput('login');
}
    /**
     * Show the admin-only login form (الإدارة).
     * Admins must use this page to login; they cannot login from the regular /login page.
     */
    public function showAdminLoginForm()
    {
        return view('auth.admin-login');

    }
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $rules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Password::defaults()],
        'role' => ['required', 'in:student,supervisor,committee'],
        'specialty_id' => ['required', 'exists:specialties,id'], 
        'phone' => ['nullable', 'string', 'max:255'],
        'department' => ['nullable', 'string', 'max:255'],
        'department_general' => ['nullable', 'string', 'max:255'],
        'phone_general' => ['nullable', 'string', 'max:255'],
        'graduation_year' => ['nullable', 'integer', 'min:2020', 'max:2100'],
        'bio' => ['nullable', 'string'],
        'username' => ['nullable', 'string', 'max:255', 'unique:users'],
        'student_id' => ['nullable', 'string', 'max:255', 'unique:users'],
    ];

    $validated = $request->validate($rules, [
        'name.required' => 'الاسم مطلوب',
        'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
        'specialty_id.required' => 'يجب اختيار التخصص لضمان ظهورك في القسم الصحيح',
        'password.confirmed' => 'كلمة المرور غير متطابقة',
        'student_id.unique' => 'رقم الطالب مستخدم بالفعل',
    ]);

    try {
        // توحيد جلب البيانات سواء كانت عامة أو مخصصة
        $department = $request->department ?? $request->department_general;
        $phone = $request->phone ?? $request->phone_general;

        $userData = [
            'name'            => $validated['name'],
            'username'        => $validated['username'] ?? null,
            'email'           => $validated['email'],
            'password'        => Hash::make($validated['password']),
            'role'            => $validated['role'],
            'student_id'      => $validated['student_id'] ?? null,
            'phone'           => $phone,
            'department'      => $department,
            'specialty_id'    => $validated['specialty_id'], // استخدام القيمة المفلترة
            'graduation_year' => $validated['graduation_year'] ?? null,
            'bio'             => $validated['bio'] ?? null,
        ];

        // إنشاء المستخدم
        $user = User::create($userData);

        // التحقق من الدور وإسناده
        $roleName = $validated['role'];
        if (!Role::where('name', $roleName)->exists()) {
            $user->delete();
            return back()->withErrors(['role' => 'الدور المحدد غير موجود في النظام.'])->withInput();
        }

        $user->assignRole($roleName);
        Auth::login($user);

        return redirect('/dashboard')->with('success', 'تم إنشاء الحساب بنجاح!');

    } catch (\Exception $e) {
        Log::error('Registration Error: ' . $e->getMessage());
        
        // حذف المستخدم إذا تم إنشاؤه وفشلت الخطوات التالية
        if (isset($user)) { $user->delete(); }

        // لإظهار الخطأ الحقيقي أثناء التطوير (احذف هذا السطر في الإنتاج)
        return back()->withErrors(['error' => 'خطأ تقني: ' . $e->getMessage()])->withInput();
    }
}

    public function logout(Request $request)
    {
        $wasAdmin = Auth::user()?->hasRole('admin') ?? false;
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        if ($wasAdmin) {
            return redirect()->route('admin.login')->with('success', 'تم تسجيل الخروج بنجاح.');
        }
        return redirect('/login')->with('success', 'تم تسجيل الخروج بنجاح.');
    }
}
