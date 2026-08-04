<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\Report;
use App\Models\Appointment;
use App\Models\Survey;
use App\Models\Officer;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        $kpi = (object)[
            'total_citizens'     => User::where('role', 'citizen')->count(),
            'total_officers'     => Officer::count() ?: User::where('role', 'officer')->count(),
            'processing_reports' => Report::whereIn('status', ['pending', 'processing'])->count(),
            'total_surveys'      => Survey::count(),
            'total_profiles'     => Profile::count(),
            'total_appointments' => Appointment::count(),
        ];

        $recentItems = Report::with('user')
            ->latest()
            ->take(10)
            ->get();

        $monthlyData = [
            'labels' => ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
            'logins' => [1200, 1350, 1250, 1420, 1500, max(1680, User::count() * 10)],
            'reports' => [300, 420, 380, 500, 480, max(610, Report::count())],
        ];

        $reportStatusDist = [
            'resolved'   => Report::where('status', 'resolved')->count(),
            'processing' => Report::where('status', 'processing')->count(),
            'pending'    => Report::where('status', 'pending')->count(),
            'rejected'   => Report::where('status', 'rejected')->count(),
        ];

        return view('frontend.dashboard.index', compact(
            'kpi',
            'recentItems',
            'monthlyData',
            'reportStatusDist'
        ));
    }

    public function export()
    {
        $filename = 'bao_cao_tong_quan_he_thong_' . date('Y_m_d_His') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['BÁO CÁO TỔNG QUAN CHÍNH QUYỀN SỐ & PHẢN ÁNH HIỆN TRƯỜNG']);
            fputcsv($file, ['Thời gian xuất báo cáo', date('d/m/Y H:i:s')]);
            fputcsv($file, []);

            fputcsv($file, ['CHỈ SỐ KPI TỔNG QUAN']);
            fputcsv($file, ['Chỉ số', 'Số lượng']);
            fputcsv($file, ['Tổng số Công dân', User::where('role', 'citizen')->count()]);
            fputcsv($file, ['Công dân đã định danh', User::where('role', 'citizen')->where('is_verified', true)->count()]);
            fputcsv($file, ['Tổng số Cán bộ / Tổ trưởng', Officer::count() ?: User::where('role', 'officer')->count()]);
            fputcsv($file, ['Tổng hồ sơ dân cư / Sổ hộ khẩu', Profile::count()]);
            fputcsv($file, ['Phản ánh hiện trường (PAHT) đang xử lý', Report::whereIn('status', ['pending', 'processing'])->count()]);
            fputcsv($file, ['Phản ánh hiện trường đã giải quyết', Report::where('status', 'resolved')->count()]);
            fputcsv($file, ['Lịch hẹn dịch vụ công đã đăng ký', Appointment::count()]);
            fputcsv($file, ['Khảo sát & Trưng cầu ý dân', Survey::count()]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}