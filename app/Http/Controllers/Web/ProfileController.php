<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = Profile::with(['user', 'officer'])->latest();

        if ($request->filled('household_type')) {
            $query->where('household_type', $request->household_type);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('household_code', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('full_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $profiles = $query->paginate(20)->withQueryString();

        $stats = (object)[
            'total'           => Profile::count(),
            'received'        => Profile::where('status', 'received')->count(),
            'processing'      => Profile::where('status', 'processing')->count(),
            'waiting'         => Profile::where('status', 'waiting')->count(),
            'completed'       => Profile::where('status', 'completed')->count(),
            'rejected'        => Profile::where('status', 'rejected')->count(),
            'poor_count'      => Profile::where('household_type', 'poor')->count(),
            'near_poor_count' => Profile::where('household_type', 'near_poor')->count(),
            'policy_count'    => Profile::where('household_type', 'policy')->count(),
            'birth_events'    => Profile::where('event_type', 'birth')->count(),
            'death_events'    => Profile::where('event_type', 'death')->count(),
        ];

        $statusClasses = [
            'received'   => 'profiles-badge-received',
            'processing' => 'profiles-badge-processing',
            'waiting'    => 'profiles-badge-waiting',
            'completed'  => 'profiles-badge-completed',
            'rejected'   => 'profiles-badge-rejected',
        ];

        $statusTexts = [
            'received'   => 'Đã tiếp nhận',
            'processing' => 'Đang xử lý',
            'waiting'    => 'Chờ bổ sung',
            'completed'  => 'Hoàn thành',
            'rejected'   => 'Từ chối',
        ];

        return view('frontend.profiles.index', compact('profiles', 'stats', 'statusClasses', 'statusTexts'));
    }

    public function export()
    {
        $profiles = Profile::with(['user', 'officer'])->latest()->get();

        $filename = 'danh_sach_ho_khau_dan_cu_' . date('Y_m_d_His') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($profiles) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Mã Hồ sơ', 'Mã Hộ khẩu', 'Chủ hộ / Công dân', 'Phân loại hộ', 'Loại biến động', 'Thu nhập bình quân', 'Trực trạng nhà ở', 'Trạng thái', 'Ngày nhận']);

            foreach ($profiles as $p) {
                fputcsv($file, [
                    $p->code,
                    $p->household_code ?? 'N/A',
                    $p->user->full_name ?? 'N/A',
                    $p->household_type_label,
                    $p->event_type_label,
                    $p->income_per_capita ? number_format($p->income_per_capita) . ' VNĐ' : 'Chưa cập nhật',
                    $p->housing_status ?? 'Đạt chuẩn',
                    $p->status,
                    $p->received_at ? $p->received_at->format('d/m/Y H:i') : '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}