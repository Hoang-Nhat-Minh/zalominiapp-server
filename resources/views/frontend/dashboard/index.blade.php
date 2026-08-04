@extends('layouts.main')

@section('title', 'Tổng Quan Báo Cáo & Thống Kê')

@section('content')
    <main class="admin-content-wrapper">
        <div class="admin-page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h1 style="font-size: 24px; font-weight: 700; color: #1E293B;">Tổng quan chỉ đạo & điều hành</h1>
                <p style="color: #64748B; font-size: 14px; margin-top: 4px;">Dữ liệu cập nhật theo thời gian thực từ Phân hệ Người dân & Quản lý địa bàn</p>
            </div>
            <a href="{{ route('dashboard.export') }}" class="admin-btn admin-btn-primary" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; background: #1E4E79; color: #fff; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">
                <i class="ph ph-download-simple" style="font-size: 18px;"></i> Xuất Báo Cáo Tổng Quan (CSV)
            </a>
        </div>

        <section class="admin-kpi-grid">
            <div class="admin-kpi-card">
                <div class="admin-kpi-header">Tổng Công Dân Đăng Ký</div>
                <div class="admin-kpi-value">{{ number_format($kpi->total_citizens ?? 0) }}</div>
                <div class="admin-kpi-trend admin-trend-positive"><i class="ph ph-users-three"></i> Định danh trực tuyến</div>
            </div>
            <div class="admin-kpi-card">
                <div class="admin-kpi-header">Tổng Cán Bộ / Tổ Trưởng</div>
                <div class="admin-kpi-value">{{ number_format($kpi->total_officers ?? 0) }}</div>
                <div class="admin-kpi-trend admin-trend-positive"><i class="ph ph-shield-check"></i> Quản lý địa bàn TDP</div>
            </div>
            <div class="admin-kpi-card">
                <div class="admin-kpi-header">Sổ Hộ Khẩu / Dân Cư Số</div>
                <div class="admin-kpi-value">{{ number_format($kpi->total_profiles ?? 0) }}</div>
                <div class="admin-kpi-trend admin-trend-neutral"><i class="ph ph-address-book"></i> Đã đồng bộ hồ sơ</div>
            </div>
            <div class="admin-kpi-card">
                <div class="admin-kpi-header">PAHT Đang Xử Lý</div>
                <div class="admin-kpi-value">{{ number_format($kpi->processing_reports ?? 0) }}</div>
                <div class="admin-kpi-trend admin-trend-warning"><i class="ph ph-warning-circle"></i> Tự động phân công</div>
            </div>
        </section>

        <section class="admin-chart-grid" style="margin-top: 24px;">
            <div class="admin-chart-card">
                <h3>Thống kê truy cập & Xử lý PAHT hàng tháng</h3>
                <div class="admin-chart-container" style="position: relative; height: 280px;">
                    <canvas id="adminMonthlyActivityChart"></canvas>
                </div>
            </div>
            <div class="admin-chart-card">
                <h3>Phân bổ Trạng thái Phản ánh Hiện trường (PAHT)</h3>
                <div class="admin-chart-container" style="position: relative; height: 280px;">
                    <canvas id="adminDocStatusChart"></canvas>
                </div>
            </div>
        </section>

        <section class="admin-table-section" style="margin-top: 24px;">
            <div class="admin-table-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #1E293B;">Phản ánh Hiện trường (PAHT) Mới Tiếp Nhận</h3>
                <a href="{{ route('reports') }}" class="admin-btn-text" style="color: #1E4E79; font-weight: 600; text-decoration: none;">Xem tất cả →</a>
            </div>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Người gửi</th>
                            <th>Danh mục sự cố</th>
                            <th>Bộ phận chuyên trách</th>
                            <th>Địa chỉ</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentItems as $item)
                            <tr>
                                <td><strong>#PA-{{ $item->id }}</strong></td>
                                <td>{{ $item->user->full_name ?? 'Công dân' }}</td>
                                <td>
                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #F1F5F9; color: #334155;">
                                        {{ $item->category_label ?? $item->category }}
                                    </span>
                                </td>
                                <td>
                                    <span style="color: #0F766E; font-weight: 600; font-size: 13px;">
                                        <i class="ph ph-arrows-merge"></i> {{ $item->assigned_department_label }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($item->address, 30) ?: 'Tại hiện trường' }}</td>
                                <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '' }}</td>
                                <td>
                                    @if($item->status === 'pending')
                                        <span class="admin-status-badge admin-status-pending" style="background: #FEF3C7; color: #92400E; padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 12px;">Chờ tiếp nhận</span>
                                    @elseif($item->status === 'processing')
                                        <span class="admin-status-badge admin-status-active" style="background: #DBEAFE; color: #1E40AF; padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 12px;">Đang xử lý</span>
                                    @elseif($item->status === 'resolved')
                                        <span class="admin-status-badge admin-status-approved" style="background: #D1FAE5; color: #065F46; padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 12px;">Đã giải quyết</span>
                                    @else
                                        <span class="admin-status-badge admin-status-archived" style="background: #FEE2E2; color: #991B1B; padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 12px;">Từ chối</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; color: #64748B; padding: 24px;">Chưa có dữ liệu phản ánh hiện trường</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            Chart.defaults.font.family = "'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif";
            Chart.defaults.color = "#5A6B7B";
            Chart.defaults.plugins.tooltip.backgroundColor = "#1F2937";

            // Data from Laravel backend
            const monthlyLabels = {!! json_encode($monthlyData['labels'] ?? []) !!};
            const monthlyLogins = {!! json_encode($monthlyData['logins'] ?? []) !!};
            const monthlyReports = {!! json_encode($monthlyData['reports'] ?? []) !!};
            const reportStatusDist = {!! json_encode([
                $reportStatusDist['resolved'] ?? 0,
                $reportStatusDist['processing'] ?? 0,
                $reportStatusDist['pending'] ?? 0,
                $reportStatusDist['rejected'] ?? 0
            ]) !!};

            // 1. Line Chart
            const ctxActivity = document.getElementById('adminMonthlyActivityChart');
            if(ctxActivity) {
                new Chart(ctxActivity.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: monthlyLabels,
                        datasets: [
                            {
                                label: 'Lượt tương tác / Đăng nhập',
                                data: monthlyLogins,
                                borderColor: '#1E4E79',
                                backgroundColor: 'rgba(30, 78, 121, 0.1)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true,
                                pointRadius: 4,
                                pointBackgroundColor: '#FFFFFF',
                                pointBorderColor: '#1E4E79'
                            },
                            {
                                label: 'Phản ánh hiện trường tiếp nhận',
                                data: monthlyReports,
                                borderColor: '#0284C7',
                                backgroundColor: '#0284C7',
                                borderWidth: 2,
                                borderDash: [5, 5],
                                tension: 0.3,
                                pointRadius: 3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#F1F5F9', drawBorder: false } },
                            x: { grid: { display: false, drawBorder: false } }
                        },
                        plugins: { legend: { position: 'top', align: 'end' } }
                    }
                });
            }

            // 2. Donut Chart
            const ctxDocStatus = document.getElementById('adminDocStatusChart');
            if(ctxDocStatus) {
                new Chart(ctxDocStatus.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Đã giải quyết', 'Đang xử lý', 'Chờ tiếp nhận', 'Từ chối'],
                        datasets: [{
                            data: reportStatusDist,
                            backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } }
                        }
                    }
                });
            }
        });
    </script>
@endpush