<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير إشراف: {{ $supervisor->name }}</title>
    <style>
        @page { size: A4; margin: 1.5cm; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            direction: rtl; line-height: 1.6; color: #1a1a1a; padding: 20px;
        }
        .report-header {
            text-align: center;
            border-bottom: 3px solid #2563eb; /* تغيير اللون للأزرق */
            margin-bottom: 30px;
            padding-bottom: 20px;
        }
        .report-header h1 { font-size: 24pt; color: #1e40af; margin: 10px 0; }
        .info-bar {
            display: flex;
            justify-content: space-between;
            background: #eff6ff; /* خلفية زرقاء فاتحة */
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: bold;
            border: 1px solid #bfdbfe;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 12px; text-align: center; vertical-align: middle; }
        th { background-color: #2563eb; color: white; font-size: 13pt; } /* رأس الجدول أزرق */
        tr:nth-child(even) { background-color: #f8fafc; }
        .student-list { list-style: none; padding: 0; margin: 0; text-align: right; }
        .student-list li { border-bottom: 1px solid #eee; padding: 4px 0; }
        .student-list li:last-child { border-bottom: none; }
        .footer-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            padding: 0 50px;
        }
        .signature-box { text-align: center; width: 200px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    <div class="report-header">
        <h1>تقرير مجموعات الإشراف الأكاديمي</h1>
        <p>نظام إدارة مشاريع التخرج - ProjexiOn</p>
    </div>

    <div class="info-bar">
        <span>المشرف الأكاديمي: أ. {{ $supervisor->name }}</span>
        <span>تاريخ التقرير: {{ date('Y-m-d') }}</span>
    </div>

    <table>
    <thead>
        <tr>
            <th style="width: 35%;">عنوان المشروع</th>
            <th style="width: 45%;">أعضاء المجموعة</th>
            <th style="width: 20%;">الإحصائيات</th>
        </tr>
    </thead>
    <tbody>
        @forelse($groups as $group)
            @php 
                $project = $group->projects->first(); 
            @endphp
            <tr>
                <td style="text-align: right; font-weight: 500;">
                    {{ $project->title ?? 'لم يتم تسجيل عنوان مشروع' }}
                </td>
                
                <td>
                    <ul class="student-list">
                        @forelse($group->students as $student)
                            <li>• {{ $student->name }}</li>
                        @empty
                            <li style="color: #999;">لا يوجد طلاب مسجلين</li>
                        @endforelse
                    </ul>
                </td>
                
                <td>
                    @if($project)
                        <div style="margin-bottom: 5px;">
                            <span style="color: {{ ($project->similarity_score ?? 0) > 25 ? '#dc2626' : '#16a34a' }}">
                                التشابه: {{ number_format($project->similarity_score ?? 0, 1) }}%
                            </span>
                        </div>
                        <div style="font-weight: bold; color: #2563eb;">
                            الإنجاز: {{ $project->report_progress ?? 0 }}%
                        </div>
                    @else
                        <span style="color: #94a3b8;">---</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" style="color: #dc2626; padding: 20px; font-weight: bold;">
                    لا توجد أي مجموعات مسندة لهذا المشرف حالياً.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

    <div class="footer-section">
        <div class="signature-box">
            <p>توقيع المشرف الأكاديمي</p>
            <br><br>
            <p>....................................</p>
        </div>
        <div class="signature-box">
            <p>ختم واعتماد القسم</p>
            <br><br>
            <p>....................................</p>
        </div>
    </div>

</body>
</html>