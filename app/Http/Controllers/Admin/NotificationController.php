<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::user()->id)->orderbyDesc('id')->paginate(10);
        $unreadNotificationsCount = Notification::where('user_id', Auth::user()->id)->unread()->get()->count();
        return view('admin.notifications', [
            'notifications' => $notifications,
            'unreadNotificationsCount' => $unreadNotificationsCount,
        ]);
    }

    public function view($id)
    {
        $notification = Notification::where('user_id', Auth::user()->id)->where('id', $id)->firstOrFail();
        $notification->update(['status' => 1]);
        return redirect($notification->link);
    }

    public function readAll()
    {
        $notifications = Notification::where('user_id', Auth::user()->id)->unread()->get();
        if ($notifications->count() >= 1) {
            foreach ($notifications as $notification) {
                $notification->update(['status' => 1]);
            }
            toastr()->success(admin_lang('All notifications marked as read successfully'));
        }
        return back();
    }

    public function destroyAll()
    {
        $notifications = Notification::where('user_id', Auth::user()->id)->read()->get();
        if ($notifications->count() >= 1) {
            foreach ($notifications as $notification) {
                $notification->delete();
            }
            toastr()->success(admin_lang('Deleted Successfully'));
        }
        return back();
    }
}
