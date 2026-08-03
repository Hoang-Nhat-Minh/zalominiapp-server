@extends('layouts.main')

@section('title', 'Thông báo công dân')

@section('content')
    <main class="admin-content-wrapper notifications-wrapper">
        <div class="notifications-header">
            <div class="notifications-header-info">
                <h1 class="notifications-title">Thông báo công dân</h1>
                <p class="notifications-subtitle">Quản lý và phát hành các thông báo, cảnh báo xuống ứng dụng di động của người dân.</p>
            </div>
            <button class="notifications-btn notifications-btn-primary"><i class="ph ph-paper-plane-tilt"></i> Tạo thông báo</button>
        </div>

        <div class="notifications-stats-grid">
            <div class="notifications-stat-card">
                <div class="notifications-stat-icon" style="color: var(--text-main); background: #E5E7EB;"><i class="ph ph-broadcast"></i></div>
                <div class="notifications-stat-info">
                    <span class="notifications-stat-label">Tổng thông báo</span>
                    <span class="notifications-stat-value">{{ number_format($stats->total, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="notifications-stat-card">
                <div class="notifications-stat-icon" style="color: var(--danger); background: var(--danger-bg);"><i class="ph ph-warning-octagon"></i></div>
                <div class="notifications-stat-info">
                    <span class="notifications-stat-label">Khẩn cấp</span>
                    <span class="notifications-stat-value">{{ number_format($stats->emergency, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="notifications-stat-card">
                <div class="notifications-stat-icon" style="color: var(--primary); background: #E8F0FE;"><i class="ph ph-bank"></i></div>
                <div class="notifications-stat-info">
                    <span class="notifications-stat-label">Chính quyền</span>
                    <span class="notifications-stat-value">{{ number_format($stats->government, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="notifications-stat-card">
                <div class="notifications-stat-icon" style="color: var(--warning); background: var(--warning-bg);"><i class="ph ph-plugs"></i></div>
                <div class="notifications-stat-info">
                    <span class="notifications-stat-label">Tiện ích công</span>
                    <span class="notifications-stat-value">{{ number_format($stats->utility, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="notifications-stat-card">
                <div class="notifications-stat-icon" style="color: var(--info); background: var(--info-bg);"><i class="ph ph-users-three"></i></div>
                <div class="notifications-stat-info">
                    <span class="notifications-stat-label">Cộng đồng</span>
                    <span class="notifications-stat-value">{{ number_format($stats->community, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="notifications-filter-bar">
            <div class="notifications-filter-group">
                <div class="notifications-search">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" placeholder="Tìm kiếm theo tiêu đề thông báo...">
                </div>
                <select class="notifications-select">
                    <option value="">Tất cả phân loại</option>
                    <option value="emergency">Khẩn cấp</option>
                    <option value="government">Chính quyền</option>
                    <option value="utility">Tiện ích công</option>
                    <option value="community">Cộng đồng</option>
                </select>
                <input type="date" class="notifications-select" title="Lọc theo ngày gửi">
            </div>
            <button class="notifications-btn notifications-btn-outline"><i class="ph ph-funnel"></i> Lọc kết quả</button>
        </div>

        <div class="notifications-table-wrapper">
            <table class="notifications-table">
                <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="25%">Tiêu đề</th>
                    <th width="15%">Phân loại</th>
                    <th width="20%">Nội dung tóm tắt</th>
                    <th width="15%">Thời gian gửi</th>
                    <th width="10%">Trạng thái</th>
                    <th width="10%" class="notifications-text-center">Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($notifications as $index => $notify)
                    @php
                        $typeConfig = $notify->type_config;
                        $statusConfig = $notify->status_config;
                    @endphp
                    <tr>
                        <td class="notifications-text-center">{{ $index + 1 }}</td>
                        <td>
                            <div class="notifications-title-truncate notifications-fw-600" title="{{ $notify->title }}">{{ $notify->title }}</div>
                            <div class="notifications-text-small notifications-text-muted">Tạo lúc: {{ date('d/m/Y H:i', strtotime($notify->created_at)) }}</div>
                        </td>
                        <td>
                            <span class="notifications-tag">
                                <i class="ph {{ $notify->type_config['icon'] }}"></i>
                                {{ $notify->type_config['label'] }}
                            </span>
                        </td>
                        <td>
                            <div class="notifications-content-truncate" title="{{ $notify->content }}">{{ $notify->content }}</div>
                        </td>
                        <td>
                            @if($notify->sent_at)
                                <div class="notifications-datetime">
                                    <span class="notifications-date"><i class="ph ph-calendar-blank"></i> {{ date('d/m/Y', strtotime($notify->sent_at)) }}</span>
                                    <span class="notifications-time"><i class="ph ph-clock"></i> {{ date('H:i', strtotime($notify->sent_at)) }}</span>
                                </div>
                            @else
                                <span class="notifications-text-muted notifications-text-small">- Chưa xác định -</span>
                            @endif
                        </td>
                        <td>
                            <span class="notifications-badge {{ $notify->status_config['class'] }}">
                                {{ $notify->status_config['label'] }}
                            </span>
                        </td>
                        <td class="notifications-text-center">
                            <div class="notifications-actions">
                                @if($notify->status === 'draft')
                                    <button class="notifications-btn-icon notifications-color-primary" title="Chỉnh sửa"><i class="ph ph-pencil-simple"></i></button>
                                    <button class="notifications-btn-icon notifications-color-success" title="Gửi thông báo"><i class="ph ph-paper-plane-right"></i></button>
                                    <button class="notifications-btn-icon notifications-color-danger" title="Xóa"><i class="ph ph-trash"></i></button>
                                @elseif($notify->status === 'scheduled')
                                    <button class="notifications-btn-icon notifications-color-primary" title="Chỉnh sửa"><i class="ph ph-pencil-simple"></i></button>
                                    <button class="notifications-btn-icon notifications-color-danger" title="Hủy lịch gửi"><i class="ph ph-x-circle"></i></button>
                                @elseif($notify->status === 'sent')
                                    <button class="notifications-btn-icon notifications-color-info" title="Xem chi tiết" onclick="toggleNotificationModal('modal-notify-{{ $notify->id }}')"><i class="ph ph-eye"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <div class="notifications-modal" id="modal-notify-{{ $notify->id }}">
                        <div class="notifications-modal-overlay" onclick="toggleNotificationModal('modal-notify-{{ $notify->id }}')"></div>
                        <div class="notifications-modal-content">
                            <div class="notifications-modal-header">
                                <h3 class="notifications-modal-title">Chi tiết thông báo</h3>
                                <button class="notifications-modal-close" onclick="toggleNotificationModal('modal-notify-{{ $notify->id }}')"><i class="ph ph-x"></i></button>
                            </div>

                            <div class="notifications-modal-body">
                                <div class="notifications-info-box">
                                    <h2 class="notifications-modal-heading">{{ $notify->title }}</h2>

                                    <div class="notifications-meta-grid">
                                        <div class="notifications-meta-item">
                                            <label>Phân loại</label>
                                            <span class="notifications-tag">
                                                <i class="ph {{ $notify->type_config['icon'] }}"></i>
                                                {{ $notify->type_config['label'] }}
                                            </span>
                                        </div>
                                        <div class="notifications-meta-item">
                                            <label>Trạng thái</label>
                                            <span class="notifications-badge {{ $notify->status_config['class'] }}">
                                                {{ $notify->status_config['label'] }}
                                            </span>
                                        </div>
                                        <div class="notifications-meta-item">
                                            <label>Tạo lúc</label>
                                            <span><i class="ph ph-clock"></i> {{ date('d/m/Y H:i', strtotime($notify->created_at)) }}</span>
                                        </div>
                                        <div class="notifications-meta-item">
                                            <label>Gửi lúc</label>
                                            <span>
                                                @if($notify->sent_at)
                                                    <i class="ph ph-paper-plane-right"></i> {{ date('d/m/Y H:i', strtotime($notify->sent_at)) }}
                                                @else
                                                    Chưa gửi
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="notifications-full-content">
                                        {!! nl2br(e($notify->content)) !!}
                                    </div>
                                </div>

                                @if($notify->status === 'sent')
                                    <h4 class="notifications-section-title">Thống kê tương tác</h4>
                                    <div class="notifications-tracking-grid">
                                        <div class="notifications-tracking-card">
                                            <div class="notifications-tracking-val">{{ number_format($notify->read_stats->total, 0, ',', '.') }}</div>
                                            <div class="notifications-tracking-lbl">Tổng người nhận</div>
                                        </div>
                                        <div class="notifications-tracking-card notifications-tracking-read">
                                            <div class="notifications-tracking-val">{{ number_format($notify->read_stats->read, 0, ',', '.') }}</div>
                                            <div class="notifications-tracking-lbl">Đã đọc</div>
                                        </div>
                                        <div class="notifications-tracking-card notifications-tracking-unread">
                                            <div class="notifications-tracking-val">{{ number_format($notify->read_stats->unread, 0, ',', '.') }}</div>
                                            <div class="notifications-tracking-lbl">Chưa đọc</div>
                                        </div>
                                    </div>

                                    <div class="notifications-progress-container">
                                        <div class="notifications-progress-header">
                                            <span class="notifications-progress-lbl">Tỷ lệ chuyển đổi đọc</span>
                                            <span class="notifications-progress-pct">{{ $notify->read_stats->rate }}%</span>
                                        </div>
                                        <div class="notifications-progress-bar">
                                            <div class="notifications-progress-fill" style="width: {{ $notify->read_stats->rate }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="notifications-modal-footer">
                                <button class="notifications-btn notifications-btn-outline" onclick="toggleNotificationModal('modal-notify-{{ $notify->id }}')">Đóng</button>
                                @if($notify->status === 'sent')
                                    <button class="notifications-btn notifications-btn-primary"><i class="ph ph-export"></i> Xuất danh sách</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="notifications-empty-state">
                                <i class="ph ph-bell-slash"></i>
                                <p>Không có dữ liệu thông báo nào.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="pagination-wrapper">
                 {{ $notifications->links('frontend.components.pagination') }}
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        function toggleNotificationModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('active');
            }
        }
    </script>
@endpush