# تثبيت وتشغيل خدمة Python AI

## المتطلبات الأساسية

- Python 3.8 أو أحدث
- pip (مثبت تلقائياً مع Python)

## خطوات التثبيت

### الطريقة الأولى: استخدام Script التلقائي (موصى به)

#### على Windows:
1. انقر نقراً مزدوجاً على ملف `start_ai_service.bat`
2. أو افتح Command Prompt واكتب:
   ```bash
   cd python_ai_service
   start_ai_service.bat
   ```

#### على Linux/Mac:
1. افتح Terminal
2. اذهب إلى مجلد `python_ai_service`
3. قم بتشغيل:
   ```bash
   chmod +x start_ai_service.sh
   ./start_ai_service.sh
   ```

### الطريقة الثانية: التثبيت اليدوي

1. افتح Terminal/Command Prompt
2. اذهب إلى مجلد `python_ai_service`:
   ```bash
   cd python_ai_service
   ```

3. قم بتثبيت المتطلبات:
   ```bash
   pip install -r requirements.txt
   ```
   أو على Linux/Mac:
   ```bash
   pip3 install -r requirements.txt
   ```

4. شغّل الخدمة:
   ```bash
   python main.py
   ```
   أو:
   ```bash
   python3 main.py
   ```

## التحقق من التشغيل

بعد تشغيل الخدمة، افتح المتصفح على:
```
http://localhost:8001/health
```

يجب أن ترى:
```json
{
  "status": "healthy",
  "service": "SmartGrad AI Similarity Service",
  "version": "2.0.0"
}
```

أو افتح:
```
http://localhost:8001/
```

لرؤية جميع الـ endpoints المتاحة.

## حل المشاكل الشائعة

### المشكلة: Python غير مثبت أو غير موجود في PATH

**الحل:**
- قم بتثبيت Python من [python.org](https://www.python.org/downloads/)
- تأكد من تفعيل "Add Python to PATH" أثناء التثبيت
- أعد تشغيل Terminal/Command Prompt بعد التثبيت

### المشكلة: خطأ في تثبيت المكتبات

**الحل:**
```bash
pip install --upgrade pip
pip install -r requirements.txt --force-reinstall
```

### المشكلة: Port 8001 مستخدم بالفعل

**الحل:**
1. أوقف الخدمة الأخرى التي تستخدم Port 8001
2. أو غيّر المنفذ في ملف `main.py`:
   ```python
   uvicorn.run(app, host="0.0.0.0", port=8002)  # استخدم 8002 أو أي منفذ آخر
   ```
3. تأكد من تحديث الإعدادات في Laravel (`config/services.php`)

### المشكلة: PDF extraction لا يعمل

**الحل:**
```bash
pip install PyPDF2 pdfplumber
```

### المشكلة: الخدمة لا تستجيب

**الحل:**
1. تأكد من أن الخدمة تعمل (تحقق من Terminal)
2. تأكد من أن Firewall لا يحجب Port 8001
3. جرب الوصول من `http://127.0.0.1:8001/health` بدلاً من `localhost`

## إعدادات Laravel

تأكد من أن ملف `config/services.php` يحتوي على:

```php
'ai_similarity' => [
    'url' => env('AI_SIMILARITY_URL', 'http://localhost:8001'),
],
```

وفي ملف `.env`:
```
AI_SIMILARITY_URL=http://localhost:8001
```

## ملاحظات مهمة

- الخدمة يجب أن تعمل في نافذة Terminal منفصلة
- لا تغلق نافذة Terminal أثناء استخدام الخدمة
- للاستخدام في الإنتاج، يُنصح بتشغيل الخدمة كخدمة نظام (systemd service) أو استخدام process manager مثل PM2

## الاختبار

بعد تشغيل الخدمة، يمكنك اختبارها باستخدام curl:

```bash
curl http://localhost:8001/health
```

يجب أن تحصل على استجابة JSON تشير إلى أن الخدمة تعمل بشكل صحيح.
