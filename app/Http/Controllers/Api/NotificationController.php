<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->wardNotifications()
            ->get()
            ->map(fn($n) => [
                'id'       => $n->id,
                'title'    => $n->title,
                'content'  => $n->content,
                'type'     => $n->type,
                'is_read'  => (bool) $n->pivot->is_read,
                'is_acknowledged'  => (bool) $n->pivot->is_acknowledged,
                'acknowledged_at'  => $n->pivot->acknowledged_at,
                'sent_at'  => $n->sent_at,
                'created_at' => $n->created_at,
            ]);

        return response()->json(['success' => true, 'data' => $notifications]);
    }

    public function read(Request $request, $id)
    {
        $request->user()->wardNotifications()->updateExistingPivot($id, [
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function acknowledge(Request $request, $id)
    {
        $request->user()->wardNotifications()->updateExistingPivot($id, [
            'is_read' => true,
            'read_at' => now(),
            'is_acknowledged' => true,
            'acknowledged_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function readAll(Request $request)
    {
        $request->user()->wardNotifications()->updateExistingPivot(
            $request->user()->notifications()->pluck('notifications.id'),
            ['is_read' => true, 'read_at' => now()]
        );

        return response()->json(['success' => true]);
    }
}
