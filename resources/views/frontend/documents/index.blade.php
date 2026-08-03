@extends('layouts.main')

@section('title', 'Tài liệu dịch vụ công - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper documents-wrapper">
        <div class="documents-header">
            <div class="documents-header-info">
                <h1 class="documents-title">Tài liệu dịch vụ công</h1>
                <p class="documents-subtitle">Quản lý kho tài liệu, văn bản và biểu mẫu điện tử phục vụ người dân và doanh nghiệp.</p>
            </div>
            <button class="documents-btn documents-btn-primary"><i class="ph ph-plus-circle"></i> Thêm tài liệu</button>
        </div>

        <div class="documents-stats-grid">
            <div class="documents-stat-card">
                <div class="documents-stat-icon" style="color: var(--primary); background: #E8F0FE;"><i class="ph ph-folders"></i></div>
                <div class="documents-stat-info">
                    <span class="documents-stat-label">Tổng tài liệu</span>
                    <span class="documents-stat-value">{{ number_format($stats->total) }}</span>
                </div>
            </div>
            <div class="documents-stat-card">
                <div class="documents-stat-icon" style="color: var(--success); background: var(--success-bg);"><i class="ph ph-check-circle"></i></div>
                <div class="documents-stat-info">
                    <span class="documents-stat-label">Đã xuất bản</span>
                    <span class="documents-stat-value">{{ number_format($stats->published) }}</span>
                </div>
            </div>
            <div class="documents-stat-card">
                <div class="documents-stat-icon" style="color: var(--warning); background: var(--warning-bg);"><i class="ph ph-note-pencil"></i></div>
                <div class="documents-stat-info">
                    <span class="documents-stat-label">Bản nháp</span>
                    <span class="documents-stat-value">{{ number_format($stats->draft) }}</span>
                </div>
            </div>
            <div class="documents-stat-card">
                <div class="documents-stat-icon" style="color: var(--text-muted); background: var(--background);"><i class="ph ph-archive"></i></div>
                <div class="documents-stat-info">
                    <span class="documents-stat-label">Lưu trữ</span>
                    <span class="documents-stat-value">{{ number_format($stats->archived) }}</span>
                </div>
            </div>
        </div>

        <div class="documents-filter-bar">
            <div class="documents-filter-group">
                <div class="documents-search">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" placeholder="Tìm kiếm theo tên tài liệu...">
                </div>
                <select class="documents-select">
                    <option value="">Tất cả danh mục</option>
                    <option value="meeting">Họp chi bộ</option>
                    <option value="directive">Chỉ thị</option>
                    <option value="resolution">Nghị quyết</option>
                    <option value="report">Báo cáo</option>
                    <option value="form">Biểu mẫu</option>
                    <option value="guide">Hướng dẫn</option>
                </select>
                <select class="documents-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="draft">Bản nháp</option>
                    <option value="published">Đã xuất bản</option>
                    <option value="archived">Lưu trữ</option>
                </select>
            </div>
            <button class="documents-btn documents-btn-outline"><i class="ph ph-funnel"></i> Lọc dữ liệu</button>
        </div>

        <div class="documents-table-wrapper">
            <table class="documents-table">
                <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="30%">Tiêu đề</th>
                    <th width="15%">Danh mục</th>
                    <th width="15%">Người tạo</th>
                    <th width="12%">Trạng thái</th>
                    <th width="13%">Ngày tạo / Xuất bản</th>
                    <th width="10%" class="documents-text-center">Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($documents as $index => $doc)
                    <tr>
                        <td class="documents-text-center">{{ $index + 1 }}</td>
                        <td>
                            <div class="documents-title-text" title="{{ $doc->title }}">{{ $doc->title }}</div>
                            <div class="documents-file-info">
                                <i class="ph {{ $doc->file_icon }}"></i>
                                {{ $doc->file_info->name }} ({{ $doc->file_info->size }})
                            </div>
                        </td>
                        <td>
                            <span class="documents-tag {{ $doc->category_config['class'] }}">
                                {{ $doc->category_config['label'] }}
                            </span>
                        </td>
                        <td>
                            <div class="documents-author">
                                <span class="documents-author-name">{{ $doc->author->name }}</span>
                                <span class="documents-author-role">{{ $doc->author->role }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="documents-badge {{ $doc->status_config['class'] }}">
                                {{ $doc->status_config['label'] }}
                            </span>
                        </td>
                        <td>
                            <div class="documents-dates">
                                <span class="documents-date-item" title="Ngày tạo"><i class="ph ph-plus-circle"></i> {{ date('d/m/Y', strtotime($doc->created_at)) }}</span>
                                @if($doc->published_at)
                                    <span class="documents-date-item documents-text-success" title="Ngày xuất bản"><i class="ph ph-check-circle"></i> {{ date('d/m/Y', strtotime($doc->published_at)) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="documents-text-center">
                            <div class="documents-actions">
                                <button class="documents-btn-icon documents-color-primary" title="Xem chi tiết" onclick="toggleDocumentModal('modal-doc-{{ $doc->id }}')"><i class="ph ph-eye"></i></button>
                                <button class="documents-btn-icon documents-color-info" title="Tải xuống"><i class="ph ph-download-simple"></i></button>

                                @if($doc->status === 'draft')
                                    <button class="documents-btn-icon documents-color-warning" title="Chỉnh sửa"><i class="ph ph-pencil-simple"></i></button>
                                    <button class="documents-btn-icon documents-color-success" title="Xuất bản"><i class="ph ph-upload-simple"></i></button>
                                    <button class="documents-btn-icon documents-color-danger" title="Xóa"><i class="ph ph-trash"></i></button>
                                @elseif($doc->status === 'published')
                                    <button class="documents-btn-icon documents-color-muted" title="Lưu trữ"><i class="ph ph-archive"></i></button>
                                @elseif($doc->status === 'archived')
                                    <button class="documents-btn-icon documents-color-success" title="Khôi phục/Xuất bản lại"><i class="ph ph-arrow-counter-clockwise"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <div class="documents-modal" id="modal-doc-{{ $doc->id }}">
                        <div class="documents-modal-overlay" onclick="toggleDocumentModal('modal-doc-{{ $doc->id }}')"></div>
                        <div class="documents-modal-content">
                            <div class="documents-modal-header">
                                <h3 class="documents-modal-title">Chi tiết tài liệu</h3>
                                <button class="documents-modal-close" onclick="toggleDocumentModal('modal-doc-{{ $doc->id }}')"><i class="ph ph-x"></i></button>
                            </div>
                            <div class="documents-modal-body">
                                <div class="documents-modal-grid">
                                    <div class="documents-modal-main">
                                        <h2 class="documents-modal-heading">{{ $doc->title }}</h2>

                                        <div class="documents-meta-row">
                                            <span class="documents-tag {{ $doc->category_config['class'] }}">
                                                {{ $doc->category_config['label'] }}
                                            </span>
                                            <span class="documents-badge {{ $doc->status_config['class'] }}">
                                                {{ $doc->status_config['label'] }}
                                            </span>
                                        </div>

                                        <div class="documents-desc-box">
                                            <label>Mô tả tài liệu</label>
                                            <p>{{ $doc->description }}</p>
                                        </div>

                                        <div class="documents-file-preview">
                                            <div class="documents-file-preview-icon">
                                                <i class="ph {{ $doc->file_icon }}"></i>
                                            </div>
                                            <div class="documents-file-preview-info">
                                                <div class="documents-file-name">{{ $doc->file_info->name }}</div>
                                                <div class="documents-file-meta">
                                                    <span>Định dạng: <strong>{{ $doc->file_info->format }}</strong></span>
                                                    <span class="documents-dot-sep">•</span>
                                                    <span>Dung lượng: <strong>{{ $doc->file_info->size }}</strong></span>
                                                </div>
                                            </div>
                                            <button class="documents-btn documents-btn-outline"><i class="ph ph-download-simple"></i> Tải file</button>
                                        </div>
                                    </div>

                                    <div class="documents-modal-sidebar">
                                        <div class="documents-sidebar-block">
                                            <h4>Thông tin chung</h4>
                                            <div class="documents-info-list">
                                                <div class="documents-info-item">
                                                    <span class="documents-info-lbl">Người tạo:</span>
                                                    <span class="documents-info-val"><i class="ph ph-user"></i> {{ $doc->author->name }}</span>
                                                </div>
                                                <div class="documents-info-item">
                                                    <span class="documents-info-lbl">Ngày tạo:</span>
                                                    <span class="documents-info-val"><i class="ph ph-calendar-plus"></i> {{ date('d/m/Y H:i', strtotime($doc->created_at)) }}</span>
                                                </div>
                                                <div class="documents-info-item">
                                                    <span class="documents-info-lbl">Ngày xuất bản:</span>
                                                    <span class="documents-info-val">
                                                        @if($doc->published_at)
                                                            <i class="ph ph-check-circle documents-text-success"></i> {{ date('d/m/Y H:i', strtotime($doc->published_at)) }}
                                                        @else
                                                            <span class="documents-text-muted">Chưa xuất bản</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="documents-modal-footer">
                                <button class="documents-btn documents-btn-outline" onclick="toggleDocumentModal('modal-doc-{{ $doc->id }}')">Đóng</button>
                                @if($doc->status === 'draft')
                                    <button class="documents-btn documents-btn-primary"><i class="ph ph-pencil-simple"></i> Chỉnh sửa</button>
                                    <button class="documents-btn documents-btn-success"><i class="ph ph-upload-simple"></i> Xuất bản ngay</button>
                                @elseif($doc->status === 'published')
                                    <button class="documents-btn documents-btn-muted"><i class="ph ph-archive"></i> Chuyển vào lưu trữ</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="documents-empty-state">
                                <i class="ph ph-file-dashed"></i>
                                <p>Chưa có tài liệu nào trong hệ thống.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="pagination-wrapper">
                 {{ $documents->links('frontend.components.pagination') }}
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        function toggleDocumentModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('active');
            }
        }
    </script>
@endpush