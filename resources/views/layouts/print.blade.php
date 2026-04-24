<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>طباعة التقرير</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    </style>
</head>
<body class="bg-white p-10">
    <div class="no-print mb-5 text-center">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded shadow">تأكيد الطباعة</button>
        <button onclick="window.history.back()" class="bg-gray-500 text-white px-6 py-2 rounded shadow">رجوع</button>
    </div>
    
    @yield('content')

    <script>
        // اختيارياً: فتح نافذة الطباعة تلقائياً عند التحميل
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>