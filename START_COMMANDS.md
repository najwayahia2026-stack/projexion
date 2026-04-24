# 🚀 أوامر تشغيل مشروع ProjexiOn

هذا الملف يحتوي على جميع الأوامر اللازمة لتشغيل المشروع بالترتيب.

---

## 📋 المتطلبات الأساسية

قبل البدء، تأكد من تثبيت:

- ✅ PHP >= 8.2
- ✅ Composer
- ✅ Node.js >= 18.x & NPM
- ✅ Python 3.8+
- ✅ MySQL/MariaDB

---

## 🎯 الطريقة السريعة (مستحسنة)

### Windows:
```bash
start_project.bat
```

### Linux/Mac:
```bash
chmod +x start_project.sh
./start_project.sh
```

---

## 📝 الأوامر اليدوية (خطوة بخطوة)

### 1️⃣ التحقق من ملف البيئة (.env)

**Windows:**
```cmd
if not exist .env copy .env.example .env
```

**Linux/Mac:**
```bash
if [ ! -f .env ]; then cp .env.example .env; fi
```

**أو يدوياً:**
```bash
cp .env.example .env
```

قم بتعديل ملف `.env` وإضافة معلومات قاعدة البيانات:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=projexion
DB_USERNAME=root
DB_PASSWORD=your_password

AI_SIMILARITY_URL=http://localhost:8001
```

---

### 2️⃣ تثبيت حزم PHP (Composer)

```bash
composer install
```

هذا الأمر يثبت جميع الحزم المطلوبة من `composer.json`:
- Laravel Framework
- Spatie Laravel Permission
- Laravel DomPDF
- PHPWord
- وغيرها...

---

### 3️⃣ توليد مفتاح التطبيق

```bash
php artisan key:generate
```

هذا الأمر يولد مفتاح تشفير للتطبيق ويضيفه تلقائياً في ملف `.env`.

---

### 4️⃣ إعداد قاعدة البيانات

#### أ) إنشاء قاعدة البيانات:

قم بتسجيل الدخول إلى MySQL:
```bash
mysql -u root -p
```

ثم أنشئ قاعدة البيانات:
```sql
CREATE DATABASE projexion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### ب) تشغيل Migrations:

```bash
php artisan migrate
```

هذا الأمر ينشئ جميع الجداول في قاعدة البيانات.

#### ج) تشغيل Seeders (اختياري):

```bash
php artisan db:seed
```

هذا الأمر يضيف البيانات الأولية (الأدوار، etc.).

---

### 5️⃣ تثبيت حزم JavaScript (NPM)

```bash
npm install
```

هذا الأمر يثبت جميع الحزم من `package.json`:
- Tailwind CSS 4
- Vite
- Laravel Vite Plugin
- وغيرها...

---

### 6️⃣ بناء Frontend Assets

```bash
npm run build
```

**أو للتطوير مع Hot Reload:**
```bash
npm run dev
```

---

### 7️⃣ إنشاء رابط التخزين (Storage Link)

```bash
php artisan storage:link
```

هذا الأمر ينشئ رابط رمزي لملفات التخزين.

---

### 8️⃣ تثبيت حزم Python AI Service

```bash
cd python_ai_service
pip install -r requirements.txt
```

**أو مع Python 3:**
```bash
pip3 install -r requirements.txt
```

هذا الأمر يثبت:
- FastAPI
- uvicorn
- scikit-learn
- PyPDF2
- وغيرها...

---

### 9️⃣ تشغيل خدمة Python AI

**Windows:**
```cmd
cd python_ai_service
start "ProjexiOn AI Service" /MIN cmd /c "python main.py"
```

**أو يدوياً:**
```bash
cd python_ai_service
python main.py
```

**أو باستخدام uvicorn:**
```bash
cd python_ai_service
uvicorn main:app --host 0.0.0.0 --port 8001 --reload
```

خدمة AI تعمل على: `http://localhost:8001`

**للتحقق من عمل الخدمة:**
```bash
curl http://localhost:8001/health
```

---

### 🔟 تشغيل Laravel

```bash
php artisan serve
```

**أو على منفذ محدد:**
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

**أو للوصول من الشبكة المحلية:**
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Laravel يعمل على: `http://127.0.0.1:8000`

---

## 🔄 إعادة تشغيل المشروع

### Windows:
```cmd
REM إيقاف العمليات على المنافذ
netstat -ano | findstr ":8000" 
taskkill /F /PID <PID>
netstat -ano | findstr ":8001"
taskkill /F /PID <PID>

REM ثم شغّل start_project.bat مرة أخرى
start_project.bat
```

