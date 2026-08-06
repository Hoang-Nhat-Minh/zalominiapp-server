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

        <div class="appointments-header">
            <div>
                <h1 class="appointments-title">Quản lý lịch hẹn</h1>
                <p class="appointments-subtitle">Duyệt và quản lý lịch hẹn công dân được gửi từ Cổng dịch vụ công / Ứng dụng di động.</p>
            </div>
            <button class="admin-btn admin-btn-primary" onclick="toggleModal('modal-create-appointment')">
                <i class="ph ph-plus"></i> Tạo lịch hẹn mới
            </button>
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
            <form action="{{ route('appointments') }}" method="GET" style="display: flex; justify-content: space-between; align-items: center; width: 100%; gap: 16px; flex-wrap: wrap;">
                <div class="appointments-filter-group" style="flex: 1; flex-wrap: wrap;">
                    <div class="appointments-search">
                        <i class="ph ph-magnifying-glass"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tiêu đề, tên người dân...">
                    </div>
                    <select class="appointments-select" name="department">
                        <option value="">Tất cả phòng ban</option>
                        <option value="Phòng Cảnh sát QLHC" {{ request('department') == 'Phòng Cảnh sát QLHC' ? 'selected' : '' }}>Phòng Cảnh sát QLHC</option>
                        <option value="Phòng Tư pháp" {{ request('department') == 'Phòng Tư pháp' ? 'selected' : '' }}>Phòng Tư pháp</option>
                        <option value="Phòng Tài nguyên & Môi trường" {{ request('department') == 'Phòng Tài nguyên & Môi trường' ? 'selected' : '' }}>Phòng Tài nguyên & Môi trường</option>
                        <option value="Bộ phận một cửa" {{ request('department') == 'Bộ phận một cửa' ? 'selected' : '' }}>Bộ phận một cửa</option>
                    </select>
                    <select class="appointments-select" name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                    <input type="date" class="appointments-select" name="date" value="{{ request('date') }}" title="Lọc theo ngày hẹn">
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="admin-btn admin-btn-primary" style="background-color: var(--surface); color: var(--text-main); border: 1px solid var(--border);"><i class="ph ph-funnel"></i> Lọc dữ liệu</button>
                    @if(request()->hasAny(['search', 'department', 'status', 'date']))
                        <a href="{{ route('appointments') }}" class="admin-btn" style="background: #e0e0e0; color: #333; text-decoration: none; padding: 10px 14px; border-radius: 6px; font-weight: 600; font-size: 14px;">Bỏ lọc</a>
                    @endif
                </div>
            </form>
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
                @forelse ($appointments as $apt)
                    <tr>
                        <td class="appointments-fw-500">#{{ $apt->id }}</td>
                        <td>
                            <div class="appointments-citizen">
                                <i class="ph ph-user"></i> {{ $apt->user?->full_name ?? ($apt->user?->name ?? 'N/A') }}
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
                                    <form action="{{ route('appointments.updateStatus', $apt->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="appointments-btn-icon appointments-color-info" title="Duyệt lịch hẹn">
                                            <i class="ph ph-check-square"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($apt->status === 'approved')
                                    <form action="{{ route('appointments.updateStatus', $apt->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="appointments-btn-icon appointments-color-success" title="Đánh dấu hoàn thành">
                                            <i class="ph ph-check-circle"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($apt->status !== 'cancelled' && $apt->status !== 'completed')
                                    <form action="{{ route('appointments.updateStatus', $apt->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="appointments-btn-icon appointments-color-danger" title="Hủy lịch hẹn">
                                            <i class="ph ph-x-circle"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('appointments.destroy', $apt->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch hẹn này khỏi hệ thống?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="appointments-btn-icon" style="color: #6b7280;" title="Xóa lịch hẹn">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">
                            Chưa có dữ liệu lịch hẹn nào phù hợp.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

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
                            <span class="appointments-modal-value">{{ $apt->user?->full_name ?? ($apt->user?->name ?? 'N/A') }}</span>
                        </div>
                        <div class="appointments-modal-item">
                            <span class="appointments-modal-label">Số điện thoại:</span>
                            <span class="appointments-modal-value">{{ $apt->user?->phone ?? 'Chưa cập nhật' }}</span>
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
                        <form action="{{ route('appointments.updateStatus', $apt->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="admin-btn admin-btn-primary" style="background: var(--info);"><i class="ph ph-check"></i> Duyệt lịch</button>
                        </form>
                    @endif
                    @if($apt->status === 'approved')
                        <form action="{{ route('appointments.updateStatus', $apt->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="admin-btn admin-btn-primary" style="background: var(--success);"><i class="ph ph-check-circle"></i> Hoàn thành</button>
                        </form>
                    @endif
                    @if($apt->status !== 'cancelled' && $apt->status !== 'completed')
                        <form action="{{ route('appointments.updateStatus', $apt->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?');">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="admin-btn" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5;"><i class="ph ph-x-circle"></i> Hủy lịch</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

        <!-- Modal Tạo mới lịch hẹn -->
        <div class="appointments-modal" id="modal-create-appointment">
            <div class="appointments-modal-overlay" onclick="toggleModal('modal-create-appointment')"></div>
            <div class="appointments-modal-content" style="max-width: 600px;">
                <div class="appointments-modal-header">
                    <h3 class="appointments-modal-title">Tạo mới lịch hẹn cho công dân</h3>
                    <button class="appointments-modal-close" onclick="toggleModal('modal-create-appointment')"><i class="ph ph-x"></i></button>
                </div>
                <form action="{{ route('appointments.store') }}" method="POST">
                    @csrf
                    <div class="appointments-modal-body" style="display: flex; flex-direction: column; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Công dân đặt lịch <span style="color:red;">*</span></label>
                            <select name="user_id" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px; background: white;">
                                <option value="">-- Chọn công dân --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->full_name ?? 'Công dân' }} ({{ $u->phone ?? 'Không SĐT' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Tiêu đề nội dung làm việc <span style="color:red;">*</span></label>
                            <input type="text" name="title" required placeholder="Nội dung làm việc/thủ tục hành chính..." style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Phòng ban xử lý <span style="color:red;">*</span></label>
                            <input type="text" name="department" required placeholder="Bộ phận một cửa / Phòng Tư pháp..." list="dept-list" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                            <datalist id="dept-list">
                                <option value="Bộ phận một cửa">
                                <option value="Phòng Cảnh sát QLHC">
                                <option value="Phòng Tư pháp">
                                <option value="Phòng Tài nguyên & Môi trường">
                            </datalist>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Ngày hẹn <span style="color:red;">*</span></label>
                                <input type="date" name="appointment_date" required min="{{ date('Y-m-d') }}" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Giờ hẹn <span style="color:red;">*</span></label>
                                <input type="time" name="appointment_time" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Ghi chú thêm</label>
                            <textarea name="note" rows="3" placeholder="Ghi chú chi tiết thêm nếu có..." style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;"></textarea>
                        </div>
                    </div>
                    <div class="appointments-modal-footer">
                        <button type="button" class="admin-btn" style="background: var(--neutral-bg); border: 1px solid var(--neutral-border);" onclick="toggleModal('modal-create-appointment')">Hủy</button>
                        <button type="submit" class="admin-btn admin-btn-primary"><i class="ph ph-plus"></i> Tạo & Duyệt lịch hẹn</button>
                    </div>
                </form>
            </div>
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