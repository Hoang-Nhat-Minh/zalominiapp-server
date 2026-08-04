@extends('layouts.main')

@section('title', 'Tài khoản công dân')

@section('content')
    <main class="admin-content-wrapper citizens-wrapper">
        <div class="citizens-header">
            <div class="citizens-header-info">
                <h1 class="citizens-title">Tài khoản công dân</h1>
                <p class="citizens-subtitle">Quản lý danh sách tài khoản, phân quyền và giám sát hoạt động trên hệ thống.</p>
            </div>
            <div class="citizens-header-actions">
                <a href="{{ route('citizens.export') }}" class="citizens-btn citizens-btn-outline" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;"><i class="ph ph-file-csv"></i> Xuất CSV</a>
            </div>
        </div>

        <div class="citizens-stats-grid">
            <div class="citizens-stat-card">
                <div class="citizens-stat-icon" style="color: var(--primary); background: #E8F0FE;"><i class="ph ph-users"></i></div>
                <div class="citizens-stat-info">
                    <span class="citizens-stat-label">Tổng tài khoản</span>
                    <span class="citizens-stat-value">{{ number_format($stats->total) }}</span>
                </div>
            </div>
            <div class="citizens-stat-card">
                <div class="citizens-stat-icon" style="color: var(--success); background: var(--success-bg);"><i class="ph ph-shield-check"></i></div>
                <div class="citizens-stat-info">
                    <span class="citizens-stat-label">Đã xác thực</span>
                    <span class="citizens-stat-value">{{ number_format($stats->verified) }}</span>
                </div>
            </div>
            <div class="citizens-stat-card">
                <div class="citizens-stat-icon" style="color: var(--text-muted); background: var(--border);"><i class="ph ph-shield-warning"></i></div>
                <div class="citizens-stat-info">
                    <span class="citizens-stat-label">Chưa xác thực</span>
                    <span class="citizens-stat-value">{{ number_format($stats->unverified) }}</span>
                </div>
            </div>
            <div class="citizens-stat-card">
                <div class="citizens-stat-icon" style="color: #673AB7; background: #EDE7F6;"><i class="ph ph-activity"></i></div>
                <div class="citizens-stat-info">
                    <span class="citizens-stat-label">Hoạt động (30 ngày)</span>
                    <span class="citizens-stat-value">{{ number_format($stats->active_30d) }}</span>
                </div>
            </div>
        </div>

        <div class="citizens-filter-bar">
            <div class="citizens-filter-group">
                <div class="citizens-search">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" placeholder="Tìm họ tên, SĐT, CCCD...">
                </div>
                <select class="citizens-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="verified">Đã xác thực</option>
                    <option value="unverified">Chưa xác thực</option>
                </select>
                <select class="citizens-select">
                    <option value="">Hoạt động</option>
                    <option value="online">Online gần đây</option>
                    <option value="inactive">Không hoạt động</option>
                </select>
            </div>
            <button class="citizens-btn citizens-btn-primary" style="background: var(--surface); color: var(--text-main); border: 1px solid var(--border);"><i class="ph ph-funnel"></i> Lọc dữ liệu</button>
        </div>

        <div class="citizens-table-wrapper">
            <table class="citizens-table">
                <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="5%">Ảnh</th>
                    <th width="15%">Họ và tên</th>
                    <th width="12%">Số điện thoại</th>
                    <th width="15%">CCCD</th>
                    <th width="10%">Vai trò</th>
                    <th width="12%">Trạng thái</th>
                    <th width="13%">Đăng nhập cuối</th>
                    <th width="10%">Ngày tạo</th>
                    <th width="8%" class="citizens-text-center">Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($citizens as $index => $user)
                    <tr>
                        <td class="citizens-text-center">{{ $index + 1 }}</td>
                        <td>
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="Avatar" class="citizens-avatar-sm">
                            @else
                                <div class="citizens-avatar-sm citizens-avatar-placeholder">{{ $user->full_name }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="citizens-fw-600 citizens-text-main">{{ $user->full_name }}</span>
                        </td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->citizen_code ?? '---' }}</td>
                        <td>
                            <span class="citizens-badge {{ $user->role_config['class'] }}">
                                {{ $user->role_config['label'] }}
                            </span>
                        </td>
                        <td>
                            @if($user->is_verified)
                                <span class="citizens-badge citizens-badge-success">
                                    Đã xác thực
                                </span>
                            @else
                                <span class="citizens-badge citizens-badge-warning">
                                    Chưa xác thực
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($user->last_login_at)
                                <div class="citizens-date-cell">
                                    <span>{{ date('d/m/Y', strtotime($user->last_login_at)) }}</span>
                                    <span class="citizens-text-muted">{{ date('H:i', strtotime($user->last_login_at)) }}</span>
                                </div>
                            @else
                                <span class="citizens-text-muted">Chưa đăng nhập</span>
                            @endif
                        </td>
                        <td>{{ date('d/m/Y', strtotime($user->created_at)) }}</td>
                        <td class="citizens-text-center">
                            <div class="citizens-actions">
                                <button class="citizens-btn-icon citizens-color-primary" title="Xem chi tiết" onclick="toggleCitizenModal('modal-citizen-{{ $user->id }}')"><i class="ph ph-eye"></i></button>
                                <button class="citizens-btn-icon citizens-color-info" title="Chỉnh sửa"><i class="ph ph-pencil-simple"></i></button>
                            </div>
                        </td>
                    </tr>

                    <div class="citizens-modal" id="modal-citizen-{{ $user->id }}">
                        <div class="citizens-modal-overlay" onclick="toggleCitizenModal('modal-citizen-{{ $user->id }}')"></div>
                        <div class="citizens-modal-content">
                            <div class="citizens-modal-header">
                                <h3 class="citizens-modal-title">Hồ sơ tài khoản</h3>
                                <button class="citizens-modal-close" onclick="toggleCitizenModal('modal-citizen-{{ $user->id }}')"><i class="ph ph-x"></i></button>
                            </div>
                            <div class="citizens-modal-body">
                                <div class="citizens-profile-header">
                                    <div class="citizens-profile-avatar">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="Avatar">
                                        @else
                                            <div class="citizens-avatar-placeholder-lg">{{ getInitials($user->full_name) }}</div>
                                        @endif
                                    </div>
                                    <div class="citizens-profile-title">
                                        <h2>{{ $user->full_name }}</h2>
                                        <div class="citizens-profile-badges">
                                            <span class="citizens-badge {{ $user->role_config['class'] }}">
                                                {{ $user->role_config['label'] }}
                                            </span>
                                            @if($user->is_verified)
                                                <span class="citizens-badge citizens-badge-success"><i class="ph ph-check-circle"></i>Đã xác thực</span>
                                            @else
                                                <span class="citizens-badge citizens-badge-warning"><i class="ph ph-warning-circle"></i>Chưa xác thực</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="citizens-info-grid">
                                    <div class="citizens-info-section">
                                        <h4 class="citizens-section-title"><i class="ph ph-identification-card"></i> Thông tin cá nhân</h4>
                                        <div class="citizens-info-list">
                                            <div class="citizens-info-item">
                                                <span class="citizens-info-lbl">Số CCCD:</span>
                                                <span class="citizens-info-val citizens-fw-600">{{ $user->citizen_code ?? 'Chưa cập nhật' }}</span>
                                            </div>
                                            <div class="citizens-info-item">
                                                <span class="citizens-info-lbl">Số điện thoại:</span>
                                                <span class="citizens-info-val">{{ $user->phone }}</span>
                                            </div>
                                            <div class="citizens-info-item">
                                                <span class="citizens-info-lbl">Zalo ID:</span>
                                                <span class="citizens-info-val">{{ $user->zalo_id ?? 'Chưa liên kết' }}</span>
                                            </div>
                                            <div class="citizens-info-item">
                                                <span class="citizens-info-lbl">Địa chỉ:</span>
                                                <span class="citizens-info-val">{{ $user->address ?? 'Chưa cập nhật' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="citizens-info-section">
                                        <h4 class="citizens-section-title"><i class="ph ph-desktop"></i> Thông tin hệ thống</h4>
                                        <div class="citizens-info-list">
                                            <div class="citizens-info-item">
                                                <span class="citizens-info-lbl">ID Hệ thống:</span>
                                                <span class="citizens-info-val">#{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</span>
                                            </div>
                                            <div class="citizens-info-item">
                                                <span class="citizens-info-lbl">Ngày tạo:</span>
                                                <span class="citizens-info-val">{{ date('d/m/Y H:i', strtotime($user->created_at)) }}</span>
                                            </div>
                                            <div class="citizens-info-item">
                                                <span class="citizens-info-lbl">Cập nhật lần cuối:</span>
                                                <span class="citizens-info-val">{{ date('d/m/Y H:i', strtotime($user->updated_at)) }}</span>
                                            </div>
                                            <div class="citizens-info-item">
                                                <span class="citizens-info-lbl">Đăng nhập gần nhất:</span>
                                                <span class="citizens-info-val">
                                                    @if($user->last_login_at)
                                                        {{ date('d/m/Y H:i', strtotime($user->last_login_at)) }}
                                                    @else
                                                        <span class="citizens-text-muted">Chưa từng đăng nhập</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="citizens-activity-section">
                                    <h4 class="citizens-section-title"><i class="ph ph-chart-line-up"></i> Thống kê hoạt động</h4>
                                    <div class="citizens-activity-grid">
                                        <div class="citizens-activity-card">
                                            <div class="citizens-activity-val">{{ $user->profiles_count }}</div>
                                            <div class="citizens-activity-lbl">Hồ sơ dịch vụ công</div>
                                        </div>
                                        <div class="citizens-activity-card">
                                            <div class="citizens-activity-val">{{ $user->appointments_count }}</div>
                                            <div class="citizens-activity-lbl">Lịch hẹn</div>
                                        </div>
                                        <div class="citizens-activity-card">
                                            <div class="citizens-activity-val">{{ $user->reports_count }}</div>
                                            <div class="citizens-activity-lbl">Phản ánh kiến nghị</div>
                                        </div>
                                        <div class="citizens-activity-card">
                                            <div class="citizens-activity-val">{{ $user->party_vote_responses_count }}</div>
                                            <div class="citizens-activity-lbl">Lượt biểu quyết</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="citizens-modal-footer">
                                <button class="citizens-btn citizens-btn-outline" onclick="toggleCitizenModal('modal-citizen-{{ $user->id }}')">Đóng</button>
                                <button class="citizens-btn citizens-btn-info"><i class="ph ph-pencil-simple"></i> Cập nhật</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="10">
                            <div class="citizens-empty-state">
                                <i class="ph ph-users-slash"></i>
                                <p>Danh sách tài khoản công dân hiện đang trống.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="pagination-wrapper">
                 {{ $citizens->links('frontend.components.pagination') }}
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        function toggleCitizenModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('active');
            }
        }
    </script>
@endpush