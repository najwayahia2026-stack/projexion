<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير فحص التشابه - {{ $project->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', 'Tajawal', 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            unicode-bidi: embed;
            color: #1b1b18;
            line-height: 1.6;
            background: #fff;
        }
        
        body, .header, .project-info, .similarity-check, .section-content, .info-row, .similar-projects-list li, .footer {
            direction: rtl;
            text-align: right;
            unicode-bidi: embed;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 25px;
            border-bottom: 3px solid #1e40af;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .header .subtitle {
            font-size: 16px;
            opacity: 0.95;
        }
        
        .project-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-right: 4px solid #1e40af;
        }
        
        .project-info h2 {
            color: #1e40af;
            font-size: 20px;
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .info-row {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }
        
        .info-label {
            font-weight: 600;
            color: #4b5563;
            min-width: 120px;
        }
        
        .info-value {
            color: #1b1b18;
            flex: 1;
        }
        
        .similarity-check {
            background: #fff;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .similarity-check-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .similarity-percentage {
            font-size: 32px;
            font-weight: 700;
            color: #1e40af;
        }
        
        .similarity-date {
            color: #6b7280;
            font-size: 14px;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .section-content {
            color: #4b5563;
            line-height: 1.8;
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            white-space: pre-line;
        }
        
        .similar-projects-list {
            list-style: none;
            padding: 0;
        }
        
        .similar-projects-list li {
            background: #f3f4f6;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 6px;
            border-right: 3px solid #3b82f6;
        }
        
        .similar-projects-list li strong {
            color: #1e40af;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-high {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-medium {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-low {
            background: #dbeafe;
            color: #1e40af;
        }
        
        @page {
            margin: 20mm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 تقرير فحص التشابه</h1>
        <div class="subtitle">نظام SmartGrad - تقارير فحص التشابه للمشاريع</div>
    </div>

    <div class="project-info">
        <h2>معلومات المشروع</h2>
        <div class="info-row">
            <span class="info-label">اسم المشروع:</span>
            <span class="info-value">{{ $project->title }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">المجموعة:</span>
            <span class="info-value">{{ $project->group->name ?? 'غير محدد' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">الطلاب:</span>
            <span class="info-value">
                @if($project->group && $project->group->students)
                    {{ $project->group->students->pluck('name')->join('، ') }}
                @else
                    غير محدد
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">تاريخ الإنشاء:</span>
            <span class="info-value">{{ $project->created_at->format('Y-m-d H:i') }}</span>
        </div>
        @if($project->similarity_score)
        <div class="info-row">
            <span class="info-label">نسبة التشابه الإجمالية:</span>
            <span class="info-value">
                <strong style="color: #1e40af; font-size: 18px;">{{ number_format($project->similarity_score, 2) }}%</strong>
            </span>
        </div>
        @endif
    </div>

    @if($similarityChecks->count() > 0)
        <h2 style="color: #1e40af; font-size: 22px; margin-bottom: 20px; font-weight: 700;">نتائج فحص التشابه</h2>
        
        @foreach($similarityChecks as $check)
            <div class="similarity-check">
                <div class="similarity-check-header">
                    <div>
                        <div class="similarity-percentage">
                            {{ number_format($check->similarity_percentage, 2) }}%
                        </div>
                        @if($check->similarity_percentage >= 70)
                            <span class="badge badge-high">تشابه عالي</span>
                        @elseif($check->similarity_percentage >= 50)
                            <span class="badge badge-medium">تشابه متوسط</span>
                        @else
                            <span class="badge badge-low">تشابه منخفض</span>
                        @endif
                    </div>
                    <div class="similarity-date">
                        تاريخ الفحص: {{ $check->checked_at ? $check->checked_at->format('Y-m-d H:i') : 'غير محدد' }}
                    </div>
                </div>

                @if($check->source_comparison && !Auth::user()->hasRole('student'))
                    <div class="section">
                        <div class="section-title">📋 مصدر المقارنة</div>
                        <div class="section-content">{{ $check->source_comparison }}</div>
                    </div>
                @endif

                @if($check->similar_projects && count($check->similar_projects) > 0)
                    <div class="section">
                        <div class="section-title">🔍 المشاريع المشابهة</div>
                        <ul class="similar-projects-list">
                            @foreach($check->similar_projects as $similar)
                                <li>
                                    @if(Auth::user()->hasRole('student'))
                                        <strong>مشروع مشابه</strong>
                                    @else
                                        <strong>{{ $similar['title'] ?? 'مشروع غير محدد' }}</strong>
                                    @endif
                                    - نسبة التشابه: <strong>{{ number_format($similar['similarity'] ?? 0, 2) }}%</strong>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($check->details)
                    <div class="section">
                        <div class="section-title">📝 التفاصيل</div>
                        <div class="section-content">{{ $check->details }}</div>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <p style="font-size: 18px;">لا توجد تقارير فحص تشابه متاحة لهذا المشروع</p>
        </div>
    @endif

    <div class="footer">
        <p>تم إنشاء هذا التقرير تلقائياً بواسطة نظام SmartGrad</p>
        <p>تاريخ الإنشاء: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
