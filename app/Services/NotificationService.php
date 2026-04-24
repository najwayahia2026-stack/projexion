<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * خدمة إدارة الإشعارات
 * مسؤولة عن إرسال التنبيهات للمستخدمين، المشرفين، وأصحاب المشاريع
 */
class NotificationService
{
    /**
     * إنشاء إشعار فردي لمستخدم محدد
     * * @param User $user المستخدم المستلم للإشعار
     * @param string $type نوع الإشعار (للتصنيف والفلترة)
     * @param Model $notifiable النموذج المرتبط بالإشعار (مشروع، جزئية، إلخ)
     * @param string $title عنوان الإشعار
     * @param string $message نص الإشعار
     * @param array $data بيانات إضافية بصيغة مصفوفة يتم تخزينها كـ JSON
     */
    public function create(User $user, string $type, Model $notifiable, string $title, string $message, array $data = []): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'notifiable_type' => get_class($notifiable), // جلب اسم الكلاس للموديل المرتبط (Polymorphic)
            'notifiable_id' => $notifiable->id,         // جلب معرف الموديل المرتبط
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'read' => false, // الإشعار غير مقروء افتراضياً عند الإنشاء
        ]);
    }

    /**
     * إرسال إشعار لمجموعة من المستخدمين دفعة واحدة
     * * @param array $users مصفوفة من كائنات المستخدمين
     */
    public function createForUsers(array $users, string $type, Model $notifiable, string $title, string $message, array $data = []): void
    {
        foreach ($users as $user) {
            // التأكد من أن الكائن ينتمي لكلاس المستخدمين قبل الإرسال
            if ($user instanceof User) {
                $this->create($user, $type, $notifiable, $title, $message, $data);
            }
        }
    }

    /**
     * إرسال تنبيه لصاحب المشروع عند تغيير الحالة (قبول أو رفض)
     * * @param mixed $project كائن المشروع
     * @param string $status الحالة الجديدة (approved/rejected)
     * @param User $reviewer المستخدم الذي قام بالمراجعة
     */
    public function notifyProjectStatusChange($project, string $status, User $reviewer): void
    {
        $owner = $project->owner;
        // التحقق من وجود صاحب للمشروع لتجنب الأخطاء البرمجية
        if (!$owner) {
            return;
        }

        // تحديد العنوان والرسالة بناءً على الحالة باللغة العربية
        $title = $status === 'approved' ? 'تم قبول المشروع' : 'تم رفض المشروع';
        $message = $status === 'approved' 
            ? "تم قبول مشروعك '{$project->title}' من قبل {$reviewer->name}"
            : "تم رفض مشروعك '{$project->title}' من قبل {$reviewer->name}";

        $this->create($owner, 'project_' . $status, $project, $title, $message, [
            'project_id' => $project->id,
            'reviewer_id' => $reviewer->id,
        ]);
    }

    /**
     * إرسال تنبيه لصاحب المشروع عند قبول أو رفض "جزئية" معينة (Section)
     */
    public function notifySectionStatusChange($section, string $status, User $reviewer): void
    {
        $project = $section->project;
        $owner = $project->owner;
        
        if (!$owner) {
            return;
        }

        $title = $status === 'approved' ? 'تم قبول جزئية المشروع' : 'تم رفض جزئية المشروع';
        $message = $status === 'approved'
            ? "تم قبول جزئية '{$section->name}' من مشروعك '{$project->title}' من قبل {$reviewer->name}"
            : "تم رفض جزئية '{$section->name}' من مشروعك '{$project->title}' من قبل {$reviewer->name}";

        $this->create($owner, 'section_' . $status, $section, $title, $message, [
            'section_id' => $section->id,
            'project_id' => $project->id,
            'reviewer_id' => $reviewer->id,
        ]);
    }

    /**
     * إشعار المشرف بوجود مشروع جديد تم تسليمه ليقوم بمراجعته
     */
    public function notifyNewProjectSubmission($project): void
    {
        // الوصول للمشرف عبر علاقة المشروع بالمجموعة
        $supervisor = $project->group->supervisor;
        if (!$supervisor) {
            return;
        }

        $title = 'مشروع جديد تم إرساله';
        $message = "تم إرسال مشروع جديد '{$project->title}' من قبل الطلاب";

        $this->create($supervisor, 'new_project_submission', $project, $title, $message, [
            'project_id' => $project->id,
        ]);
    }

    /**
     * إرسال إشعارات مخصصة (يدوية) لمجموعة مستخدمين
     * * @param User $sender المستخدم المرسل
     * @param array $recipients مصفوفة تحتوي كائنات مستخدمين أو أرقام تعريفية (IDs)
     */
    public function sendCustomNotification(User $sender, array $recipients, string $title, string $message, array $data = []): void
    {
        foreach ($recipients as $recipient) {
            $user = $recipient instanceof User ? $recipient : null;
            
            // إذا كان المعطى ID رقمي، نقوم بجلب المستخدم من قاعدة البيانات
            if (!$user && is_numeric($recipient)) {
                $user = User::find($recipient);
            }
            
            if ($user) {
                // في الإشعارات المخصصة، نعتبر المرسل هو الـ "notifiable" لسهولة الوصول لبياناته
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'custom',
                    'notifiable_type' => get_class($sender),
                    'notifiable_id' => $sender->id,
                    'title' => $title,
                    'message' => $message,
                    'data' => array_merge($data, ['sender_id' => $sender->id]),
                    'read' => false,
                ]);
            }
        }
    }

    /**
     * إخطار المشرف باقتراب الموعد النهائي لتسليم مرحلة/جزئية معينة
     * * @param mixed $phase كائن المرحلة/الجزئية
     * @param int $daysRemaining الأيام المتبقية
     */
    public function notifyPhaseDeadlineApproaching($phase, int $daysRemaining): void
    {
        $project = $phase->project;
        if (!$project || !$project->group) {
            return;
        }
        
        $supervisor = $project->group->supervisor;
        if (!$supervisor) {
            return;
        }
        
        $title = 'تنبيه: اقتراب موعد تسليم جزئية';
        $message = "تنتهي مدة تسليم الجزئية '{$phase->title}' من المشروع '{$project->title}' خلال {$daysRemaining} يوم/أيام. تاريخ التسليم: {$phase->delivery_date->format('Y-m-d')}";
        
        $this->create($supervisor, 'phase_deadline_approaching', $phase, $title, $message, [
            'phase_id' => $phase->id,
            'project_id' => $project->id,
            'days_remaining' => $daysRemaining,
            'delivery_date' => $phase->delivery_date->format('Y-m-d'),
        ]);
        
        // تحديث تاريخ آخر إشعار تم إرساله لمنع تكرار الإرسال في نفس اليوم
        $phase->update(['notification_sent_at' => now()]);
    }

    /**
     * فحص المواعيد القادمة وإرسال التنبيهات (تعمل عبر الجدولة التلقائية/Cron Job)
     */
    public function checkAndNotifyUpcomingDeadlines(): void
    {
        // إعداد أيام التنبيه (مثلاً تنبيه قبل 3 أيام وتنبيه قبل يوم واحد)
        $warningDays = [3, 1]; 
        
        foreach ($warningDays as $days) {
            // حساب تاريخ الموعد الذي نبحث عنه
            $deadlineDate = now()->addDays($days)->format('Y-m-d');
            
            // جلب المراحل التي تنتهي في ذلك التاريخ ولم يتم رفض مشاريعها
            $phases = \App\Models\ProjectPhase::where('delivery_date', $deadlineDate)
                ->whereNotNull('delivery_date')
                ->whereHas('project', function($query) {
                    $query->where('status', '!=', 'rejected');
                })
                ->with('project.group.supervisor')
                ->get();
            
            foreach ($phases as $phase) {
                // التحقق من تاريخ آخر إشعار تم إرساله
                $lastNotificationDate = $phase->notification_sent_at ? 
                    \Carbon\Carbon::parse($phase->notification_sent_at)->format('Y-m-d') : null;
                
                // التأكد من أننا لم نرسل إشعاراً لهذا اليوم مسبقاً
                if ($lastNotificationDate !== now()->format('Y-m-d')) {
                    $this->notifyPhaseDeadlineApproaching($phase, $days);
                }
            }
        }
    }
}