@extends('layouts.main')

@section('title', 'Thông báo công dân')

@section('content')
    <main class="admin-content-wrapper notifications-wrapper">
        @if(session('success'))
            <div style="background: #DEF7EC; color: #03543F; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; display: flex; align-items: center; justify-content: space-between;">
                <span><i class="ph ph-check-circle" style="font-size: 18px; vertical-align: middle; margin-right: 6px;"></i> {{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div style="background: #FDE8E8; color: #9B1C1C; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="notifications-header">
            <div class="notifications-header-info">
                <h1 class="notifications-title">Thông báo công dân</h1>
                <p class="notifications-subtitle">Quản lý và phát hành các thông báo, cảnh báo xuống ứng dụng di động của người dân.</p>
            </div>
            <button class="notifications-btn notifications-btn-primary" onclick="toggleNotificationModal('modal-create-notification')">
                <i class="ph ph-paper-plane-tilt"></i> Tạo thông báo
            </button>
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
            <form action="{{ route('notifications') }}" method="GET" style="display: flex; justify-content: space-between; align-items: center; width: 100%; gap: 16px; flex-wrap: wrap;">
                <div class="notifications-filter-group" style="flex: 1; flex-wrap: wrap;">
                    <div class="notifications-search">
                        <i class="ph ph-magnifying-glass"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm theo tiêu đề thông báo...">
                    </div>
                    <select class="notifications-select" name="type">
                        <option value="">Tất cả phân loại</option>
                        <option value="emergency" {{ request('type') == 'emergency' ? 'selected' : '' }}>Khẩn cấp</option>
                        <option value="government" {{ request('type') == 'government' ? 'selected' : '' }}>Chính quyền</option>
                        <option value="utility" {{ request('type') == 'utility' ? 'selected' : '' }}>Tiện ích công</option>
                        <option value="community" {{ request('type') == 'community' ? 'selected' : '' }}>Cộng đồng</option>
                    </select>
                    <input type="date" class="notifications-select" name="date" value="{{ request('date') }}" title="Lọc theo ngày gửi">
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="notifications-btn notifications-btn-outline"><i class="ph ph-funnel"></i> Lọc kết quả</button>
                    @if(request()->hasAny(['search', 'type', 'date']))
                        <a href="{{ route('notifications') }}" class="notifications-btn" style="background: #e0e0e0; color: #333; text-decoration: none; padding: 8px 14px; border-radius: 6px; font-weight: 600; font-size: 14px;">Bỏ lọc</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="notifications-table-wrapper">
            <table class="notifications-table">
                <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="25%">Tiêu đề</th>
                    <th width="15%">Phân loại</th>
                    <th width="25%">Nội dung tóm tắt</th>
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
                        <td class="notifications-text-center">{{ $notifications->firstItem() + $index }}</td>
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
                                <span class="notifications-text-muted notifications-text-small">- Chưa gửi -</span>
                            @endif
                        </td>
                        <td>
                            <span class="notifications-badge {{ $notify->status_config['class'] }}">
                                {{ $notify->status_config['label'] }}
                            </span>
                        </td>
                        <td class="notifications-text-center">
                            <div class="notifications-actions">
                                <button class="notifications-btn-icon notifications-color-info" title="Xem chi tiết" onclick="toggleNotificationModal('modal-notify-{{ $notify->id }}')">
                                    <i class="ph ph-eye"></i>
                                </button>
                                <form action="{{ route('notifications.destroy', $notify->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thông báo này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="notifications-btn-icon notifications-color-danger" title="Xóa thông báo">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="notifications-modal" id="modal-notify-{{ $notify->id }}">
                        <div class="notifications-modal-overlay" onclick="toggleNotificationModal('modal-notify-{{ $notify->id }}')"></div>
                        <div class="notifications-modal-content">
                            <div class="notifications-modal-header">
                                <h3 class="notifications-modal-title">Chi tiết thông báo #{{ $notify->id }}</h3>
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
                                            <label>Thời gian tạo</label>
                                            <span><i class="ph ph-clock"></i> {{ date('d/m/Y H:i', strtotime($notify->created_at)) }}</span>
                                        </div>
                                        <div class="notifications-meta-item">
                                            <label>Thời gian phát hành</label>
                                            <span>
                                                @if($notify->sent_at)
                                                    <i class="ph ph-paper-plane-right"></i> {{ date('d/m/Y H:i', strtotime($notify->sent_at)) }}
                                                @else
                                                    Chưa gửi
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="notifications-full-content" style="margin-top: 16px; background: #F8FAFC; border: 1px solid var(--border); border-radius: 6px; padding: 16px; line-height: 1.6; font-size: 14px; color: #334155;">
                                        {!! nl2br(e($notify->content)) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="notifications-modal-footer">
                                <button class="notifications-btn notifications-btn-outline" onclick="toggleNotificationModal('modal-notify-{{ $notify->id }}')">Đóng</button>
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

        <!-- Modal Tạo Mới Thông Báo -->
        <div class="notifications-modal" id="modal-create-notification">
            <div class="notifications-modal-overlay" onclick="toggleNotificationModal('modal-create-notification')"></div>
            <div class="notifications-modal-content" style="max-width: 600px;">
                <div class="notifications-modal-header">
                    <h3 class="notifications-modal-title">Tạo mới thông báo xuống ứng dụng công dân</h3>
                    <button class="notifications-modal-close" onclick="toggleNotificationModal('modal-create-notification')"><i class="ph ph-x"></i></button>
                </div>
                <form action="{{ route('notifications.store') }}" method="POST">
                    @csrf
                    <div class="notifications-modal-body" style="display: flex; flex-direction: column; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Tiêu đề thông báo <span style="color:red;">*</span></label>
                            <input type="text" name="title" required placeholder="Nhập tiêu đề thông báo..." style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Phân loại thông báo <span style="color:red;">*</span></label>
                            <select name="type" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px; background: white;">
                                <option value="government">Chính quyền (Thông báo hành chính, chỉ thị)</option>
                                <option value="emergency">Khẩn cấp (Cảnh báo thiên tai, sự cố)</option>
                                <option value="utility">Tiện ích công (Lịch cắt điện, nước, sửa chữa)</option>
                                <option value="community">Cộng đồng (Sinh hoạt TDP, hoạt động chung)</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Nội dung chi tiết thông báo <span style="color:red;">*</span></label>
                            <textarea name="content" rows="5" required placeholder="Nhập chi tiết nội dung thông báo phát hành tới công dân..." style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;"></textarea>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="send_now" id="send_now" value="1" checked style="width: 16px; height: 16px;">
                            <label for="send_now" style="font-size: 14px; font-weight: 500; color: var(--text-main); cursor: pointer;">Phát hành ngay lập tức tới ứng dụng di động công dân</label>
                        </div>
                    </div>
                    <div class="notifications-modal-footer">
                        <button type="button" class="notifications-btn notifications-btn-outline" onclick="toggleNotificationModal('modal-create-notification')">Hủy</button>
                        <button type="submit" class="notifications-btn notifications-btn-primary"><i class="ph ph-paper-plane-tilt"></i> Phát Hành Thông Báo</button>
                    </div>
                </form>
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