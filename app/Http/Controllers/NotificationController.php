<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Send notification to users
     */
    public function send(Request $request)
    {
        $user = Auth::user();
        
        // Only admin, department_admin, and supervisor can send notifications
        if (!$user->hasRole('admin') && !$user->hasRole('department_admin') && !$user->hasRole('supervisor')) {
            return back()->with('error', 'ليس لديك صلاحية لإرسال الإشعارات');
        }

        // If recipient_id is provided without recipient_type, treat it as specific user
        if ($request->has('recipient_id') && !$request->has('recipient_type')) {
            $request->merge(['recipient_type' => 'specific']);
        }

        $validated = $request->validate([
            'recipient_type' => ['required', 'in:all,specific,role'],
            'recipient_id' => ['nullable', 'required_if:recipient_type,specific', 'exists:users,id'],
            'role' => ['nullable', 'required_if:recipient_type,role', 'string'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar', 'max:10240'], // 10MB max
        ], [
            'recipient_type.required' => 'يرجى اختيار نوع المستلم',
            'recipient_type.in' => 'نوع المستلم غير صحيح',
            'recipient_id.required_if' => 'يرجى اختيار المستخدم',
            'recipient_id.exists' => 'المستخدم المحدد غير موجود',
            'role.required_if' => 'يرجى اختيار الدور',
            'title.required' => 'العنوان مطلوب',
            'title.max' => 'العنوان يجب أن يكون أقل من 255 حرف',
            'message.required' => 'الرسالة مطلوبة',
            'attachment.file' => 'الملف المرفق غير صحيح',
            'attachment.mimes' => 'نوع الملف غير مدعوم. الأنواع المدعومة: PDF, DOC, DOCX, JPG, PNG, ZIP, RAR',
            'attachment.max' => 'حجم الملف يجب أن يكون أقل من 10 ميجابايت',
        ]);

        try {
            $recipients = [];

            if ($validated['recipient_type'] === 'all') {
                // Send to all users
                $recipients = User::all();
            } elseif ($validated['recipient_type'] === 'specific') {
                // Send to specific user
                if (empty($validated['recipient_id'])) {
                    return back()->withErrors(['recipient_id' => 'يرجى اختيار المستخدم'])->withInput();
                }
                $recipient = User::find($validated['recipient_id']);
                if (!$recipient) {
                    return back()->withErrors(['recipient_id' => 'المستخدم المحدد غير موجود'])->withInput();
                }
                $recipients = [$recipient];
            } elseif ($validated['recipient_type'] === 'role') {
                // Send to users with specific role
                if (empty($validated['role'])) {
                    return back()->withErrors(['role' => 'يرجى اختيار الدور'])->withInput();
                }
                $recipients = User::role($validated['role'])->get();
                
                if ($recipients->isEmpty()) {
                    return back()->with('error', 'لا يوجد مستخدمين بهذا الدور')->withInput();
                }
            }

            if (empty($recipients)) {
                return back()->with('error', 'لم يتم العثور على مستلمين للإشعار')->withInput();
            }

            // Handle file attachment if provided
            $attachmentData = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('notification-attachments', $fileName, 'public');
                
                $attachmentData = [
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];
            }
            
            // Send notification
            // Convert collection to array of User instances
            $recipientsArray = is_array($recipients) ? $recipients : $recipients->all();
            
            $this->notificationService->sendCustomNotification(
                $user,
                $recipientsArray,
                $validated['title'],
                $validated['message'],
                $attachmentData ? ['attachment' => $attachmentData] : []
            );

            return back()->with('success', 'تم إرسال الإشعار بنجاح إلى ' . count($recipients) . ' مستخدم');
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إرسال الإشعار: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Get notifications list for dropdown (AJAX)
     */
    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'notifications' => $notifications->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'read' => $notification->read,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'type' => $notification->type,
                ];
            })
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'تم تعليم الإشعار كمقروء بنجاح');
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        $updatedCount = $user->notifications()->unread()->update([
            'read' => true,
            'read_at' => now(),
        ]);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'تم تعليم جميع الإشعارات كمقروءة بنجاح');
    }

    public function unreadCount(Request $request)
    {
        $user = Auth::user();
        $count = $user->unreadNotifications()->count();

        // If it's an AJAX request, return JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['count' => $count]);
        }

        // If it's a direct browser request, redirect to dashboard
        return redirect()->route('dashboard');
    }

    /**
     * تحميل الملف المرفق بالإشعار
     */
    public function downloadAttachment(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$notification->data || !isset($notification->data['attachment']['file_path'])) {
            abort(404, 'لا يوجد ملف مرفق بهذا الإشعار');
        }

        $attachment = $notification->data['attachment'];
        $filePath = $attachment['file_path'];

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'الملف غير موجود');
        }

        return Storage::disk('public')->download(
            $filePath,
            $attachment['file_name'] ?? basename($filePath),
            ['Content-Type' => $attachment['mime_type'] ?? 'application/octet-stream']
        );
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return redirect()->back()->with('success', 'تم حذف الإشعار بنجاح');
    }
}