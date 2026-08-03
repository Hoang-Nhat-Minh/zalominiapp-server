@extends('layouts.main')

@section('title', 'Tiếp nhận phản ánh công dân')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
@endpush

@section('content')
    <main class="admin-content-wrapper reports-wrapper">
        <div class="reports-header">
            <div class="reports-header-info">
                <h1 class="reports-title">Tiếp nhận phản ánh công dân</h1>
                <p class="reports-subtitle">Quản lý xử lý phản ánh, kiến nghị từ người dân gửi lên hệ thống.</p>
            </div>
            <button class="reports-btn reports-btn-primary"><i class="ph ph-file-pdf"></i> Xuất báo cáo</button>
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

        <div class="reports-filter-bar">
            <div class="reports-filter-group">
                <div class="reports-search">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" placeholder="Tìm kiếm theo tiêu đề...">
                </div>
                <select class="reports-select">
                    <option value="">Tất cả danh mục</option>
                    <option value="environment">Môi trường</option>
                    <option value="urban_order">Trật tự đô thị</option>
                    <option value="traffic">Giao thông</option>
                    <option value="infrastructure">Hạ tầng</option>
                </select>
                <select class="reports-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending">Chờ tiếp nhận</option>
                    <option value="processing">Đang xử lý</option>
                    <option value="resolved">Đã xử lý</option>
                    <option value="rejected">Từ chối</option>
                </select>
                <input type="date" class="reports-select" title="Lọc theo ngày gửi">
            </div>
            <button class="reports-btn reports-btn-outline"><i class="ph ph-funnel"></i> Lọc kết quả</button>
        </div>

        <div class="reports-table-wrapper">
            <table class="reports-table">
                <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="15%">Người gửi</th>
                    <th width="15%">Danh mục</th>
                    <th width="25%">Tiêu đề / Địa điểm</th>
                    <th width="15%">Thời gian gửi</th>
                    <th width="15%">Trạng thái</th>
                    <th width="10%" class="reports-text-center">Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($reports as $index => $report)
                    @php
                        $statusConfig=$statusConfigs[$report->status]??[
                            'class'=>'',
                            'label'=>'Không xác định'
                        ];

                        $images = $report->images;

                        if (is_string($images)) {
                            $images = json_decode($images, true);
                        }

                        $images = is_array($images) ? $images : [];
                    @endphp
                    <tr>
                        <td class="reports-text-center">{{ $index + 1 }}</td>
                        <td>
                            <div class="reports-user-info">
                                <span class="reports-fw-600">{{ $report->user->full_name }}</span>
                                <span class="reports-text-muted reports-text-small"><i class="ph ph-phone"></i> {{ $report->user->phone }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="reports-category-tag">{{ $categories[$report->category] ?? 'Khác' }}</span>
                        </td>
                        <td>
                            <div class="reports-title-truncate" title="{{ $report->title }}">{{ $report->title }}</div>
                            <div class="reports-address-truncate reports-text-muted reports-text-small" title="{{ $report->address }}">
                                <i class="ph ph-map-pin"></i> {{ $report->address }}
                            </div>
                        </td>
                        <td>{{ date('d/m/Y H:i', strtotime($report->created_at)) }}</td>
                        <td>
                            <span class="reports-badge {{ $statusConfig['class'] }}">{{ $statusConfig['label'] }}</span>
                        </td>
                        <td class="reports-text-center">
                            <div class="reports-actions">
                                @if($report->status === 'pending')
                                    <button class="reports-btn-icon reports-color-info" title="Tiếp nhận xử lý" onclick="toggleReportModal('modal-report-{{ $report->id }}')"><i class="ph ph-download-simple"></i></button>
                                    <button class="reports-btn-icon reports-color-danger" title="Từ chối" onclick="toggleReportModal('modal-report-{{ $report->id }}')"><i class="ph ph-x-circle"></i></button>
                                @elseif($report->status === 'processing')
                                    <button class="reports-btn-icon reports-color-success" title="Đánh dấu hoàn thành" onclick="toggleReportModal('modal-report-{{ $report->id }}')"><i class="ph ph-check-circle"></i></button>
                                    <button class="reports-btn-icon reports-color-primary" title="Thêm ghi chú" onclick="toggleReportModal('modal-report-{{ $report->id }}')"><i class="ph ph-note-pencil"></i></button>
                                @elseif($report->status === 'resolved')
                                    <button class="reports-btn-icon reports-color-primary" title="Xem chi tiết" onclick="toggleReportModal('modal-report-{{ $report->id }}')"><i class="ph ph-eye"></i></button>
                                @elseif($report->status === 'rejected')
                                    <button class="reports-btn-icon reports-color-danger" title="Xem lý do từ chối" onclick="toggleReportModal('modal-report-{{ $report->id }}')"><i class="ph ph-eye"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <div class="reports-modal" id="modal-report-{{ $report->id }}">
                        <div class="reports-modal-overlay" onclick="toggleReportModal('modal-report-{{ $report->id }}')"></div>
                        <div class="reports-modal-content">
                            <div class="reports-modal-header">
                                <h3 class="reports-modal-title">Chi tiết phản ánh #{{ $report->id }}</h3>
                                <button class="reports-modal-close" onclick="toggleReportModal('modal-report-{{ $report->id }}')"><i class="ph ph-x"></i></button>
                            </div>

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
                                                <div class="reports-info-value"><i class="ph ph-user"></i> {{ $report->user->full_name }} - {{ $report->user->phone }}</div>
                                            </div>
                                            <div class="reports-info-group">
                                                <label>Danh mục</label>
                                                <div class="reports-info-value">{{ $categories[$report->category] ?? 'Khác' }}</div>
                                            </div>
                                            <div class="reports-info-group">
                                                <label>Trạng thái</label>
                                                <div class="reports-info-value"><span class="reports-badge {{ $statusConfig['class'] }}">{{ $statusConfig['label'] }}</span></div>
                                            </div>
                                            <div class="reports-info-group">
                                                <label>Thời gian gửi</label>
                                                <div class="reports-info-value"><i class="ph ph-clock"></i> {{ date('d/m/Y H:i', strtotime($report->created_at)) }}</div>
                                            </div>
                                        </div>

                                        <div class="reports-info-group reports-full-width">
                                            <label>Địa điểm</label>
                                            <div class="reports-info-value"><i class="ph ph-map-pin"></i> {{ $report->address }}</div>
                                        </div>

                                        <div class="reports-info-group reports-full-width">
                                            <label>Nội dung mô tả</label>
                                            <div class="reports-info-box">
                                                {{ $report->description }}
                                            </div>
                                        </div>
                                        @if(count($images) > 0)
                                            <div class="reports-info-group reports-full-width">
                                                <label>Hình ảnh đính kèm</label>
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

                                        @if($report->officer_note)
                                            <div class="reports-info-group reports-full-width">
                                                <label>Ghi chú của cán bộ</label>
                                                <div class="reports-officer-note">
                                                    <i class="ph ph-info"></i> {{ $report->officer_note }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="reports-modal-footer">
                                <button class="reports-btn reports-btn-outline" onclick="toggleReportModal('modal-report-{{ $report->id }}')">Đóng</button>
                                @if($report->status === 'pending')
                                    <button class="reports-btn reports-btn-danger"><i class="ph ph-x"></i> Từ chối</button>
                                    <button class="reports-btn reports-btn-primary"><i class="ph ph-download-simple"></i> Tiếp nhận xử lý</button>
                                @elseif($report->status === 'processing')
                                    <button class="reports-btn reports-btn-success"><i class="ph ph-check"></i> Hoàn thành xử lý</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="reports-empty-state">
                                <i class="ph ph-folder-open"></i>
                                <p>Không có dữ liệu phản ánh nào.</p>
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
    </script>

    <script>
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

        function toggleReportModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('active');
            }
        }
    </script>
@endpush