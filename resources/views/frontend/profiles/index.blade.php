@extends('layouts.main')

@section('title', 'Quản lý hồ sơ công dân')

@section('content')
    <main class="admin-content-wrapper">
        <div class="profiles-header">
            <div>
                <h1 class="profiles-title">Quản lý hồ sơ công dân</h1>
                <p class="profiles-subtitle">Tiếp nhận, xử lý và theo dõi tiến độ hồ sơ được gửi từ Cổng dịch vụ công.</p>
            </div>
            <button class="admin-btn admin-btn-primary"><i class="ph ph-file-plus"></i> Tạo hồ sơ thủ công</button>
        </div>

        <div class="profiles-stats-grid">
            <div class="profiles-stat-card">
                <div class="profiles-stat-icon" style="color: var(--primary); background: #E8F0FE;"><i class="ph ph-files"></i></div>
                <div class="profiles-stat-info">
                    <span class="profiles-stat-label">Tổng hồ sơ</span>
                    <span class="profiles-stat-value">{{ $stats['total'] }}</span>
                </div>
            </div>
            <div class="profiles-stat-card">
                <div class="profiles-stat-icon" style="color: var(--info); background: var(--info-bg);"><i class="ph ph-tray-arrow-down"></i></div>
                <div class="profiles-stat-info">
                    <span class="profiles-stat-label">Đã tiếp nhận</span>
                    <span class="profiles-stat-value">{{ $stats['received'] }}</span>
                </div>
            </div>
            <div class="profiles-stat-card">
                <div class="profiles-stat-icon" style="color: #673AB7; background: #EDE7F6;"><i class="ph ph-arrows-clockwise"></i></div>
                <div class="profiles-stat-info">
                    <span class="profiles-stat-label">Đang xử lý</span>
                    <span class="profiles-stat-value">{{ $stats['processing'] }}</span>
                </div>
            </div>
            <div class="profiles-stat-card">
                <div class="profiles-stat-icon" style="color: var(--warning); background: var(--warning-bg);"><i class="ph ph-warning-circle"></i></div>
                <div class="profiles-stat-info">
                    <span class="profiles-stat-label">Chờ bổ sung</span>
                    <span class="profiles-stat-value">{{ $stats['waiting'] }}</span>
                </div>
            </div>
            <div class="profiles-stat-card">
                <div class="profiles-stat-icon" style="color: var(--success); background: var(--success-bg);"><i class="ph ph-check-circle"></i></div>
                <div class="profiles-stat-info">
                    <span class="profiles-stat-label">Hoàn thành</span>
                    <span class="profiles-stat-value">{{ $stats['completed'] }}</span>
                </div>
            </div>
            <div class="profiles-stat-card">
                <div class="profiles-stat-icon" style="color: var(--danger); background: var(--danger-bg);"><i class="ph ph-x-circle"></i></div>
                <div class="profiles-stat-info">
                    <span class="profiles-stat-label">Từ chối</span>
                    <span class="profiles-stat-value">{{ $stats['rejected'] }}</span>
                </div>
            </div>
        </div>

        <div class="profiles-filter-bar">
            <div class="profiles-filter-group">
                <div class="profiles-search">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" placeholder="Tìm mã hồ sơ...">
                </div>
                <div class="profiles-search">
                    <i class="ph ph-user"></i>
                    <input type="text" placeholder="Tìm tên công dân...">
                </div>
                <select class="profiles-select">
                    <option value="">Tất cả loại hồ sơ</option>
                    <option value="ho-tich">Hộ tịch</option>
                    <option value="hanh-chinh">Hành chính</option>
                    <option value="dat-dai">Đất đai</option>
                </select>
                <select class="profiles-select">
                    <option value="">Tất cả phòng ban</option>
                    <option value="tu-phap">Phòng Tư pháp</option>
                    <option value="qlhc">Phòng Cảnh sát QLHC</option>
                </select>
                <select class="profiles-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="received">Đã tiếp nhận</option>
                    <option value="processing">Đang xử lý</option>
                    <option value="waiting">Chờ bổ sung</option>
                    <option value="completed">Hoàn thành</option>
                    <option value="rejected">Từ chối</option>
                </select>
            </div>
            <button class="admin-btn admin-btn-primary" style="background-color: var(--surface); color: var(--text-main); border: 1px solid var(--border);"><i class="ph ph-funnel"></i> Lọc dữ liệu</button>
        </div>

        <div class="profiles-table-wrapper">
            <table class="profiles-table">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã hồ sơ</th>
                    <th>Công dân</th>
                    <th>Tiêu đề / Loại</th>
                    <th>Phòng ban xử lý</th>
                    <th>Cán bộ thụ lý</th>
                    <th>Ngày tiếp nhận</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($profiles as $index => $profile)
                    <tr>
                        <td class="profiles-text-center">{{ $index + 1 }}</td>
                        <td class="profiles-fw-600 profiles-color-primary">{{ $profile->code }}</td>
                        <td>
                            <div class="profiles-citizen-info">
                                <span class="profiles-fw-500">{{ $profile->user->name }}</span>
                                <span class="profiles-text-small profiles-color-muted">{{ $profile->user->phone }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="profiles-truncate" title="{{ $profile->title }}">{{ $profile->title }}</div>
                            <span class="profiles-text-small profiles-color-muted">{{ $profile->type }}</span>
                        </td>
                        <td>{{ $profile->department }}</td>
                        <td>
                            @if($profile->officer)
                                <div class="profiles-officer"><i class="ph ph-user-circle"></i> {{ $profile->officer->name }}</div>
                            @else
                                <span class="profiles-text-small profiles-color-muted">Chưa phân công</span>
                            @endif
                        </td>
                        <td>{{ date('d/m/Y H:i', strtotime($profile->received_at)) }}</td>
                        <td>
                        <span class="profiles-badge {{ $statusClasses[$profile->status]??'' }}">
                            {{ $statusTexts[$profile->status]??'Không xác định' }}
                        </span>
                        </td>
                        <td>
                            <div class="profiles-actions">
                                <button class="profiles-btn-icon" title="Xem chi tiết" onclick="toggleProfileModal('modal-profile-{{ $profile->id }}')">
                                    <i class="ph ph-eye"></i>
                                </button>

                                @if($profile->status === 'received')
                                    <button class="profiles-btn-icon profiles-color-info" title="Tiếp nhận thụ lý"><i class="ph ph-download-simple"></i></button>
                                @endif

                                @if($profile->status === 'processing' || $profile->status === 'received')
                                    <button class="profiles-btn-icon profiles-color-primary" title="Chuyển xử lý"><i class="ph ph-share-fat"></i></button>
                                    <button class="profiles-btn-icon profiles-color-warning" title="Yêu cầu bổ sung"><i class="ph ph-warning-circle"></i></button>
                                @endif

                                @if($profile->status === 'processing' || $profile->status === 'waiting')
                                    <button class="profiles-btn-icon profiles-color-success" title="Hoàn thành hồ sơ"><i class="ph ph-check-circle"></i></button>
                                @endif

                                @if($profile->status !== 'completed' && $profile->status !== 'rejected')
                                    <button class="profiles-btn-icon profiles-color-danger" title="Từ chối hồ sơ"><i class="ph ph-x-circle"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <div class="profiles-modal" id="modal-profile-{{ $profile->id }}">
                        <div class="profiles-modal-overlay" onclick="toggleProfileModal('modal-profile-{{ $profile->id }}')"></div>
                        <div class="profiles-modal-content">
                            <div class="profiles-modal-header">
                                <h3 class="profiles-modal-title">Chi tiết hồ sơ: {{ $profile->code }}</h3>
                                <button class="profiles-modal-close" onclick="toggleProfileModal('modal-profile-{{ $profile->id }}')"><i class="ph ph-x"></i></button>
                            </div>
                            <div class="profiles-modal-body">
                                <div class="profiles-modal-grid">
                                    <div class="profiles-modal-item">
                                        <span class="profiles-modal-label">Mã hồ sơ:</span>
                                        <span class="profiles-modal-value profiles-fw-600">{{ $profile->code }}</span>
                                    </div>
                                    <div class="profiles-modal-item">
                                        <span class="profiles-modal-label">Trạng thái:</span>
                                        <span class="profiles-badge {{ $statusClasses[$profile->status]??'' }}">{{ $statusTexts[$profile->status]??'Không xác định' }}</span>
                                    </div>
                                    <div class="profiles-modal-item profiles-full-width">
                                        <span class="profiles-modal-label">Tiêu đề:</span>
                                        <span class="profiles-modal-value profiles-fw-500">{{ $profile->title }}</span>
                                    </div>
                                    <div class="profiles-modal-item">
                                        <span class="profiles-modal-label">Loại hồ sơ:</span>
                                        <span class="profiles-modal-value">{{ $profile->type }}</span>
                                    </div>
                                    <div class="profiles-modal-item">
                                        <span class="profiles-modal-label">Phòng ban xử lý:</span>
                                        <span class="profiles-modal-value">{{ $profile->department }}</span>
                                    </div>
                                    <div class="profiles-modal-item">
                                        <span class="profiles-modal-label">Người nộp (Công dân):</span>
                                        <span class="profiles-modal-value"><i class="ph ph-user"></i> {{ $profile->user->name }} ({{ $profile->user->phone }})</span>
                                    </div>
                                    <div class="profiles-modal-item">
                                        <span class="profiles-modal-label">Cán bộ xử lý:</span>
                                        <span class="profiles-modal-value">{{ $profile->officer ? $profile->officer->name : 'Chưa phân công' }}</span>
                                    </div>
                                    <div class="profiles-modal-item">
                                        <span class="profiles-modal-label">Ngày tiếp nhận:</span>
                                        <span class="profiles-modal-value"><i class="ph ph-calendar-blank"></i> {{ date('d/m/Y H:i', strtotime($profile->received_at)) }}</span>
                                    </div>
                                    <div class="profiles-modal-item">
                                        <span class="profiles-modal-label">Ngày xử lý:</span>
                                        <span class="profiles-modal-value">
                                        @if($profile->processed_at)
                                                <i class="ph ph-calendar-check"></i> {{ date('d/m/Y H:i', strtotime($profile->processed_at)) }}
                                            @else
                                                Chưa xử lý
                                            @endif
                                    </span>
                                    </div>
                                    <div class="profiles-modal-item profiles-full-width">
                                        <span class="profiles-modal-label">Mô tả / Ghi chú:</span>
                                        <div class="profiles-modal-desc">
                                            {{ $profile->description ?: 'Không có mô tả chi tiết.' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="profiles-modal-footer">
                                <button class="admin-btn" style="background: var(--neutral-bg); border: 1px solid var(--neutral-border); color: var(--text-main);" onclick="toggleProfileModal('modal-profile-{{ $profile->id }}')">Đóng</button>
                                @if($profile->status !== 'completed' && $profile->status !== 'rejected')
                                    <button class="admin-btn admin-btn-primary"><i class="ph ph-pencil-simple"></i> Cập nhật trạng thái</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                </tbody>
            </table>

            <div class="pagination-wrapper">
                 {{ $profiles->links('frontend.components.pagination') }}
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        function toggleProfileModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('active');
            }
        }
    </script>
@endpush