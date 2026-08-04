<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Carbon;

class CitizenController extends Controller
{
    public function index()
    {
        $citizens = User::query()
            ->where('role', 'citizen')
            ->withCount([
                'profiles',
                'appointments',
                'reports',
                'partyVoteResponses',
            ])
            ->latest()
            ->paginate(10);

        $citizens->getCollection()->transform(function ($user) {
            $user->activity = (object)[
                'profiles' => $user->profiles_count,
                'appointments' => $user->appointments_count,
                'reports' => $user->reports_count,
                'votes' => $user->party_vote_responses_count,
            ];

            return $user;
        });

        $stats = (object)[
            'total' => User::where('role', 'citizen')->count(),
            'verified' => User::where('role', 'citizen')
                ->where('is_verified', true)
                ->count(),
            'unverified' => User::where('role', 'citizen')
                ->where('is_verified', false)
                ->count(),
            'active_30d' => User::where('role', 'citizen')
                ->whereNotNull('last_login_at')
                ->where('last_login_at', '>=', Carbon::now()->subDays(30))
                ->count(),
        ];

        return view('frontend.citizens.index', compact(
            'citizens',
            'stats'
        ));
    }

    public function export()
    {
        $citizens = User::where('role', 'citizen')->latest()->get();

        $filename = 'danh_sach_cong_dan_' . date('Y_m_d_His') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($citizens) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['ID', 'Họ tên', 'Số điện thoại', 'Zalo ID', 'Mã Định Danh/CCCD', 'Địa chỉ', 'Trạng thái định danh', 'Đăng nhập gần nhất']);

            foreach ($citizens as $c) {
                fputcsv($file, [
                    $c->id,
                    $c->full_name,
                    $c->phone,
                    $c->zalo_id,
                    $c->citizen_code,
                    $c->address,
                    $c->is_verified ? 'Đã định danh' : 'Chưa định danh',
                    $c->last_login_at ? $c->last_login_at->format('d/m/Y H:i') : 'Chưa đăng nhập',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}