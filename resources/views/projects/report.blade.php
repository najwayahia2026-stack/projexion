<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>{{ $project->title }}</title>
    <style>
        @page { size: A4; margin: 2cm; }
        body { 
            font-family: 'Simplified Arabic', 'Traditional Arabic', serif; 
            line-height: 1.5; 
            color: #1a1a1a; 
            margin: 0; padding: 0;
            text-align: right; 
        }

        /* تنسيق الترويسة العلوية */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .section-title { 
            font-size: 18pt; 
            font-weight: bold; 
            color: #1e40af; 
            margin-top: 25px; 
            margin-bottom: 10px; 
            border-bottom: 2px solid #1e40af;
            padding-bottom: 3px;
            display: inline-block; 
        }
        
        .content { 
            text-align: justify; 
            color: #374151; 
            font-size: 14pt; 
            margin-bottom: 15px; 
        }
        
        .supervisor-section { margin-top: 50px; }
        .supervisor-name { font-size: 16pt; font-weight: bold; }
    </style>
</head>
<body onload="window.print()">

    <div class="report-header">
        <img src="{{ asset('7.png') }}" width="80" alt="Logo">
        <div style="font-size: 12pt; color: #6b7280;">تاريخ التقرير: {{ date('Y-m-d') }}</div>
    </div>

    <h1 style="font-size: 24pt; font-weight: 800; margin-bottom: 30px; color: #111827; text-align: center;">
        {{ $project->title }}
    </h1>

    <div class="section-title">وصف المشروع</div>
    <div class="content">{{ $project->description }}</div>

    <div class="section-title">أهداف المشروع</div>
    <div class="content">{{ $project->objectives }}</div>

    <div class="section-title">التقنيات المستخدمة</div>
    <div class="content">{{ $project->technologies }}</div>

    <div class="section-title">نسبة التشابه</div>
    <div class="content" style="font-weight: bold; font-size: 16pt;">{{ $project->similarity_score ?? '0.0' }}%</div>

    <div class="supervisor-section">
        <div style="font-size: 14pt; margin-bottom: 5px;">اعتماد مشرف المشروع:</div>
        <div class="supervisor-name">أ. {{ $project->supervisor->name ?? '....................' }}</div>
    </div>

</body>
</html>