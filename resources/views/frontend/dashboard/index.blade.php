@extends('layouts.main')

@section('title', 'Tổng Quan')

@section('content')
    <main class="admin-content-wrapper">
                <div class="admin-page-header">
                    <h1>Tổng quan hệ thống</h1>
                    <button class="admin-btn admin-btn-primary"><i class="ph ph-download-simple"></i> Xuất báo cáo</button>
                </div>

                <section class="admin-kpi-grid">
                    <div class="admin-kpi-card">
                        <div class="admin-kpi-header">Tổng số cán bộ</div>
                        <div class="admin-kpi-value">1,492</div>
                        <div class="admin-kpi-trend admin-trend-positive"><i class="ph ph-trend-up"></i> +12 tháng này</div>
                    </div>
                    <div class="admin-kpi-card">
                        <div class="admin-kpi-header">Văn bản đang xử lý</div>
                        <div class="admin-kpi-value">384</div>
                        <div class="admin-kpi-trend admin-trend-neutral"><i class="ph ph-minus"></i> Không đổi</div>
                    </div>
                    <div class="admin-kpi-card">
                        <div class="admin-kpi-header">Cuộc họp/Bỏ phiếu</div>
                        <div class="admin-kpi-value">8</div>
                        <div class="admin-kpi-trend admin-trend-warning"><i class="ph ph-clock"></i> 2 sắp đóng</div>
                    </div>
                    <div class="admin-kpi-card">
                        <div class="admin-kpi-header">Lịch trình sắp tới</div>
                        <div class="admin-kpi-value">45</div>
                        <div class="admin-kpi-trend admin-trend-positive"><i class="ph ph-calendar-check"></i> Đúng tiến độ</div>
                    </div>
                </section>

                <section class="admin-chart-grid">
                    <div class="admin-chart-card">
                        <h3>Hoạt động truy cập hàng tháng</h3>
                        <div class="admin-chart-container">
                            <canvas id="adminMonthlyActivityChart"></canvas>
                        </div>
                    </div>
                    <div class="admin-chart-card">
                        <h3>Trạng thái văn bản</h3>
                        <div class="admin-chart-container">
                            <canvas id="adminDocStatusChart"></canvas>
                        </div>
                    </div>
                </section>

                <section class="admin-table-section">
                    <div class="admin-table-header">
                        <h3>Thông báo & Trình duyệt gần đây</h3>
                        <div class="admin-table-actions">
                            <input type="text" placeholder="Lọc danh sách..." class="admin-table-filter">
                        </div>
                    </div>
                    <div class="admin-table-container">
                        <table class="admin-table">
                            <thead>
                            <tr>
                                <th>Mã ID</th>
                                <th>Tiêu đề</th>
                                <th>Phân loại</th>
                                <th>Ngày tạo</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>DOC-2026-041</td>
                                <td>Dự thảo ngân sách liên sở Quý 3</td>
                                <td>Tài chính</td>
                                <td>22/06/2026</td>
                                <td><span class="admin-status-badge admin-status-pending">Chờ duyệt</span></td>
                                <td><button class="admin-btn-text">Kiểm tra</button></td>
                            </tr>
                            <tr>
                                <td>VOT-2026-012</td>
                                <td>Biểu quyết chính sách hạ tầng CNTT mới</td>
                                <td>Quy định</td>
                                <td>21/06/2026</td>
                                <td><span class="admin-status-badge admin-status-active">Đang mở</span></td>
                                <td><button class="admin-btn-text">Xem</button></td>
                            </tr>
                            <tr>
                                <td>APT-2026-089</td>
                                <td>Họp báo cáo kiểm toán Bộ</td>
                                <td>Cuộc họp</td>
                                <td>20/06/2026</td>
                                <td><span class="admin-status-badge admin-status-approved">Đã chốt</span></td>
                                <td><button class="admin-btn-text">Chi tiết</button></td>
                            </tr>
                            <tr>
                                <td>DOC-2026-039</td>
                                <td>Lưu trữ báo cáo nhân sự Quý 1</td>
                                <td>Nhân sự</td>
                                <td>18/06/2026</td>
                                <td><span class="admin-status-badge admin-status-archived">Lưu trữ</span></td>
                                <td><button class="admin-btn-text">Xem</button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Cấu hình chung cho Chart.js
            Chart.defaults.font.family = "'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif";
            Chart.defaults.color = "#5A6B7B";
            Chart.defaults.plugins.tooltip.backgroundColor = "#1F2937";

            // 1. Biểu đồ đường
            const ctxActivity = document.getElementById('adminMonthlyActivityChart');
            if(ctxActivity) {
                new Chart(ctxActivity.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
                        datasets: [
                            {
                                label: 'Lượt đăng nhập',
                                data: [1200, 1350, 1250, 1420, 1500, 1680],
                                borderColor: '#1E4E79',
                                backgroundColor: '#1E4E79',
                                borderWidth: 2,
                                tension: 0.1,
                                pointRadius: 4,
                                pointBackgroundColor: '#FFFFFF',
                                pointBorderColor: '#1E4E79'
                            },
                            {
                                label: 'Văn bản xử lý',
                                data: [300, 420, 380, 500, 480, 610],
                                borderColor: '#5A6B7B',
                                backgroundColor: '#5A6B7B',
                                borderWidth: 2,
                                borderDash: [5, 5],
                                tension: 0.1,
                                pointRadius: 0
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#E5E7EB', drawBorder: false } },
                            x: { grid: { display: false, drawBorder: false } }
                        },
                        plugins: { legend: { position: 'top', align: 'end' } }
                    }
                });
            }

            // 2. Biểu đồ Donut
            const ctxDocStatus = document.getElementById('adminDocStatusChart');
            if(ctxDocStatus) {
                new Chart(ctxDocStatus.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Đã duyệt', 'Chờ duyệt', 'Bản nháp', 'Từ chối'],
                        datasets: [{
                            data: [45, 25, 20, 10],
                            backgroundColor: ['#2E7D32', '#ED6C02', '#9E9E9E', '#D32F2F'],
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