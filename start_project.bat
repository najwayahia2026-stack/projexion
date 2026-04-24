@echo off
chcp 65001 >nul
echo ========================================
echo    ProjexiOn - تشغيل المشروع
echo ========================================
echo.

REM التحقق من وجود Composer
where composer >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [خطأ] Composer غير مثبت أو غير موجود في PATH
    echo يرجى تثبيت Composer من: https://getcomposer.org/
    pause
    exit /b 1
)

REM التحقق من وجود PHP
where php >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [خطأ] PHP غير مثبت أو غير موجود في PATH
    echo يرجى تثبيت PHP من: https://www.php.net/
    pause
    exit /b 1
)

REM التحقق من وجود Python
where python >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [خطأ] Python غير مثبت أو غير موجود في PATH
    echo يرجى تثبيت Python من: https://www.python.org/
    pause
    exit /b 1
)

REM التحقق من وجود Node.js
where node >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [تحذير] Node.js غير مثبت أو غير موجود في PATH
    echo قد تحتاج Node.js لتشغيل Frontend Assets
)

echo.
echo [1/6] التحقق من وجود ملف .env...
if not exist .env (
    echo ملف .env غير موجود. يتم نسخ .env.example...
    if exist .env.example (
        copy .env.example .env >nul
        echo تم نسخ .env.example إلى .env
    ) else (
        echo [خطأ] ملف .env.example غير موجود!
        pause
        exit /b 1
    )
) else (
    echo ملف .env موجود ✓
)

echo.
echo [2/6] تثبيت حزم PHP (Composer)...
if not exist vendor (
    call composer install
    if %ERRORLEVEL% NEQ 0 (
        echo [خطأ] فشل تثبيت حزم Composer
        pause
        exit /b 1
    )
) else (
    echo حزم PHP مثبتة بالفعل ✓
)

echo.
echo [3/6] التحقق من مفتاح التطبيق...
php artisan key:generate --show >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo يتم توليد مفتاح التطبيق...
    php artisan key:generate
)

echo.
echo [4/6] تثبيت حزم JavaScript (NPM)...
if exist package.json (
    if not exist node_modules (
        call npm install
        if %ERRORLEVEL% NEQ 0 (
            echo [خطأ] فشل تثبيت حزم NPM
            pause
            exit /b 1
        )
    ) else (
        echo حزم JavaScript مثبتة بالفعل ✓
    )
    
    echo بناء Assets...
    call npm run build
    if %ERRORLEVEL% NEQ 0 (
        echo [تحذير] فشل بناء Assets. قد تحتاج إلى تشغيل npm run build يدوياً
    )
) else (
    echo ملف package.json غير موجود. يتم تخطي هذا الخطوة.
)

echo.
echo [5/6] التحقق من تثبيت حزم Python...
cd python_ai_service
if not exist __pycache__ (
    echo تثبيت حزم Python...
    pip install -r requirements.txt
    if %ERRORLEVEL% NEQ 0 (
        echo [خطأ] فشل تثبيت حزم Python
        cd ..
        pause
        exit /b 1
    )
) else (
    echo حزم Python مثبتة بالفعل ✓
)
cd ..

echo.
echo [6/6] التحقق من قاعدة البيانات...
echo يرجى التأكد من:
echo   1. MySQL/MariaDB يعمل
echo   2. قاعدة البيانات موجودة في ملف .env
echo   3. تم تشغيل Migrations (php artisan migrate)
echo.
set /p run_migrations="هل تريد تشغيل Migrations الآن؟ (y/n): "
if /i "%run_migrations%"=="y" (
    echo تشغيل Migrations...
    php artisan migrate
    if %ERRORLEVEL% NEQ 0 (
        echo [تحذير] فشل تشغيل Migrations. تأكد من إعدادات قاعدة البيانات في ملف .env
    )
)

echo.
echo ========================================
echo    بدء تشغيل الخدمات
echo ========================================
echo.

REM إيقاف أي عمليات سابقة على المنافذ 8000 و 8001
echo إيقاف العمليات القديمة على المنافذ 8000 و 8001...
for /f "tokens=5" %%a in ('netstat -ano ^| findstr ":8000"') do (
    taskkill /F /PID %%a >nul 2>&1
)
for /f "tokens=5" %%a in ('netstat -ano ^| findstr ":8001"') do (
    taskkill /F /PID %%a >nul 2>&1
)
timeout /t 2 /nobreak >nul

echo.
echo [1/2] تشغيل خدمة Python AI على المنفذ 8001...
start "ProjexiOn AI Service" /MIN cmd /c "cd /d %~dp0python_ai_service && python main.py"
timeout /t 5 /nobreak >nul

REM التحقق من خدمة AI
echo التحقق من خدمة AI...
timeout /t 3 /nobreak >nul
curl -s http://localhost:8001/docs >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo ✓ خدمة AI تعمل بنجاح
) else (
    echo ⚠ تحذير: خدمة AI قد تحتاج وقت إضافي للبدء
)

echo.
echo [2/2] تشغيل Laravel على المنفذ 8000...
echo.
echo ========================================
echo    المشروع يعمل الآن!
echo ========================================
echo.
echo يمكنك الوصول إلى:
echo   - التطبيق الرئيسي: http://127.0.0.1:8000
echo   - صفحة تسجيل الدخول: http://127.0.0.1:8000/login
echo   - وثائق AI API: http://localhost:8001/docs
echo.
echo ملاحظات:
echo   - لإيقاف الخدمات، اضغط Ctrl+C في هذا النافذة
echo   - خدمة AI تعمل في نافذة منفصلة (مصغرة)
echo.
echo ========================================
echo.

REM تشغيل Laravel في النافذة الحالية
php artisan serve --host=127.0.0.1 --port=8000

pause