### Linux/Mac:
```bash
# إيقاف العمليات على المنافذ
lsof -ti:8000 | xargs kill -9
lsof -ti:8001 | xargs kill -9

# ثم شغّل السكريبت مرة أخرى
./start_project.sh
```

---

## 🧹 تنظيف Cache (إذا واجهت مشاكل)

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

**أو تنظيف شامل:**
```bash
php artisan optimize:clear
```

---

## 🔍 التحقق من عمل الخدمات

### التحقق من Laravel:
```bash
curl http://127.0.0.1:8000
```

يجب أن ترى HTML صفحة تسجيل الدخول.

### التحقق من خدمة AI:
```bash
curl http://localhost:8001/health
```

يجب أن ترى:
```json
{
  "status": "healthy",
  "service": "SmartGrad AI Similarity Service",
  "version": "2.0.0"
}
```

### التحقق من المنافذ:
**Windows:**
```cmd
netstat -ano | findstr ":8000 :8001"
```

**Linux/Mac:**
```bash
lsof -i :8000
lsof -i :8001
```

---

## 📱 الوصول إلى المشروع

بعد التشغيل، يمكنك الوصول إلى:

- 🌐 **التطبيق الرئيسي**: http://127.0.0.1:8000
- 🔐 **صفحة تسجيل الدخول**: http://127.0.0.1:8000/login
- 📝 **صفحة التسجيل**: http://127.0.0.1:8000/register
- 🏠 **لوحة التحكم**: http://127.0.0.1:8000/dashboard (بعد تسجيل الدخول)
- 🤖 **وثائق AI API**: http://localhost:8001/docs
- 🏥 **Health Check AI**: http://localhost:8001/health

---

## ⚙️ أوامر إضافية مفيدة

### تشغيل Queue Worker (إذا كنت تستخدم Queue):
```bash
php artisan queue:work
```

### تشغيل في وضع التطوير مع Hot Reload:
```bash
# Terminal 1: Laravel
php artisan serve

# Terminal 2: Frontend Dev Server
npm run dev

# Terminal 3: AI Service
cd python_ai_service && python main.py
```

### عرض جميع Routes:
```bash
php artisan route:list
```

### عرض معلومات التطبيق:
```bash
php artisan about
```

---

## 🐛 حل المشاكل الشائعة

### مشكلة: "Port already in use"
**الحل:**
- Windows: `netstat -ano | findstr ":8000"` ثم `taskkill /F /PID <PID>`
- Linux/Mac: `lsof -ti:8000 | xargs kill -9`

### مشكلة: "Class not found" أو "Autoload error"
**الحل:**
```bash
composer dump-autoload
```

### مشكلة: "Database connection failed"
**الحل:**
1. تأكد من تشغيل MySQL/MariaDB
2. تحقق من معلومات الاتصال في ملف `.env`
3. تأكد من إنشاء قاعدة البيانات

### مشكلة: "Permission denied" في Linux/Mac
**الحل:**
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 📚 ملفات التشغيل المتاحة

- `start_project.bat` - سكريبت تشغيل تلقائي لـ Windows
- `start_project.sh` - سكريبت تشغيل تلقائي لـ Linux/Mac
- `python_ai_service/start_ai_service.bat` - تشغيل خدمة AI فقط (Windows)
- `python_ai_service/start_ai_service.sh` - تشغيل خدمة AI فقط (Linux/Mac)

---

## ✅ قائمة التحقق السريعة

قبل التشغيل، تأكد من:

- [ ] PHP >= 8.2 مثبت
- [ ] Composer مثبت
- [ ] Node.js & NPM مثبت
- [ ] Python 3.8+ مثبت
- [ ] MySQL/MariaDB يعمل
- [ ] ملف `.env` موجود ومضبوط
- [ ] قاعدة البيانات موجودة
- [ ] تم تشغيل Migrations

---

## 🎯 ملخص الأوامر السريع

```bash
# 1. إعداد البيئة
cp .env.example .env
php artisan key:generate

# 2. تثبيت الحزم
composer install
npm install
cd python_ai_service && pip install -r requirements.txt && cd ..

# 3. قاعدة البيانات
php artisan migrate
php artisan db:seed

# 4. بناء Assets
npm run build

# 5. تشغيل (في terminal منفصلة)
# Terminal 1:
php artisan serve

# Terminal 2:
cd python_ai_service && python main.py
```

---

**تم التطوير بـ ❤️ باستخدام Laravel و Python**
