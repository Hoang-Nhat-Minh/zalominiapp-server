@extends('layouts.main')

@section('title', 'Tiếp nhận phản ánh công dân (PAHT)')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
@endpush

@section('content')
    <main class="admin-content-wrapper reports-wrapper">
        <div class="reports-header">
            <div class="reports-header-info">
                <h1 class="reports-title">Tiếp nhận & Phân công tự động PAHT</h1>
                <p class="reports-subtitle">Hệ thống tiếp nhận phản ánh hiện trường, tự động phân công về các bộ phận chuyên trách địa bàn.</p>
            </div>
            <a href="{{ route('reports.export') }}" class="reports-btn reports-btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="ph ph-file-csv"></i> Xuất Báo Cáo PAHT (CSV)
            </a>
        </div>

        <div class="reports-stats-grid">
            <div class="reports-stat-card">
                <div class="reports-stat-icon" style="color: var(--text-main); background: #E5E7EB;"><i class="ph ph-files"></i></div>
                <div class="reports-stat-info">
                    <span class="reports-stat-label">Tổng phản ánh</span>
                    <span class="reports-stat-value">{{ $stats->total }}</span>
                </div>
            </div>
            <div class="reports-stat-card">
                <div class="reports-stat-icon" style="color: var(--warning); background: var(--warning-bg);"><i class="ph ph-hourglass-high"></i></div>
                <div class="reports-stat-info">
                    <span class="reports-stat-label">Chờ tiếp nhận</span>
                    <span class="reports-stat-value">{{ $stats->pending }}</span>
                </div>
            </div>
            <div class="reports-stat-card">
                <div class="reports-stat-icon" style="color: var(--info); background: var(--info-bg);"><i class="ph ph-wrench"></i></div>
                <div class="reports-stat-info">
                    <span class="reports-stat-label">Đang xử lý</span>
                    <span class="reports-stat-value">{{ $stats->processing }}</span>
                </div>
            </div>
            <div class="reports-stat-card">
                <div class="reports-stat-icon" style="color: var(--success); background: var(--success-bg);"><i class="ph ph-check-circle"></i></div>
                <div class="reports-stat-info">
                    <span class="reports-stat-label">Đã xử lý</span>
                    <span class="reports-stat-value">{{ $stats->resolved }}</span>
                </div>
            </div>
            <div class="reports-stat-card">
                <div class="reports-stat-icon" style="color: var(--danger); background: var(--danger-bg);"><i class="ph ph-x-circle"></i></div>
                <div class="reports-stat-info">
                    <span class="reports-stat-label">Từ chối</span>
                    <span class="reports-stat-value">{{ $stats->rejected }}</span>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('reports') }}" class="reports-filter-bar">
            <div class="reports-filter-group">
                <div class="reports-search">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tiêu đề, địa chỉ...">
                </div>
                <select name="category" class="reports-select" onchange="this.form.submit()">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach($categories as $key => $name)
                        <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <select name="status" class="reports-select" onchange="this.form.submit()">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ tiếp nhận</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Đã xử lý</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                </select>
            </div>
            <button type="submit" class="reports-btn reports-btn-outline"><i class="ph ph-funnel"></i> Lọc kết quả</button>
        </form>

        <div class="reports-table-wrapper">
            <table class="reports-table">
                <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="15%">Người gửi</th>
                    <th width="12%">Danh mục</th>
                    <th width="18%">Bộ phận phân công (Auto)</th>
                    <th width="20%">Tiêu đề / Địa điểm</th>
                    <th width="10%">Ngày gửi</th>
                    <th width="10%">Trạng thái</th>
                    <th width="10%" class="reports-text-center">Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($reports as $index => $report)
                    @php
                        $statusConfig = $statusConfigs[$report->status] ?? [
                            'class' => '',
                            'label' => 'Không xác định'
                        ];

                        $images = $report->images;
                        if (is_string($images)) {
                            $images = json_decode($images, true);
                        }
                        $images = is_array($images) ? $images : [];
                    @endphp
                    <tr>
                        <td class="reports-text-center">{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}</td>
                        <td>
                            <div class="reports-user-info">
                                <span class="reports-fw-600">{{ $report->user->full_name ?? 'Công dân' }}</span>
                                <span class="reports-text-muted reports-text-small"><i class="ph ph-phone"></i> {{ $report->user->phone ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="reports-category-tag">{{ $report->category_label }}</span>
                        </td>
                        <td>
                            <span style="color: #0F766E; font-weight: 600; font-size: 13px;">
                                <i class="ph ph-arrows-merge"></i> {{ $report->assigned_department_label }}
                            </span>
                        </td>
                        <td>
                            <div class="reports-title-truncate" title="{{ $report->title }}">{{ $report->title }}</div>
                            <div class="reports-address-truncate reports-text-muted reports-text-small" title="{{ $report->address }}">
                                <i class="ph ph-map-pin"></i> {{ $report->address ?: 'Tại vị trí GPS' }}
                            </div>
                        </td>
                        <td>{{ date('d/m/Y H:i', strtotime($report->created_at)) }}</td>
                        <td>
                            <span class="reports-badge {{ $statusConfig['class'] }}">{{ $statusConfig['label'] }}</span>
                        </td>
                        <td class="reports-text-center">
                            <button class="reports-btn-icon reports-color-primary" title="Xem chi tiết & Xử lý" onclick="toggleReportModal('modal-report-{{ $report->id }}')">
                                <i class="ph ph-note-pencil"></i>
                            </button>
                        </td>
                    </tr>

                    <div class="reports-modal" id="modal-report-{{ $report->id }}">
                        <div class="reports-modal-overlay" onclick="toggleReportModal('modal-report-{{ $report->id }}')"></div>
                        <div class="reports-modal-content">
                            <div class="reports-modal-header">
                                <h3 class="reports-modal-title">Xử lý Phản ánh #{{ $report->id }}</h3>
                                <button class="reports-modal-close" onclick="toggleReportModal('modal-report-{{ $report->id }}')"><i class="ph ph-x"></i></button>
                            </div>

                            <form method="POST" action="{{ route('reports.updateStatus', $report->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="reports-modal-body">
                                    <div class="reports-modal-layout">
                                        <div class="reports-modal-info">
                                            <div class="reports-info-group reports-full-width">
                                                <label>Tiêu đề phản ánh</label>
                                                <div class="reports-info-value reports-fw-600 reports-text-lg">{{ $report->title }}</div>
                                            </div>

                                            <div class="reports-info-grid">
                                                <div class="reports-info-group">
                                                    <label>Người gửi</label>
                                                    <div class="reports-info-value"><i class="ph ph-user"></i> {{ $report->user->full_name ?? 'N/A' }} - {{ $report->user->phone ?? 'N/A' }}</div>
                                                </div>
                                                <div class="reports-info-group">
                                                    <label>Danh mục</label>
                                                    <div class="reports-info-value">{{ $report->category_label }}</div>
                                                </div>
                                                <div class="reports-info-group">
                                                    <label>Thời gian gửi</label>
                                                    <div class="reports-info-value"><i class="ph ph-clock"></i> {{ date('d/m/Y H:i', strtotime($report->created_at)) }}</div>
                                                </div>
                                            </div>

                                            <div class="reports-info-group reports-full-width" style="margin-top: 12px;">
                                                <label>Bộ phận chuyên trách (Tự động phân công):</label>
                                                <input type="text" name="assigned_department" value="{{ $report->assigned_department_label }}" class="reports-select" style="width: 100%;">
                                            </div>

                                            <div class="reports-info-group reports-full-width" style="margin-top: 12px;">
                                                <label>Cập nhật trạng thái xử lý:</label>
                                                <select name="status" class="reports-select" style="width: 100%;">
                                                    <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Chờ tiếp nhận</option>
                                                    <option value="processing" {{ $report->status === 'processing' ? 'selected' : '' }}>Đang xử lý (Chuyển bộ phận chuyên trách)</option>
                                                    <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>Đã giải quyết & Công khai</option>
                                                    <option value="rejected" {{ $report->status === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                                                </select>
                                            </div>

                                            <div class="reports-info-group reports-full-width" style="margin-top: 12px;">
                                                <label>Nội dung mô tả từ công dân</label>
                                                <div class="reports-info-box">
                                                    {{ $report->description }}
                                                </div>
                                            </div>

                                            <div class="reports-info-group reports-full-width" style="margin-top: 12px;">
                                                <label>Ghi chú / Kết quả từ cán bộ chuyên trách</label>
                                                <textarea name="officer_note" rows="3" class="reports-select" style="width: 100%; height: auto;" placeholder="Nhập kết quả xử lý hoặc nguyên nhân từ chối...">{{ $report->officer_note }}</textarea>
                                            </div>

                                            @if(count($images) > 0)
                                                <div class="reports-info-group reports-full-width" style="margin-top: 12px;">
                                                    <label>Hình ảnh đính kèm hiện trường</label>
                                                    <div class="reports-image-grid">
                                                        @foreach($images as $img)
                                                            <div class="reports-img-preview"
                                                                 data-fancybox="gallery-{{ $report->id }}"
                                                                 data-src="{{ asset('storage/' . $img) }}"
                                                                 style="cursor: zoom-in;">
                                                                <img src="{{ asset('storage/' . $img) }}" alt="Hình ảnh đính kèm">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="reports-modal-footer">
                                    <button type="button" class="reports-btn reports-btn-outline" onclick="toggleReportModal('modal-report-{{ $report->id }}')">Đóng</button>
                                    <button type="submit" class="reports-btn reports-btn-primary"><i class="ph ph-floppy-disk"></i> Lưu Cập Nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="reports-empty-state">
                                <i class="ph ph-folder-open"></i>
                                <p>Không tìm thấy dữ liệu phản ánh hiện trường nào.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="pagination-wrapper">
                {{ $reports->links('frontend.components.pagination') }}
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        function toggleReportModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('active');
            }
        }

        Fancybox.bind('[data-fancybox]', {
            Toolbar: {
                display: {
                    left: ["infobar"],
                    middle: ["zoomIn", "zoomOut", "toggle1to1"],
                    right: ["slideshow", "download", "close"],
                },
            },
            Images: {
                zoom: true,
            }
        });
    </script>
@endpush