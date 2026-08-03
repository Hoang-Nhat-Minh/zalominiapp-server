@extends('layouts.main')

@section('title', 'Quản lý lịch hẹn')

@section('content')
    @php
        $statusClasses=[
            'pending'=>'appointments-badge-pending',
            'approved'=>'appointments-badge-approved',
            'completed'=>'appointments-badge-completed',
            'cancelled'=>'appointments-badge-cancelled'
        ];

        $statusTexts=[
            'pending'=>'Chờ duyệt',
            'approved'=>'Đã duyệt',
            'completed'=>'Hoàn thành',
            'cancelled'=>'Đã hủy'
        ];
    @endphp
            <main class="admin-content-wrapper">
                <div class="appointments-header">
                    <div>
                        <h1 class="appointments-title">Quản lý lịch hẹn</h1>
                        <p class="appointments-subtitle">Duyệt và quản lý lịch hẹn công dân được gửi từ Cổng dịch vụ công / Ứng dụng di động.</p>
                    </div>
                    <button class="admin-btn admin-btn-primary"><i class="ph ph-plus"></i> Tạo lịch hẹn mới</button>
                </div>

                <div class="appointments-stats-grid">
                    <div class="appointments-stat-card">
                        <div class="appointments-stat-icon" style="color: var(--primary); background: #E8F0FE;"><i class="ph ph-calendar-check"></i></div>
                        <div class="appointments-stat-info">
                            <span class="appointments-stat-label">Tổng lịch hẹn</span>
                            <span class="appointments-stat-value">{{ $stats['total'] }}</span>
                        </div>
                    </div>
                    <div class="appointments-stat-card">
                        <div class="appointments-stat-icon" style="color: var(--warning); background: var(--warning-bg);"><i class="ph ph-hourglass-high"></i></div>
                        <div class="appointments-stat-info">
                            <span class="appointments-stat-label">Chờ duyệt</span>
                            <span class="appointments-stat-value">{{ $stats['pending'] }}</span>
                        </div>
                    </div>
                    <div class="appointments-stat-card">
                        <div class="appointments-stat-icon" style="color: var(--info); background: var(--info-bg);"><i class="ph ph-check-square-offset"></i></div>
                        <div class="appointments-stat-info">
                            <span class="appointments-stat-label">Đã duyệt</span>
                            <span class="appointments-stat-value">{{ $stats['approved'] }}</span>
                        </div>
                    </div>
                    <div class="appointments-stat-card">
                        <div class="appointments-stat-icon" style="color: var(--success); background: var(--success-bg);"><i class="ph ph-check-circle"></i></div>
                        <div class="appointments-stat-info">
                            <span class="appointments-stat-label">Đã hoàn thành</span>
                            <span class="appointments-stat-value">{{ $stats['completed'] }}</span>
                        </div>
                    </div>
                    <div class="appointments-stat-card">
                        <div class="appointments-stat-icon" style="color: var(--danger); background: var(--danger-bg);"><i class="ph ph-x-circle"></i></div>
                        <div class="appointments-stat-info">
                            <span class="appointments-stat-label">Đã hủy</span>
                            <span class="appointments-stat-value">{{ $stats['cancelled'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="appointments-filter-bar">
                    <div class="appointments-filter-group">
                        <div class="appointments-search">
                            <i class="ph ph-magnifying-glass"></i>
                            <input type="text" placeholder="Tìm theo tiêu đề, tên người dân...">
                        </div>
                        <select class="appointments-select">
                            <option value="">Tất cả phòng ban</option>
                            <option value="qlhc">Phòng Cảnh sát QLHC</option>
                            <option value="tp">Phòng Tư pháp</option>
                            <option value="tnmt">Phòng Tài nguyên & Môi trường</option>
                        </select>
                        <select class="appointments-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending">Chờ duyệt</option>
                            <option value="approved">Đã duyệt</option>
                            <option value="completed">Đã hoàn thành</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                        <input type="date" class="appointments-select" title="Lọc theo ngày hẹn">
                    </div>
                    <button class="admin-btn admin-btn-primary" style="background-color: var(--surface); color: var(--text-main); border: 1px solid var(--border);"><i class="ph ph-funnel"></i> Lọc dữ liệu</button>
                </div>

                <div class="appointments-table-wrapper">
                    <table class="appointments-table">
                        <thead>
                        <tr>
                            <th>Mã LH</th>
                            <th>Công dân</th>
                            <th>Tiêu đề / Nội dung</th>
                            <th>Phòng ban xử lý</th>
                            <th>Thời gian hẹn</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($appointments as $apt)
                            <tr>
                                <td class="appointments-fw-500">{{ $appointments->firstItem()+$loop->index }}</td>
                                <td>
                                    <div class="appointments-citizen">
                                        <i class="ph ph-user"></i> {{ $apt->user?->full_name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="appointments-truncate" title="{{ $apt->title }}">
                                        {{ $apt->title }}
                                    </div>
                                    <div class="appointments-time-created">Tạo lúc: {{ date('d/m/Y H:i', strtotime($apt->created_at)) }}</div>
                                </td>
                                <td>{{ $apt->department }}</td>
                                <td>
                                    <div class="appointments-datetime">
                                        <span class="appointments-date"><i class="ph ph-calendar-blank"></i> {{ date('d/m/Y', strtotime($apt->appointment_date)) }}</span>
                                        <span class="appointments-time"><i class="ph ph-clock"></i> {{ date('H:i', strtotime($apt->appointment_time)) }}</span>
                                    </div>
                                </td>
                                <td>
                                <span class="appointments-badge {{ $statusClasses[$apt->status]??'' }}">
                                    {{ $statusTexts[$apt->status]??'Không xác định' }}
                                </span>
                                </td>
                                <td>
                                    <div class="appointments-actions">
                                        <button class="appointments-btn-icon" title="Xem chi tiết" onclick="toggleModal('modal-{{ $apt->id }}')">
                                            <i class="ph ph-eye"></i>
                                        </button>
                                        @if($apt->status === 'pending')
                                            <button class="appointments-btn-icon appointments-color-info" title="Duyệt lịch hẹn">
                                                <i class="ph ph-check-square"></i>
                                            </button>
                                        @endif
                                        @if($apt->status === 'approved')
                                            <button class="appointments-btn-icon appointments-color-success" title="Đánh dấu hoàn thành">
                                                <i class="ph ph-check-circle"></i>
                                            </button>
                                        @endif
                                        @if($apt->status !== 'cancelled' && $apt->status !== 'completed')
                                            <button class="appointments-btn-icon appointments-color-danger" title="Hủy lịch hẹn">
                                                <i class="ph ph-x-circle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @foreach($appointments as $apt)
                        <div class="appointments-modal" id="modal-{{ $apt->id }}">
                            <div class="appointments-modal-overlay" onclick="toggleModal('modal-{{ $apt->id }}')"></div>
                            <div class="appointments-modal-content">
                                <div class="appointments-modal-header">
                                    <h3 class="appointments-modal-title">Chi tiết lịch hẹn #{{ $apt->id }}</h3>
                                    <button class="appointments-modal-close" onclick="toggleModal('modal-{{ $apt->id }}')"><i class="ph ph-x"></i></button>
                                </div>
                                <div class="appointments-modal-body">
                                    <div class="appointments-modal-grid">
                                        <div class="appointments-modal-item">
                                            <span class="appointments-modal-label">Công dân:</span>
                                            <span class="appointments-modal-value">{{ $apt->user?->full_name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="appointments-modal-item">
                                            <span class="appointments-modal-label">Trạng thái:</span>
                                            <span class="appointments-badge {{ $statusClasses[$apt->status]??'' }}">
                                                {{ $statusTexts[$apt->status]??'Không xác định' }}
                                            </span>
                                        </div>
                                        <div class="appointments-modal-item appointments-full-width">
                                            <span class="appointments-modal-label">Tiêu đề:</span>
                                            <span class="appointments-modal-value appointments-fw-500">{{ $apt->title }}</span>
                                        </div>
                                        <div class="appointments-modal-item appointments-full-width">
                                            <span class="appointments-modal-label">Phòng ban xử lý:</span>
                                            <span class="appointments-modal-value">{{ $apt->department }}</span>
                                        </div>
                                        <div class="appointments-modal-item">
                                            <span class="appointments-modal-label">Ngày hẹn:</span>
                                            <span class="appointments-modal-value"><i class="ph ph-calendar-blank"></i> {{ date('d/m/Y', strtotime($apt->appointment_date)) }}</span>
                                        </div>
                                        <div class="appointments-modal-item">
                                            <span class="appointments-modal-label">Giờ hẹn:</span>
                                            <span class="appointments-modal-value"><i class="ph ph-clock"></i> {{ date('H:i', strtotime($apt->appointment_time)) }}</span>
                                        </div>
                                        <div class="appointments-modal-item appointments-full-width">
                                            <span class="appointments-modal-label">Ghi chú của công dân:</span>
                                            <div class="appointments-modal-note">
                                                {{ $apt->note ?: 'Không có ghi chú.' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="appointments-modal-footer">
                                    <button class="admin-btn" style="background: var(--neutral-bg); border: 1px solid var(--neutral-border);" onclick="toggleModal('modal-{{ $apt->id }}')">Đóng</button>
                                    @if($apt->status === 'pending')
                                        <button class="admin-btn admin-btn-primary" style="background: var(--info);"><i class="ph ph-check"></i> Duyệt lịch</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    {{ $appointments->links('frontend.components.pagination') }}
                </div>
            </main>
@endsection

@push('scripts')
    <script>
        // Xử lý bật/tắt Modal
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('active');
            }
        }
    </script>
@endpush