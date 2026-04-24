<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير إحصائيات مشاريع التخرج</title>
    <style>
        /* إعدادات الصفحة والخطوط */
        @page { size: A4; margin: 1.5cm; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 0; 
            direction: rtl; 
            background: white; 
            line-height: 1.6;
            color: #333;
        }

        /* رأس التقرير المطور */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            border-bottom: 3px solid #1e40af;
        }

        .logo-section {
            width: 150px; /* مساحة الشعار */
        }

        .logo-section img {
            max-width: 120px;
            height: auto;
        }

        .title-section {
            text-align: center;
            flex-grow: 1;
        }

        .title-section h1 {
            font-size: 24px;
            color: #1e40af;
            margin: 0;
        }

        .info-section {
            text-align: left;
            font-size: 12px;
            color: #666;
            width: 150px;
        }

        /* تنسيق الجدول الاحترافي */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        
        th, td { 
            padding: 12px 10px; 
            text-align: center; 
            border: 1px solid #d1d5db; 
            font-size: 13px; 
        }

        th { 
            background-color: #1e40af !important; 
            color: white !important; 
            font-weight: bold;
            -webkit-print-color-adjust: exact;
        }

        tbody tr:nth-child(even) { 
            background-color: #f8fafc; 
        }

        .badge { font-weight: bold; }
        .similarity-low { color: #059669; }
        .similarity-high { color: #dc2626; }

        /* أزرار التحكم */
        .no-print { 
            text-align: center; 
            padding: 15px; 
            background: #f1f5f9;
            border-bottom: 1px solid #e2e8f0;
        }
        .btn {
            padding: 8px 20px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            margin: 0 5px;
        }
        .btn-print { background: #1e40af; color: white; }
        .btn-close { background: #64748b; color: white; }

        @media print { 
            .no-print { display: none !important; }
            body { margin: 0; padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn btn-print" onclick="window.print()">تأكيد الطباعة</button>
        <button class="btn btn-close" onclick="window.close()">إغلاق النافذة</button>
    </div>

    <div class="report-wrapper">
        <div class="report-header">
            <div class="logo-section">
<img src="{{ asset('7.png')}}" width="80" alt="شعار الجامعة">            </div>

            <div class="title-section">
                <h1>تقرير إحصائيات مشاريع التخرج</h1>
                <p style="margin: 5px 0; color: #4b5563;">نظام إدارة المشاريع - ProjexiOn</p>
            </div>

            <div class="info-section">
                <p>التاريخ: {{ now()->format('Y-m-d') }}</p>
                <p>الوقت: {{ now()->format('H:i') }}</p>
            </div>
        </div>

        <div style="padding: 0 20px;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th style="text-align: right;">اسم المشروع</th>
                        <th>رمز المجموعة</th>
                        <th>نسبة التشابه</th>
                        <th>نسبة الإنجاز</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $index => $project)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="text-align: right; font-weight: 500;">{{ $project->title }}</td>
                        <td>#{{ $project->group_id }}</td>
                        <td class="badge {{ $project->similarity_score > 25 ? 'similarity-high' : 'similarity-low' }}">
                            {{ number_format($project->similarity_score, 1) }}%
                        </td>
                        <td style="font-weight: bold; color: #1e40af;">
                            {{ (int)$project->report_progress }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 50px; display: flex; justify-content: space-between; padding: 20px;">
                <div style="text-align: center;">
                    <p style="font-weight: bold; margin-bottom: 40px;">توقيع منسق المشاريع</p>
                    <p>..................................</p>
                </div>
                <div style="text-align: center;">
                    <p style="font-weight: bold; margin-bottom: 40px;">ختم القسم</p>
                    <p>..................................</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>