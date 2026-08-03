<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $stats = (object)[
            'total'      => Notification::count(),
            'emergency'  => Notification::where('type', 'emergency')->count(),
            'government' => Notification::where('type', 'government')->count(),
            'utility'    => Notification::where('type', 'utility')->count(),
            'community'  => Notification::where('type', 'community')->count(),
        ];

        $notifications = Notification::latest()
            ->paginate(10);

        $notifications->getCollection()->transform(function ($item) {

            $item->status = $item->sent_at ? 'sent' : 'draft';

            $item->read_stats = (object)[
                'total' => 0,
                'read' => 0,
                'unread' => 0,
                'rate' => 0,
            ];

            return $item;
        });

        return view('frontend.notifications.index', compact(
            'stats',
            'notifications'
        ));
    }
}