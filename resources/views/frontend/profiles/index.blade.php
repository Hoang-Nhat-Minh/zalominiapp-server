@extends('layouts.main')

@section('title', 'Quản lý Dân cư, Sổ Hộ Khẩu & Biến Động Nhân Khẩu')

@section('content')
    <main class="admin-content-wrapper">
        <div class="profiles-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h1 class="profiles-title" style="font-size: 24px; font-weight: 700; color: #1E293B;">Sổ Hộ Khẩu Số & Biến Động Dân Cư</h1>
                <p class="profiles-subtitle" style="color: #64748B; font-size: 14px;">Quản lý dữ liệu nhân khẩu, phân loại hộ nghèo / cận nghèo và các nghiệp vụ biến động (Sinh - Tử, Tạm trú, Tạm vắng, Tách hộ).</p>
            </div>
            <a href="{{ route('profiles.export') }}" class="admin-btn admin-btn-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; background: #1E4E79; color: #fff; padding: 10px 18px; border-radius: 8px; font-weight: 600;">
                <i class="ph ph-file-csv"></i> Xuất Sổ Hộ Khẩu & Biến Động (CSV)
            </a>
        </div>

        <div class="profiles-stats-grid">
            <div class="profiles-stat-card">
                <div class="profiles-stat-icon" style="color: var(--primary); background: #E8F0FE;"><i class="ph ph-files"></i></div>
                <div class="profiles-stat-info">
                    <span class="profiles-stat-label">Tổng số Hồ sơ / Hộ</span>
                    <span class="profiles-stat-value">{{ $stats->total ?? 0 }}</span>
                </div>
            </div>
            <div class="profiles-stat-card">
                <div class="profiles-stat-icon" style="color: #D97706; background: #FEF3C7;"><i class="ph ph-house-line"></i></div>
                <div class="profiles-stat-info">
                    <span class="profiles-stat-label">Hộ nghèo & Cận nghèo</span>
                    <span class="profiles-stat-value">{{ ($stats->poor_count ?? 0) + ($stats->near_poor_count ?? 0) }}</span>
                </div>
            </div>
            <div class="profiles-stat-card">
                <div class="profiles-stat-icon" style="color: #0284C7; background: #E0F2FE;"><i class="ph ph-baby"></i></div>
                <div class="profiles-stat-info">
                    <span class="profiles-stat-label">Khai sinh / Nhập khẩu</span>
                    <span class="profiles-stat-value">{{ $stats->birth_events ?? 0 }}</span>
                </div>
            </div>
            <div class="profiles-stat-card">
                <div class="profiles-stat-icon" style="color: #DC2626; background: #FEE2E2;"><i class="ph ph-user-minus"></i></div>
                <div class="profiles-stat-info">
                    <span class="profiles-stat-label">Khai tử / Xóa đăng ký</span>
                    <span class="profiles-stat-value">{{ $stats->death_events ?? 0 }}</span>
                </div>
            </div>
            <div class="profiles-stat-card">
                <div class="profiles-stat-icon" style="color: var(--success); background: var(--success-bg);"><i class="ph ph-check-circle"></i></div>
                <div class="profiles-stat-info">
                    <span class="profiles-stat-label">Đã hoàn thành thủ tục</span>
                    <span class="profiles-stat-value">{{ $stats->completed ?? 0 }}</span>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('profiles') }}" class="profiles-filter-bar">
            <div class="profiles-filter-group">
                <div class="profiles-search">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Mã hồ sơ, mã hộ khẩu, tên công dân...">
                </div>
                <select name="household_type" class="profiles-select" onchange="this.form.submit()">
                    <option value="">-- Phân loại hộ gia đình --</option>
                    <option value="normal" {{ request('household_type') === 'normal' ? 'selected' : '' }}>Hộ thường</option>
                    <option value="poor" {{ request('household_type') === 'poor' ? 'selected' : '' }}>Hộ nghèo</option>
                    <option value="near_poor" {{ request('household_type') === 'near_poor' ? 'selected' : '' }}>Hộ cận nghèo</option>
                    <option value="policy" {{ request('household_type') === 'policy' ? 'selected' : '' }}>Gia đình chính sách</option>
                </select>
                <select name="event_type" class="profiles-select" onchange="this.form.submit()">
                    <option value="">-- Loại biến động dân cư --</option>
                    <option value="birth" {{ request('event_type') === 'birth' ? 'selected' : '' }}>Khai sinh / Nhập khẩu</option>
                    <option value="death" {{ request('event_type') === 'death' ? 'selected' : '' }}>Khai tử / Xóa đăng ký</option>
                    <option value="move_in" {{ request('event_type') === 'move_in' ? 'selected' : '' }}>Đăng ký Thường trú / Tạm trú</option>
                    <option value="move_out" {{ request('event_type') === 'move_out' ? 'selected' : '' }}>Tạm vắng / Chuyển đi</option>
                    <option value="split" {{ request('event_type') === 'split' ? 'selected' : '' }}>Tách hộ khẩu</option>
                </select>
                <select name="status" class="profiles-select" onchange="this.form.submit()">
                    <option value="">-- Trạng thái hồ sơ --</option>
                    <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>Đã tiếp nhận</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="waiting" {{ request('status') === 'waiting' ? 'selected' : '' }}>Chờ bổ sung</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                </select>
            </div>
            <button type="submit" class="admin-btn admin-btn-primary" style="background-color: var(--surface); color: var(--text-main); border: 1px solid var(--border);"><i class="ph ph-funnel"></i> Lọc dữ liệu</button>
        </form>

        <div class="profiles-table-wrapper">
            <table class="profiles-table">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã Hồ Sơ / Hộ</th>
                    <th>Công dân / Chủ hộ</th>
                    <th>Phân loại hộ</th>
                    <th>Nghiệp vụ biến động</th>
                    <th>Cán bộ thụ lý</th>
                    <th>Ngày nhận</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($profiles as $index => $profile)
                    <tr>
                        <td class="profiles-text-center">{{ $loop->iteration + ($profiles->currentPage() - 1) * $profiles->perPage() }}</td>
                        <td>
                            <div class="profiles-fw-600 profiles-color-primary">{{ $profile->code }}</div>
                            @if($profile->household_code)
                                <div class="profiles-text-small profiles-color-muted"><i class="ph ph-identification-card"></i> HK: {{ $profile->household_code }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="profiles-citizen-info">
                                <span class="profiles-fw-500">{{ $profile->user->full_name ?? $profile->user->name ?? 'N/A' }}</span>
                                <span class="profiles-text-small profiles-color-muted"><i class="ph ph-phone"></i> {{ $profile->user->phone ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($profile->household_type === 'poor')
                                <span style="background: #FEE2E2; color: #991B1B; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Hộ nghèo</span>
                            @elseif($profile->household_type === 'near_poor')
                                <span style="background: #FEF3C7; color: #92400E; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Hộ cận nghèo</span>
                            @elseif($profile->household_type === 'policy')
                                <span style="background: #E0E7FF; color: #3730A3; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Gia đình chính sách</span>
                            @else
                                <span style="background: #F1F5F9; color: #475569; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Hộ thường</span>
                            @endif
                        </td>
                        <td>
                            <span class="profiles-text-small" style="font-weight: 600; color: #0F766E;">
                                {{ $profile->event_type_label }}
                            </span>
                        </td>
                        <td>
                            @if($profile->officer)
                                <div class="profiles-officer"><i class="ph ph-user-circle"></i> {{ $profile->officer->full_name ?? $profile->officer->name }}</div>
                            @else
                                <span class="profiles-text-small profiles-color-muted">Tổ trưởng / Cán bộ</span>
                            @endif
                        </td>
                        <td>{{ $profile->received_at ? date('d/m/Y H:i', strtotime($profile->received_at)) : date('d/m/Y H:i', strtotime($profile->created_at)) }}</td>
                        <td>
                            <span class="profiles-badge {{ $statusClasses[$profile->status] ?? '' }}">
                                {{ $statusTexts[$profile->status] ?? 'Không xác định' }}
                            </span>
                        </td>
                        <td>
                            <div class="profiles-actions">
                                <button class="profiles-btn-icon" title="Xem chi tiết" onclick="toggleProfileModal('modal-profile-{{ $profile->id }}')">
                                    <i class="ph ph-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <div class="profiles-modal" id="modal-profile-{{ $profile->id }}">
                        <div class="profiles-modal-overlay" onclick="toggleProfileModal('modal-profile-{{ $profile->id }}')"></div>
                        <div class="profiles-modal-content">
                            <div class="profiles-modal-header">
                                <h3 class="profiles-modal-title">Chi tiết Hồ sơ & Nhân khẩu: {{ $profile->code }}</h3>
                                <button class="profiles-modal-close" onclick="toggleProfileModal('modal-profile-{{ $profile->id }}')"><i class="ph ph-x"></i></button>
                            </div>
                            <div class="profiles-modal-body">
                                <div class="profiles-modal-grid">
                                    <div class="profiles-modal-item">
                                        <span class="profiles-modal-label">Mã hồ sơ:</span>
                                        <span class="profiles-modal-value profiles-fw-600">{{ $profile->code }}</span>
                                    </div>
                                    <div class="profiles-modal-item">
                                        <span class="profiles-modal-label">Mã Sổ Hộ Khẩu:</span>
                                        <span class="profiles-modal-value profiles-fw-600">{{ $profile->household_code ?: 'HK-350912' }}</span>
                                    </div>
                                    <div class="profiles-modal-item">
                                        <span class="profiles-modal-label">Phân loại hộ gia đình:</span>
                                        <span class="profiles-modal-value profiles-fw-600" style="color: #1E4E79;">{{ $profile->household_type_label }}</span>
                                    </div>
                                    <div class="profiles-modal-item">
                                        <span class="profiles-modal-label">Loại nghiệp vụ biến động:</span>
                                        <span class="profiles-modal-value profiles-fw-600" style="color: #0F766E;">{{ $profile->event_type_label }}</span>
                                    </div>
                                    <div class="profiles-modal-item">
                                        <span class="profiles-modal-label">Người nộp (Chủ hộ):</span>
                                        <span class="profiles-modal-value"><i class="ph ph-user"></i> {{ $profile->user->full_name ?? $profile->user->name ?? 'N/A' }} ({{ $profile->user->phone ?? 'N/A' }})</span>
                                    </div>
                                    <div class="profiles-modal-item">
                                        <span class="profiles-modal-label">Thu nhập bình quân:</span>
                                        <span class="profiles-modal-value">{{ $profile->income_per_capita ? number_format($profile->income_per_capita) . ' VNĐ / tháng' : 'Đã xác minh theo chuẩn phường' }}</span>
                                    </div>
                                    <div class="profiles-modal-item profiles-full-width">
                                        <span class="profiles-modal-label">Nội dung / Mô tả chi tiết:</span>
                                        <div class="profiles-modal-desc">
                                            {{ $profile->description ?: 'Hồ sơ biến động nhân khẩu đã được kiểm tra và ghi nhận vào sổ bộ quản lý địa bàn.' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="profiles-modal-footer">
                                <button class="admin-btn" style="background: var(--neutral-bg); border: 1px solid var(--neutral-border); color: var(--text-main);" onclick="toggleProfileModal('modal-profile-{{ $profile->id }}')">Đóng</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; color: #64748B; padding: 24px;">Không tìm thấy dữ liệu hồ sơ nhân khẩu nào.</td>
                    </tr>
                @endforelse
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