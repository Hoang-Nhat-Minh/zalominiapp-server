<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $reports = $query->paginate(20)->withQueryString();

        $stats = (object)[
            'total'      => Report::count(),
            'pending'    => Report::where('status', 'pending')->count(),
            'processing' => Report::where('status', 'processing')->count(),
            'resolved'   => Report::where('status', 'resolved')->count(),
            'rejected'   => Report::where('status', 'rejected')->count(),
        ];

        $statusConfigs = [
            'pending' => [
                'class' => 'reports-badge-pending',
                'label' => 'Chờ tiếp nhận',
            ],
            'processing' => [
                'class' => 'reports-badge-processing',
                'label' => 'Đang xử lý',
            ],
            'resolved' => [
                'class' => 'reports-badge-resolved',
                'label' => 'Đã xử lý',
            ],
            'rejected' => [
                'class' => 'reports-badge-rejected',
                'label' => 'Từ chối',
            ],
        ];

        $categories = [
            'environment'       => 'Môi trường',
            'urban_order'       => 'Trật tự đô thị',
            'traffic'           => 'Giao thông',
            'infrastructure'    => 'Hạ tầng',
            'electricity_water' => 'Điện lực - Cấp thoát nước',
        ];

        return view('frontend.reports.index', compact(
            'reports',
            'stats',
            'statusConfigs',
            'categories'
        ));
    }

    public function show($id)
    {
        $report = Report::with('user')->findOrFail($id);

        $statusConfigs = [
            'pending' => [
                'class' => 'reports-badge-pending',
                'label' => 'Chờ tiếp nhận',
            ],
            'processing' => [
                'class' => 'reports-badge-processing',
                'label' => 'Đang xử lý',
            ],
            'resolved' => [
                'class' => 'reports-badge-resolved',
                'label' => 'Đã xử lý',
            ],
            'rejected' => [
                'class' => 'reports-badge-rejected',
                'label' => 'Từ chối',
            ],
        ];

        return view('frontend.reports.show', compact('report', 'statusConfigs'));
    }

    public function updateStatus(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $request->validate([
            'status'              => 'required|in:pending,processing,resolved,rejected',
            'assigned_department' => 'nullable|string|max:255',
            'officer_note'        => 'nullable|string|max:1000',
        ]);

        $report->status = $request->status;
        if ($request->filled('assigned_department')) {
            $report->assigned_department = $request->assigned_department;
        }
        if ($request->filled('officer_note')) {
            $report->officer_note = $request->officer_note;
        }
        if ($request->status === 'resolved') {
            $report->resolved_at = now();
        }
        $report->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái phản ánh thành công');
    }

    public function export()
    {
        $reports = Report::with('user')->latest()->get();

        $filename = 'danh_sach_phan_anh_paht_' . date('Y_m_d_His') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($reports) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['ID', 'Người gửi', 'Danh mục', 'Tiêu đề', 'Địa chỉ', 'Bộ phận phân công', 'Trạng thái', 'Ngày tạo']);

            foreach ($reports as $r) {
                fputcsv($file, [
                    $r->id,
                    $r->user->full_name ?? 'Công dân',
                    $r->category_label ?? $r->category,
                    $r->title,
                    $r->address,
                    $r->assigned_department_label ?? $r->assigned_department,
                    $r->status,
                    $r->created_at ? $r->created_at->format('d/m/Y H:i') : '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}