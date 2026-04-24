#!/bin/bash

# ProjexiOn - تشغيل المشروع (Linux/Mac)
# لتشغيل الملف: chmod +x start_project.sh && ./start_project.sh

echo "========================================"
echo "   ProjexiOn - تشغيل المشروع"
echo "========================================"
echo ""

# الألوان للنصوص
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# التحقق من وجود Composer
if ! command -v composer &> /dev/null; then
    echo -e "${RED}[خطأ] Composer غير مثبت أو غير موجود في PATH${NC}"
    echo "يرجى تثبيت Composer من: https://getcomposer.org/"
    exit 1
fi

# التحقق من وجود PHP
if ! command -v php &> /dev/null; then
    echo -e "${RED}[خطأ] PHP غير مثبت أو غير موجود في PATH${NC}"
    echo "يرجى تثبيت PHP من: https://www.php.net/"
    exit 1
fi

# التحقق من وجود Python
if ! command -v python3 &> /dev/null && ! command -v python &> /dev/null; then
    echo -e "${RED}[خطأ] Python غير مثبت أو غير موجود في PATH${NC}"
    echo "يرجى تثبيت Python من: https://www.python.org/"
    exit 1
fi

# التحقق من وجود Node.js
if ! command -v node &> /dev/null; then
    echo -e "${YELLOW}[تحذير] Node.js غير مثبت أو غير موجود في PATH${NC}"
    echo "قد تحتاج Node.js لتشغيل Frontend Assets"
fi

echo ""
echo "[1/6] التحقق من وجود ملف .env..."
if [ ! -f .env ]; then
    echo "ملف .env غير موجود. يتم نسخ .env.example..."
    if [ -f .env.example ]; then
        cp .env.example .env
        echo "تم نسخ .env.example إلى .env"
    else
        echo -e "${RED}[خطأ] ملف .env.example غير موجود!${NC}"
        exit 1
    fi
else
    echo -e "${GREEN}ملف .env موجود ✓${NC}"
fi

echo ""
echo "[2/6] تثبيت حزم PHP (Composer)..."
if [ ! -d "vendor" ]; then
    composer install
    if [ $? -ne 0 ]; then
        echo -e "${RED}[خطأ] فشل تثبيت حزم Composer${NC}"
        exit 1
    fi
else
    echo -e "${GREEN}حزم PHP مثبتة بالفعل ✓${NC}"
fi

echo ""
echo "[3/6] التحقق من مفتاح التطبيق..."
php artisan key:generate --show > /dev/null 2>&1
if [ $? -ne 0 ]; then
    echo "يتم توليد مفتاح التطبيق..."
    php artisan key:generate
fi

echo ""
echo "[4/6] تثبيت حزم JavaScript (NPM)..."
if [ -f "package.json" ]; then
    if [ ! -d "node_modules" ]; then
        npm install
        if [ $? -ne 0 ]; then
            echo -e "${RED}[خطأ] فشل تثبيت حزم NPM${NC}"
            exit 1
        fi
    else
        echo -e "${GREEN}حزم JavaScript مثبتة بالفعل ✓${NC}"
    fi
    
    echo "بناء Assets..."
    npm run build
    if [ $? -ne 0 ]; then
        echo -e "${YELLOW}[تحذير] فشل بناء Assets. قد تحتاج إلى تشغيل npm run build يدوياً${NC}"
    fi
else
    echo "ملف package.json غير موجود. يتم تخطي هذا الخطوة."
fi

echo ""
echo "[5/6] التحقق من تثبيت حزم Python..."
cd python_ai_service
if [ ! -d "__pycache__" ]; then
    echo "تثبيت حزم Python..."
    if command -v pip3 &> /dev/null; then
        pip3 install -r requirements.txt
    else
        pip install -r requirements.txt
    fi
    if [ $? -ne 0 ]; then
        echo -e "${RED}[خطأ] فشل تثبيت حزم Python${NC}"
        cd ..
        exit 1
    fi
else
    echo -e "${GREEN}حزم Python مثبتة بالفعل ✓${NC}"
fi
cd ..

echo ""
echo "[6/6] التحقق من قاعدة البيانات..."
echo "يرجى التأكد من:"
echo "  1. MySQL/MariaDB يعمل"
echo "  2. قاعدة البيانات موجودة في ملف .env"
echo "  3. تم تشغيل Migrations (php artisan migrate)"
echo ""
read -p "هل تريد تشغيل Migrations الآن؟ (y/n): " run_migrations
if [ "$run_migrations" = "y" ] || [ "$run_migrations" = "Y" ]; then
    echo "تشغيل Migrations..."
    php artisan migrate
    if [ $? -ne 0 ]; then
        echo -e "${YELLOW}[تحذير] فشل تشغيل Migrations. تأكد من إعدادات قاعدة البيانات في ملف .env${NC}"
    fi
fi

echo ""
echo "========================================"
echo "   بدء تشغيل الخدمات"
echo "========================================"
echo ""

# إيقاف أي عمليات سابقة على المنافذ 8000 و 8001
echo "إيقاف العمليات القديمة على المنافذ 8000 و 8001..."
lsof -ti:8000 | xargs kill -9 2>/dev/null
lsof -ti:8001 | xargs kill -9 2>/dev/null
sleep 2

echo ""
echo "[1/2] تشغيل خدمة Python AI على المنفذ 8001..."
cd python_ai_service
if command -v python3 &> /dev/null; then
    python3 main.py > /dev/null 2>&1 &
else
    python main.py > /dev/null 2>&1 &
fi
AI_PID=$!
cd ..
sleep 5

# التحقق من خدمة AI
echo "التحقق من خدمة AI..."
sleep 3
if curl -s http://localhost:8001/docs > /dev/null 2>&1; then
    echo -e "${GREEN}✓ خدمة AI تعمل بنجاح${NC}"
else
    echo -e "${YELLOW}⚠ تحذير: خدمة AI قد تحتاج وقت إضافي للبدء${NC}"
fi

echo ""
echo "[2/2] تشغيل Laravel على المنفذ 8000..."
echo ""
echo "========================================"
echo "   المشروع يعمل الآن!"
echo "========================================"
echo ""
echo "يمكنك الوصول إلى:"
echo "  - التطبيق الرئيسي: http://127.0.0.1:8000"
echo "  - صفحة تسجيل الدخول: http://127.0.0.1:8000/login"
echo "  - وثائق AI API: http://localhost:8001/docs"
echo ""
echo "ملاحظات:"
echo "  - لإيقاف الخدمات، اضغط Ctrl+C"
echo "  - AI Service PID: $AI_PID"
echo ""
echo "========================================"
echo ""

# وظيفة لإيقاف الخدمات عند الخروج
cleanup() {
    echo ""
    echo "إيقاف الخدمات..."
    kill $AI_PID 2>/dev/null
    lsof -ti:8000 | xargs kill -9 2>/dev/null
    lsof -ti:8001 | xargs kill -9 2>/dev/null
    echo "تم إيقاف جميع الخدمات."
    exit 0
}

# التقاط إشارة الخروج
trap cleanup SIGINT SIGTERM

# تشغيل Laravel
php artisan serve --host=127.0.0.1 --port=8000
