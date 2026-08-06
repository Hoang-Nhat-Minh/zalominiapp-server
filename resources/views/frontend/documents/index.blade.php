@extends('layouts.main')

@section('title', 'Tài liệu dịch vụ công - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper documents-wrapper">
        @if(session('success'))
            <div style="background: #DEF7EC; color: #03543F; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; display: flex; align-items: center; justify-content: space-between;">
                <span><i class="ph ph-check-circle" style="font-size: 18px; vertical-align: middle; margin-right: 6px;"></i> {{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div style="background: #FDE8E8; color: #9B1C1C; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
                <i class="ph ph-x-circle" style="font-size: 18px; vertical-align: middle; margin-right: 6px;"></i> {{ session('error') }}
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

        <div class="documents-header">
            <div class="documents-header-info">
                <h1 class="documents-title">Tài liệu dịch vụ công</h1>
                <p class="documents-subtitle">Quản lý kho tài liệu, văn bản và biểu mẫu điện tử phục vụ người dân và doanh nghiệp.</p>
            </div>
            <button class="documents-btn documents-btn-primary" onclick="toggleDocumentModal('modal-create-document')">
                <i class="ph ph-plus-circle"></i> Thêm tài liệu
            </button>
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
            <form action="{{ route('documents') }}" method="GET" style="display: flex; justify-content: space-between; align-items: center; width: 100%; gap: 16px; flex-wrap: wrap;">
                <div class="documents-filter-group" style="flex: 1; flex-wrap: wrap;">
                    <div class="documents-search">
                        <i class="ph ph-magnifying-glass"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm theo tên tài liệu...">
                    </div>
                    <select class="documents-select" name="category">
                        <option value="">Tất cả danh mục</option>
                        <option value="meeting" {{ request('category') == 'meeting' ? 'selected' : '' }}>Họp chi bộ</option>
                        <option value="directive" {{ request('category') == 'directive' ? 'selected' : '' }}>Chỉ thị</option>
                        <option value="resolution" {{ request('category') == 'resolution' ? 'selected' : '' }}>Nghị quyết</option>
                        <option value="report" {{ request('category') == 'report' ? 'selected' : '' }}>Báo cáo</option>
                        <option value="form" {{ request('category') == 'form' ? 'selected' : '' }}>Biểu mẫu</option>
                        <option value="guide" {{ request('category') == 'guide' ? 'selected' : '' }}>Hướng dẫn</option>
                    </select>
                    <select class="documents-select" name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                    </select>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="documents-btn documents-btn-outline"><i class="ph ph-funnel"></i> Lọc dữ liệu</button>
                    @if(request()->hasAny(['search', 'category', 'status']))
                        <a href="{{ route('documents') }}" class="documents-btn" style="background: #e0e0e0; color: #333; text-decoration: none; padding: 8px 14px; border-radius: 6px; font-weight: 600; font-size: 14px;">Bỏ lọc</a>
                    @endif
                </div>
            </form>
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
                        <td class="documents-text-center">{{ $documents->firstItem() + $index }}</td>
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
                                <span class="documents-author-name">{{ $doc->author?->full_name ?? ($doc->author?->name ?? 'Ban Quản Trị') }}</span>
                                <span class="documents-author-role">{{ $doc->author?->role ?? 'Cán bộ' }}</span>
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
                                
                                <a href="{{ route('documents.download', $doc->id) }}" class="documents-btn-icon documents-color-info" title="Tải xuống" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="ph ph-download-simple"></i>
                                </a>

                                @if($doc->status === 'draft')
                                    <form action="{{ route('documents.updateStatus', $doc->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="published">
                                        <button type="submit" class="documents-btn-icon documents-color-success" title="Xuất bản ngay"><i class="ph ph-upload-simple"></i></button>
                                    </form>
                                    <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài liệu này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="documents-btn-icon documents-color-danger" title="Xóa"><i class="ph ph-trash"></i></button>
                                    </form>
                                @elseif($doc->status === 'published')
                                    <form action="{{ route('documents.updateStatus', $doc->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="archived">
                                        <button type="submit" class="documents-btn-icon documents-color-muted" title="Lưu trữ"><i class="ph ph-archive"></i></button>
                                    </form>
                                @elseif($doc->status === 'archived')
                                    <form action="{{ route('documents.updateStatus', $doc->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="published">
                                        <button type="submit" class="documents-btn-icon documents-color-success" title="Xuất bản lại"><i class="ph ph-arrow-counter-clockwise"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <div class="documents-modal" id="modal-doc-{{ $doc->id }}">
                        <div class="documents-modal-overlay" onclick="toggleDocumentModal('modal-doc-{{ $doc->id }}')"></div>
                        <div class="documents-modal-content">
                            <div class="documents-modal-header">
                                <h3 class="documents-modal-title">Chi tiết tài liệu #{{ $doc->id }}</h3>
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
                                            <p>{{ $doc->description ?: 'Không có mô tả nội dung.' }}</p>
                                        </div>

                                        <div class="documents-file-preview">
                                            <div class="documents-file-preview-icon">
                                                <i class="ph {{ $doc->file_icon }}"></i>
                                            </div>
                                            <div class="documents-file-preview-info">
                                                <div class="documents-file-name">{{ $doc->file_info->name }}</div>
                                                <div class="documents-file-meta">
                                                    <span>Định dạng: <strong>{{ strtoupper($doc->file_info->format) }}</strong></span>
                                                </div>
                                            </div>
                                            <a href="{{ route('documents.download', $doc->id) }}" class="documents-btn documents-btn-outline" style="text-decoration: none;"><i class="ph ph-download-simple"></i> Tải file</a>
                                        </div>
                                    </div>

                                    <div class="documents-modal-sidebar">
                                        <div class="documents-sidebar-block">
                                            <h4>Thông tin chung</h4>
                                            <div class="documents-info-list">
                                                <div class="documents-info-item">
                                                    <span class="documents-info-lbl">Người tạo:</span>
                                                    <span class="documents-info-val"><i class="ph ph-user"></i> {{ $doc->author?->full_name ?? ($doc->author?->name ?? 'Ban Quản Trị') }}</span>
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
                                    <form action="{{ route('documents.updateStatus', $doc->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="published">
                                        <button type="submit" class="documents-btn documents-btn-success"><i class="ph ph-upload-simple"></i> Xuất bản ngay</button>
                                    </form>
                                @elseif($doc->status === 'published')
                                    <form action="{{ route('documents.updateStatus', $doc->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="archived">
                                        <button type="submit" class="documents-btn documents-btn-muted"><i class="ph ph-archive"></i> Chuyển vào lưu trữ</button>
                                    </form>
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

        <!-- Modal Thêm Tài Liệu Mới -->
        <div class="documents-modal" id="modal-create-document">
            <div class="documents-modal-overlay" onclick="toggleDocumentModal('modal-create-document')"></div>
            <div class="documents-modal-content" style="max-width: 600px;">
                <div class="documents-modal-header">
                    <h3 class="documents-modal-title">Thêm mới tài liệu dịch vụ công</h3>
                    <button class="documents-modal-close" onclick="toggleDocumentModal('modal-create-document')"><i class="ph ph-x"></i></button>
                </div>
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="documents-modal-body" style="display: flex; flex-direction: column; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Tiêu đề tài liệu <span style="color:red;">*</span></label>
                            <input type="text" name="title" required placeholder="Nhập tiêu đề hoặc tên biểu mẫu/văn bản..." style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Danh mục <span style="color:red;">*</span></label>
                                <select name="category" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px; background: white;">
                                    <option value="">-- Chọn danh mục --</option>
                                    <option value="meeting">Họp chi bộ</option>
                                    <option value="directive">Chỉ thị</option>
                                    <option value="resolution">Nghị quyết</option>
                                    <option value="report">Báo cáo</option>
                                    <option value="form">Biểu mẫu</option>
                                    <option value="guide">Hướng dẫn</option>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Trạng thái <span style="color:red;">*</span></label>
                                <select name="status" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px; background: white;">
                                    <option value="published">Đã xuất bản (Công khai)</option>
                                    <option value="draft">Bản nháp</option>
                                    <option value="archived">Lưu trữ</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">File tài liệu đính kèm (.pdf, .doc, .docx, .xls, .xlsx... Tối đa 10MB) <span style="color:red;">*</span></label>
                            <input type="file" name="file" required style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px; background: white;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Mô tả chi tiết nội dung</label>
                            <textarea name="description" rows="3" placeholder="Mô tả tóm tắt nội dung tài liệu..." style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;"></textarea>
                        </div>
                    </div>
                    <div class="documents-modal-footer">
                        <button type="button" class="documents-btn documents-btn-outline" onclick="toggleDocumentModal('modal-create-document')">Hủy</button>
                        <button type="submit" class="documents-btn documents-btn-primary"><i class="ph ph-upload-simple"></i> Tải lên & Lưu tài liệu</button>
                    </div>
                </form>
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