<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $notifications = $query->latest()->paginate(10)->withQueryString();

        $stats = (object)[
            'total'      => Notification::count(),
            'emergency'  => Notification::where('type', 'emergency')->count(),
            'government' => Notification::where('type', 'government')->count(),
            'utility'    => Notification::where('type', 'utility')->count(),
            'community'  => Notification::where('type', 'community')->count(),
        ];

        return view('frontend.notifications.index', compact('stats', 'notifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'type'    => 'required|in:emergency,government,utility,community',
            'content' => 'required|string',
        ], [
            'title.required'   => 'Vui lòng nhập tiêu đề thông báo',
            'type.required'    => 'Vui lòng chọn phân loại thông báo',
            'content.required' => 'Vui lòng nhập nội dung thông báo',
        ]);

        Notification::create([
            'title'   => $request->title,
            'type'    => $request->type,
            'content' => $request->content,
            'sent_at' => $request->has('send_now') ? now() : ($request->has('is_sent') ? now() : now()),
        ]);

        return redirect()->route('notifications')->with('success', 'Tạo và phát hành thông báo mới thành công!');
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Xóa thông báo thành công!');
    }
}