# 🎓 ProjexiOn - منصة ذكية لإدارة مشاريع التخرج

منصة ويب احترافية لإدارة مشاريع التخرج في الجامعات، تربط بين الطلاب والمشرفين وإدارة القسم ولجنة التقييم، مع نظام ذكاء اصطناعي متقدم لاكتشاف التشابه بين المشاريع واكتشاف المحتوى المولد بالذكاء الاصطناعي.

---

## 📋 فهرس المحتويات

- [نظرة عامة](#نظرة-عامة)
- [المميزات الرئيسية](#المميزات-الرئيسية)
- [أنواع المستخدمين](#أنواع-المستخدمين)
- [التقنيات المستخدمة](#التقنيات-المستخدمة)
- [التثبيت والإعداد](#التثبيت-والإعداد)
- [البنية الأساسية للمشروع](#البنية-الأساسية-للمشروع)
- [التشغيل](#التشغيل)
- [الأدوار والصلاحيات](#الأدوار-والصلاحيات)
- [الواجهات البرمجية (API)](#الواجهات-البرمجية-api)
- [بنية قاعدة البيانات](#بنية-قاعدة-البيانات)
- [نظام الإشعارات](#نظام-الإشعارات)
- [خدمة الذكاء الاصطناعي](#خدمة-الذكاء-الاصطناعي)
- [المساهمة](#المساهمة)

---

## 🎯 نظرة عامة

**ProjexiOn** هو نظام متكامل لإدارة مشاريع التخرج في الجامعات، يوفر بيئة شاملة لإدارة دورة حياة المشاريع من الفكرة الأولية حتى التقييم النهائي. النظام يتضمن:

- إدارة كاملة للمشاريع والمجموعات الطلابية
- نظام صلاحيات متقدم قائم على الأدوار (RBAC)
- نظام إشعارات فوري
- فحص تشابه المشاريع باستخدام الذكاء الاصطناعي
- اكتشاف المحتوى المولد بالذكاء الاصطناعي
- توليد تقارير PDF احترافية
- واجهة مستخدم حديثة ومتجاوبة

---

## ✨ المميزات الرئيسية

### 1. إدارة المشاريع
- **إنشاء المشاريع**: الطلاب يمكنهم إنشاء مشاريع تخرج مع التفاصيل الكاملة
- **الموافقة على المشاريع**: المشرفون يمكنهم مراجعة وقبول/رفض المشاريع
- **تقسيم المشاريع**: تقسيم المشروع إلى أجزاء (Phases) ومراحل (Sections)
- **رفع الملفات**: رفع وتخزين ملفات المشاريع (PDF, DOCX, etc.)
- **متابعة التقدم**: تتبع حالة كل جزء من المشروع

### 2. إدارة المجموعات
- **إنشاء المجموعات**: المشرفون يمكنهم إنشاء مجموعات طلابية
- **إدارة الأعضاء**: إضافة/إزالة طلاب من المجموعات
- **تعيين المديرين**: تعيين مديرين للمجموعات

### 3. نظام التقارير
- **رفع التقارير**: الطلاب يمكنهم رفع تقارير مرحلية
- **عرض التقارير**: المشرفون يمكنهم مراجعة التقارير
- **التقييمات**: لجنة التقييم يمكنها إدخال درجات للمشاريع

### 4. نظام الإشعارات
- **إشعارات فورية**: إشعارات تلقائية للأحداث المهمة
- **إرسال إشعارات مخصصة**: المشرفون والإدارة يمكنهم إرسال إشعارات
- **واجهة احترافية**: صفحة إشعارات متكاملة مع إمكانية الحذف والقراءة

### 5. الذكاء الاصطناعي
- **فحص التشابه**: اكتشاف التشابه بين المشاريع باستخدام NLP
- **اكتشاف AI**: اكتشاف المحتوى المولد بالذكاء الاصطناعي
- **نسبة التشابه**: حساب نسبة التشابه الدقيقة

### 6. الملف الشخصي
- **تعديل المعلومات**: جميع المستخدمين يمكنهم تعديل بياناتهم الشخصية
- **إدارة كلمة المرور**: تغيير كلمة المرور

### 7. إدارة المستخدمين (للإدارة)
- **عرض المستخدمين**: قائمة بجميع المستخدمين
- **تعديل المستخدمين**: تعديل بيانات وأدوار المستخدمين
- **حظر المستخدمين**: إمكانية حظر/إلغاء حظر المستخدمين
- **حذف المستخدمين**: حذف المستخدمين

---

## 👥 أنواع المستخدمين

### 1. **طالب (Student)**
- إنشاء المشاريع الخاصة به
- الانضمام إلى المجموعات
- رفع التقارير والملفات
- متابعة حالة المشاريع
- عرض الإشعارات

### 2. **مشرف (Supervisor)**
- إنشاء وإدارة المجموعات
- مراجعة وقبول/رفض المشاريع
- إضافة التقييمات والدرجات
- إرسال الإشعارات
- متابعة تقدم المشاريع
- إضافة ملاحظات على المشاريع

### 3. **إدارة القسم (Department Admin)**
- جميع صلاحيات المشرف
- إدارة جميع المستخدمين (عرض، تعديل، حظر، حذف)
- إدارة جميع المجموعات
- إدارة جميع المشاريع
- إرسال إشعارات لجميع المستخدمين

### 4. **لجنة التقييم (Committee)**
- عرض المشاريع
- إنشاء التقييمات النهائية
- إدخال الدرجات
- توليد تقارير PDF للتقييمات

### 5. **مدير النظام (Admin)**
- جميع الصلاحيات الكاملة
- إدارة شاملة للنظام

---

## 🛠️ التقنيات المستخدمة

### Backend
- **Laravel 12.34.0**: إطار عمل PHP حديث
- **PHP 8.2.12**: لغة البرمجة الرئيسية
- **Spatie Laravel Permission**: إدارة الأدوار والصلاحيات (RBAC)
- **Laravel DomPDF**: توليد ملفات PDF للتقييمات
- **PHPWord**: معالجة ملفات Word
- **MySQL/MariaDB**: قاعدة البيانات

### Frontend
- **Tailwind CSS 4**: إطار عمل CSS حديث ومتجاوب
- **Blade Templates**: قوالب Laravel
- **Vite**: أداة البناء للـ Assets
- **JavaScript (Vanilla)**: للتفاعل الديناميكي

### AI Service
- **Python 3.8+**: لغة البرمجة للخدمة
- **FastAPI**: إطار عمل API حديث وسريع
- **scikit-learn**: مكتبة Machine Learning
- **TF-IDF Vectorization**: استخراج الميزات من النصوص
- **Cosine Similarity**: حساب التشابه بين النصوص
- **PyPDF2**: استخراج النصوص من ملفات PDF

### الأدوات
- **Composer**: إدارة حزم PHP
- **NPM**: إدارة حزم JavaScript
- **Git**: إدارة الإصدارات

---

## 📦 التثبيت والإعداد

### متطلبات النظام

- PHP >= 8.2
- Composer
- Node.js >= 18.x & NPM
- Python 3.8+
- MySQL/MariaDB
- Git

### 1. استنساخ المشروع

```bash
git clone <repository-url>
cd SmartGrad
```

### 2. تثبيت حزم PHP

```bash
composer install
```

### 3. إعداد قاعدة البيانات

قم بإنشاء قاعدة بيانات MySQL جديدة، ثم:

```bash
# نسخ ملف البيئة
cp .env.example .env

# تعديل ملف .env وإضافة معلومات قاعدة البيانات
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=projexion
# DB_USERNAME=root
# DB_PASSWORD=

# توليد مفتاح التطبيق
php artisan key:generate

# تشغيل Migrations
php artisan migrate

# تشغيل Seeders (إنشاء الأدوار والبيانات الأولية)
php artisan db:seed
```

### 4. تثبيت حزم Frontend

```bash
npm install
npm run build
```

### 5. إعداد خدمة Python AI

```bash
cd python_ai_service
pip install -r requirements.txt
```

أضف السطر التالي في ملف `.env`:

```env
AI_SIMILARITY_URL=http://localhost:8001
```

### 6. إعداد الصلاحيات

```bash
# إنشاء مجلد التخزين مع الصلاحيات الصحيحة
php artisan storage:link
```

---

## 📁 البنية الأساسية للمشروع

```
SmartGrad/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php        # المصادقة والتسجيل
│   │   │   ├── API/
│   │   │   │   └── AISimilarityController.php # API للذكاء الاصطناعي
│   │   │   ├── DashboardController.php        # لوحة التحكم
│   │   │   ├── ProjectController.php          # إدارة المشاريع
│   │   │   ├── GroupController.php            # إدارة المجموعات
│   │   │   ├── EvaluationController.php       # التقييمات
│   │   │   ├── ProjectReportController.php    # التقارير
│   │   │   ├── ProjectPhaseController.php     # أجزاء المشروع
│   │   │   ├── NotificationController.php     # الإشعارات
│   │   │   ├── UserController.php             # إدارة المستخدمين
│   │   │   └── ProfileController.php          # الملف الشخصي
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php                           # نموذج المستخدم
│   │   ├── Project.php                        # نموذج المشروع
│   │   ├── Group.php                          # نموذج المجموعة
│   │   ├── Evaluation.php                     # نموذج التقييم
│   │   ├── ProjectPhase.php                   # نموذج جزء المشروع
│   │   ├── ProjectReport.php                  # نموذج التقرير
│   │   ├── Notification.php                   # نموذج الإشعار
│   │   ├── SimilarityCheck.php                # نموذج فحص التشابه
│   │   └── ...                                # نماذج أخرى
│   └── Services/
│       ├── AISimilarityService.php            # خدمة فحص التشابه
│       ├── FileSimilarityService.php          # خدمة فحص ملفات
│       └── NotificationService.php            # خدمة الإشعارات
├── bootstrap/
│   └── app.php                                # إعداد Laravel
├── config/
│   ├── app.php                                # إعدادات التطبيق
│   ├── database.php                           # إعدادات قاعدة البيانات
│   └── permission.php                         # إعدادات الصلاحيات
├── database/
│   ├── migrations/                            # Migrations قاعدة البيانات
│   └── seeders/                               # Seeders البيانات الأولية
├── public/
│   ├── index.php                              # نقطة الدخول
│   └── storage/                               # الملفات العامة
├── python_ai_service/
│   ├── main.py                                # خدمة FastAPI
│   ├── requirements.txt                       # حزم Python
│   ├── start_ai_service.bat                   # سكريبت التشغيل (Windows)
│   ├── start_ai_service.sh                    # سكريبت التشغيل (Linux/Mac)
│   └── README.md                              # توثيق الخدمة
├── resources/
│   ├── views/                                 # قوالب Blade
│   │   ├── layouts/
│   │   │   └── app.blade.php                  # القالب الأساسي
│   │   ├── auth/
│   │   │   ├── login.blade.php                # صفحة تسجيل الدخول
│   │   │   └── register.blade.php             # صفحة التسجيل
│   │   ├── dashboard/
│   │   │   ├── index.blade.php                # لوحة التحكم
│   │   │   ├── student.blade.php              # لوحة تحكم الطالب
│   │   │   └── admin.blade.php                # لوحة تحكم الإدارة
│   │   ├── projects/                          # صفحات المشاريع
│   │   ├── groups/                            # صفحات المجموعات
│   │   ├── notifications/                     # صفحات الإشعارات
│   │   └── ...                                # صفحات أخرى
│   ├── css/
│   │   └── app.css                            # ملفات CSS
│   └── js/
│       └── app.js                             # ملفات JavaScript
├── routes/
│   ├── web.php                                # Routes الويب
│   └── api.php                                # Routes API
├── storage/
│   ├── app/                                   # الملفات المرفوعة
│   └── logs/                                  # ملفات السجلات
├── tests/                                     # الاختبارات
├── composer.json                              # حزم PHP
├── package.json                               # حزم JavaScript
├── vite.config.js                             # إعدادات Vite
└── README.md                                  # هذا الملف
```

---

## 🚀 التشغيل

### تشغيل Laravel

```bash
# في المجلد الرئيسي
php artisan serve
```

أو على منفذ محدد:

```bash
php artisan serve --port=8000
```

يمكنك الوصول إلى التطبيق على: `http://localhost:8000`

### تشغيل خدمة Python AI

#### Windows:
```bash
cd python_ai_service
start_ai_service.bat
```

#### Linux/Mac:
```bash
cd python_ai_service
./start_ai_service.sh
```

#### يدوياً:
```bash
cd python_ai_service
python main.py
```

أو باستخدام uvicorn:

```bash
uvicorn main:app --host 0.0.0.0 --port 8001 --reload
```

خدمة AI تعمل على: `http://localhost:8001`

### تشغيل Frontend Development Server

```bash
npm run dev
```

---

## 🔐 الأدوار والصلاحيات

### Student (طالب)
- `create projects` - إنشاء المشاريع
- `view own projects` - عرض مشاريعه فقط
- `edit own projects` - تعديل مشاريعه فقط
- `create project reports` - إنشاء التقارير
- `upload project files` - رفع ملفات المشاريع

### Supervisor (مشرف)
- جميع صلاحيات الطالب
- `approve projects` - الموافقة على المشاريع
- `reject projects` - رفض المشاريع
- `create groups` - إنشاء المجموعات
- `manage groups` - إدارة المجموعات
- `create evaluations` - إنشاء التقييمات
- `send notifications` - إرسال الإشعارات

### Committee (لجنة التقييم)
- `view projects` - عرض جميع المشاريع
- `create evaluations` - إنشاء التقييمات
- `generate pdf` - توليد تقارير PDF

### Department Admin (إدارة القسم)
- جميع الصلاحيات
- `manage users` - إدارة المستخدمين
- `ban users` - حظر المستخدمين
- `manage all groups` - إدارة جميع المجموعات
- `manage all projects` - إدارة جميع المشاريع

### Admin (مدير النظام)
- جميع الصلاحيات الكاملة بدون قيود

---

## 🌐 الواجهات البرمجية (API)

### AI Similarity API

#### `POST /api/ai/check-similarity`
فحص تشابه المشروع مع المشاريع الموجودة

**Request:**
```json
{
  "project": {
    "title": "عنوان المشروع",
    "description": "وصف المشروع",
    "objectives": "أهداف المشروع"
  },
  "existing_projects": [...]
}
```

**Response:**
```json
{
  "similarity_score": 0.85,
  "similar_projects": [...],
  "ai_probability": 0.12
}
```

#### `GET /api/ai/similarity-results/{project}`
الحصول على نتائج فحص التشابه لمشروع معين

### Notifications API

#### `GET /notifications/list`
الحصول على قائمة الإشعارات (للاستخدام في Dropdown)

#### `POST /notifications/{notification}/read`
تعليم إشعار كمقروء

#### `POST /notifications/read-all`
تعليم جميع الإشعارات كمقروءة

#### `GET /notifications/unread-count`
الحصول على عدد الإشعارات غير المقروءة

#### `DELETE /notifications/{notification}`
حذف إشعار

---

## 🗄️ بنية قاعدة البيانات

### الجداول الرئيسية

#### `users`
- معلومات المستخدمين (الاسم، البريد، كلمة المرور، etc.)
- `student_id` - رقم الطالب (اختياري)
- `phone` - رقم الهاتف
- `department` - القسم
- `banned_at` - تاريخ الحظر (إن وُجد)

#### `projects`
- معلومات المشاريع
- `title` - عنوان المشروع
- `description` - الوصف
- `objectives` - الأهداف
- `status` - الحالة (pending, approved, rejected, etc.)
- `similarity_score` - نسبة التشابه
- `ai_probability` - احتمال المحتوى المولد بالذكاء الاصطناعي

#### `groups`
- المجموعات الطلابية
- `name` - اسم المجموعة
- `supervisor_id` - المشرف
- `status` - الحالة (active, completed, archived)

#### `project_phases`
- أجزاء المشروع
- `project_id` - المشروع
- `title` - عنوان الجزء
- `description` - الوصف
- `status` - الحالة

#### `evaluations`
- التقييمات
- `project_id` - المشروع
- `evaluator_id` - المقيم
- `scores` - الدرجات (JSON)
- `comments` - التعليقات

#### `notifications`
- الإشعارات
- `user_id` - المستلم
- `title` - العنوان
- `message` - الرسالة
- `read` - حالة القراءة
- `type` - نوع الإشعار

#### `similarity_checks`
- سجلات فحص التشابه
- `project_id` - المشروع
- `similarity_score` - نسبة التشابه
- `ai_probability` - احتمال المحتوى المولد بالذكاء الاصطناعي

---

## 🔔 نظام الإشعارات

النظام يحتوي على نظام إشعارات متكامل:

### المميزات
- **إشعارات تلقائية**: عند إنشاء/تحديث المشاريع، الموافقة، الرفض، etc.
- **إشعارات مخصصة**: المشرفون والإدارة يمكنهم إرسال إشعارات
- **واجهة احترافية**: صفحة إشعارات كاملة مع إمكانية:
  - عرض جميع الإشعارات
  - تعليم كمقروء/غير مقروء
  - حذف الإشعارات
  - تعليم الكل كمقروء
- **عداد في Navbar**: عرض عدد الإشعارات غير المقروءة

### أنواع الإشعارات
- `project_created` - تم إنشاء مشروع
- `project_approved` - تم قبول مشروع
- `project_rejected` - تم رفض مشروع
- `evaluation_added` - تم إضافة تقييم
- `custom` - إشعار مخصص

---

## 🤖 خدمة الذكاء الاصطناعي

خدمة Python FastAPI منفصلة تقوم بـ:

### الوظائف
1. **فحص تشابه المشاريع**: مقارنة المشاريع باستخدام TF-IDF و Cosine Similarity
2. **فحص تشابه الملفات**: مقارنة ملفات PDF و DOCX
3. **اكتشاف المحتوى المولد بالذكاء الاصطناعي**: حساب احتمالية أن المحتوى مولد بالذكاء الاصطناعي
4. **استخراج النصوص**: استخراج النصوص من ملفات PDF

### Endpoints
- `GET /health` - فحص حالة الخدمة
- `POST /api/check-similarity` - فحص تشابه المشروع
- `POST /api/check-file-similarity` - فحص تشابه الملف
- `POST /api/detect-ai` - اكتشاف المحتوى المولد بالذكاء الاصطناعي

### التقنيات المستخدمة
- **TF-IDF Vectorization**: تحويل النصوص إلى متجهات عددية
- **Cosine Similarity**: حساب التشابه بين المتجهات
- **scikit-learn**: مكتبة Machine Learning

---

## 📝 حالات المشاريع (Status)

### Projects
- `pending` - قيد المراجعة
- `under_review` - قيد المراجعة
- `approved` - مقبول
- `rejected` - مرفوض
- `in_progress` - قيد التنفيذ
- `completed` - مكتمل
- `archived` - مؤرشف

### Groups
- `active` - نشط
- `completed` - مكتمل
- `archived` - مؤرشف

---

## 🎨 الواجهة

- تصميم حديث ومتجاوب باستخدام Tailwind CSS 4
- دعم Dark Mode
- واجهة سهلة الاستخدام
- أيقونات SVG
- تجربة مستخدم سلسة

---

## 🔧 الإعدادات المتقدمة

### إعدادات Laravel (.env)
```env
APP_NAME="ProjexiOn"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=projexion
DB_USERNAME=root
DB_PASSWORD=

AI_SIMILARITY_URL=http://localhost:8001
```

### إعدادات Python AI Service
الخدمة تعمل على المنفذ `8001` افتراضياً. يمكن تغييره في `main.py`.

---

## 🧪 الاختبار

```bash
php artisan test
```

---

## 📚 موارد إضافية

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [FastAPI Documentation](https://fastapi.tiangolo.com/)
- [Spatie Permission Documentation](https://spatie.be/docs/laravel-permission)

---

## 🤝 المساهمة

المشروع مفتوح للمساهمة والتطوير. نحن نرحب بجميع المساهمات!

### خطوات المساهمة
1. Fork المشروع
2. إنشاء Branch للميزة (`git checkout -b feature/AmazingFeature`)
3. Commit التغييرات (`git commit -m 'Add some AmazingFeature'`)
4. Push إلى Branch (`git push origin feature/AmazingFeature`)
5. فتح Pull Request

---

## 📄 الترخيص

هذا المشروع مرخص تحت [MIT License](LICENSE).

---

## 👨‍💻 المطورون

تم تطوير هذا المشروع باستخدام أحدث التقنيات وأفضل الممارسات.

---

## 📞 الدعم

للدعم والمساعدة:
- افتح Issue في المستودع
- راسلنا على البريد الإلكتروني

---

## 🔄 الإصدارات

### الإصدار الحالي: 2.0.0

### التحديثات القادمة
- [ ] نظام محادثات مباشرة
- [ ] لوحة تحليلات متقدمة
- [ ] تطبيق موبايل
- [ ] تكامل مع أنظمة الجامعة

---

## ⚠️ ملاحظات مهمة

1. **قاعدة البيانات**: تأكد من إنشاء قاعدة البيانات قبل تشغيل migrations
2. **خدمة AI**: يجب تشغيل خدمة Python AI قبل استخدام ميزة فحص التشابه
3. **البيئة**: في بيئة الإنتاج، قم بتعطيل `APP_DEBUG` في ملف `.env`
4. **الأمان**: قم بتغيير `APP_KEY` وقم بتعيين كلمات مرور قوية للمستخدمين
5. **النسخ الاحتياطي**: قم بعمل نسخ احتياطي منتظم لقاعدة البيانات

---

## 🎯 حالة المشروع

✅ **جاهز للاستخدام في بيئة التطوير**

المشروع في مرحلة التطوير النشط. جميع الميزات الأساسية تعمل بشكل صحيح.

---

**تم التطوير بـ ❤️ باستخدام Laravel و Python**
