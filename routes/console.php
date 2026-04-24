<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\NotificationService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily check for upcoming phase deadlines
Schedule::call(function () {
    $notificationService = app(NotificationService::class);
    $notificationService->checkAndNotifyUpcomingDeadlines();
})->dailyAt('08:00')->name('check-phase-deadlines');

// Create test notification command
Artisan::command('notification:test {user_id?}', function ($userId = null) {
    
    if ($userId) {
        $user = User::find($userId);
        if (!$user) {
            $this->error("المستخدم غير موجود!");
            return;
        }
    } else {
        // Use first user as default
        $user = User::first();
        if (!$user) {
            $this->error("لا يوجد مستخدمين في قاعدة البيانات!");
            return;
        }
    }
    
    $notification = Notification::create([
        'user_id' => $user->id,
        'type' => 'test',
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'title' => 'إشعار تجريبي',
        'message' => 'هذا إشعار تجريبي لاختبار نظام الإشعارات. الوقت: ' . now()->format('Y-m-d H:i:s'),
        'read' => false,
        'data' => ['test' => true],
    ]);
    
    $this->info("✓ تم إنشاء إشعار تجريبي للمستخدم: {$user->name} (ID: {$user->id})");
    $this->info("  العنوان: {$notification->title}");
    $this->info("  الرسالة: {$notification->message}");
    $this->info("  عدد الإشعارات غير المقروءة: " . $user->unreadNotifications()->count());
})->purpose('إنشاء إشعار تجريبي لاختبار النظام');
