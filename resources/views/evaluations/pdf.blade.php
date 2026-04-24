<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير تقييم المشروع - {{ $project->title }}</title>
    <style>
        body {
            font-family: 'Cairo', 'Tajawal', 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            unicode-bidi: embed;
        }
        body, .header, .section, .info-row, table th, table td, .total-score {
            direction: rtl;
            text-align: right;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #1e40af;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .info-row {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        table th {
            background-color: #1e40af;
            color: white;
        }
        .total-score {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            margin-top: 15px;
            text-align: center;
            padding: 10px;
            background-color: #e0e7ff;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير تقييم مشروع التخرج</h1>
        <p>منصة ProjexiOn لإدارة مشاريع التخرج</p>
    </div>

    <div class="section">
        <div class="section-title">معلومات المشروع</div>
        <div class="info-row">
            <span class="info-label">عنوان المشروع:</span>
            <span>{{ $project->title }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">المجموعة:</span>
            <span>{{ $project->group->name ?? 'غير محدد' }} ({{ $project->group->code ?? '' }})</span>
        </div>
        <div class="info-row">
            <span class="info-label">المشرف:</span>
            <span>{{ $project->group->supervisor->name ?? 'غير محدد' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">الحالة:</span>
            <span>{{ $project->status }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">نسبة الإنجاز:</span>
            <span>{{ $project->progress_percentage }}%</span>
        </div>
    </div>

    @if($evaluations->count() > 0)
    <div class="section">
        <div class="section-title">التقييمات</div>
        <table>
            <thead>
                <tr>
                    <th>المقيّم</th>
                    <th>الاقتراح (20%)</th>
                    <th>تحقيق الأهداف (30%)</th>
                    <th>النهائي (40%)</th>
                    <th>التقييم العام (10%)</th>
                    <th>المجموع</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluations as $evaluation)
                <tr>
                    <td>{{ $evaluation->evaluator->name }} 
                        @if($evaluation->evaluation_type)
                            <br><small style="color: {{ $evaluation->evaluation_type === 'committee' ? '#1e40af' : '#059669' }};">
                                ({{ $evaluation->evaluation_type === 'committee' ? 'تقييم اللجنة' : 'تقييم المشرف' }})
                            </small>
                        @endif
                    </td>
                    <td>{{ $evaluation->proposal_score ?? '-' }}</td>
                    <td>{{ $evaluation->objectives_achievement ?? '-' }}</td>
                    <td>{{ $evaluation->final_score ?? '-' }}</td>
                    <td>{{ $evaluation->general_score ?? '-' }}</td>
                    <td><strong>{{ number_format($evaluation->total_score ?? 0, 2) }}</strong></td>
                    <td>{{ $evaluation->evaluated_at->format('Y-m-d') }}</td>
                </tr>
                @if($evaluation->comments)
                <tr>
                    <td colspan="7" style="background-color: #f9fafb;">
                        <strong>تعليقات:</strong> {{ $evaluation->comments }}
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>

        @php
            $averageScore = $evaluations->avg('total_score');
        @endphp
        <div class="total-score">
            المعدل العام: {{ number_format($averageScore, 2) }} / 100
        </div>
    </div>
    @else
    <div class="section">
        <p>لا توجد تقييمات متاحة لهذا المشروع.</p>
    </div>
    @endif

    <div class="section">
        <div class="section-title">وصف المشروع</div>
        <p>{{ $project->description }}</p>
    </div>

    <div class="section">
        <div class="section-title">أهداف المشروع</div>
        <p>{{ $project->objectives }}</p>
    </div>

    @if($project->technologies)
    <div class="section">
        <div class="section-title">التقنيات المستخدمة</div>
        <p>{{ $project->technologies }}</p>
    </div>
    @endif

    <div style="margin-top: 40px; text-align: center; color: #666; font-size: 12px;">
        <p>تم إنشاء هذا التقرير في: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
