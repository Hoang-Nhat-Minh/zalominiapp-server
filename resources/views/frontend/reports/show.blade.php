@extends('layouts.main')

@section('title', 'Chi tiết phản ánh #' . $report->id)

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <style>
        .report-detail-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .report-detail-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }
        @media (max-width: 992px) {
            .report-detail-grid {
                grid-template-columns: 1fr;
            }
        }
        .report-field-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 4px;
        }
        .report-field-value {
            font-size: 15px;
            font-weight: 500;
            color: var(--text-main);
        }
        .report-img-thumb {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid var(--border);
            cursor: zoom-in;
            transition: transform 0.2s;
        }
        .report-img-thumb:hover {
            transform: scale(1.02);
        }
    </style>
@endpush

@section('content')
    <main class="admin-content-wrapper">
        @if(session('success'))
            <div style="background: #DEF7EC; color: #03543F; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
                <i class="ph ph-check-circle" style="font-size: 18px; vertical-align: middle; margin-right: 6px;"></i> {{ session('success') }}
            </div>
        @endif

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <h1 style="font-size: 24px; font-weight: 600; color: var(--text-main); margin: 0;">Phản ánh #{{ $report->id }}: {{ $report->title }}</h1>
                    @php
                        $cfg = $statusConfigs[$report->status] ?? ['class' => 'reports-badge-pending', 'label' => 'Chờ tiếp nhận'];
                    @endphp
                    <span class="reports-badge {{ $cfg['class'] }}" style="padding: 4px 12px; font-size: 13px; border-radius: 12px;">{{ $cfg['label'] }}</span>
                </div>
                <p style="color: var(--text-muted); font-size: 14px; margin-top: 4px;">Gửi lúc {{ date('H:i d/m/Y', strtotime($report->created_at)) }} bởi công dân {{ $report->user->full_name ?? 'Ẩn danh' }}</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('digitalmaps') }}" class="admin-btn" style="text-decoration: none; padding: 10px 16px; border-radius: 6px; background: #e0e0e0; color: #333; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="ph ph-map-trifold"></i> Bản đồ số
                </a>
                <a href="{{ route('reports') }}" class="admin-btn" style="text-decoration: none; padding: 10px 16px; border-radius: 6px; background: #e0e0e0; color: #333; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="ph ph-arrow-left"></i> Danh sách PAHT
                </a>
            </div>
        </div>

        <div class="report-detail-grid">
            <!-- Cột trái: Thông tin chi tiết phản ánh -->
            <div>
                <div class="report-detail-card">
                    <h2 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 16px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                        <i class="ph ph-info"></i> Thông tin Phản ánh
                    </h2>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <div class="report-field-label">Danh mục phản ánh</div>
                            <div class="report-field-value" style="color: #0057FF; font-weight: 600;">
                                <i class="ph ph-tag"></i> {{ $report->category_label }}
                            </div>
                        </div>
                        <div>
                            <div class="report-field-label">Địa chỉ / Tọa độ</div>
                            <div class="report-field-value">
                                <i class="ph ph-map-pin" style="color: #EF4444;"></i> {{ $report->address ?: 'Không xác định' }}
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <div class="report-field-label">Nội dung chi tiết từ công dân</div>
                        <div style="background: #F8FAFC; border: 1px solid var(--border); border-radius: 6px; padding: 14px; font-size: 14px; color: #334155; line-height: 1.6; margin-top: 4px;">
                            {{ $report->description ?: 'Không có nội dung mô tả.' }}
                        </div>
                    </div>

                    @php
                        $images = is_array($report->images) ? $report->images : (json_decode($report->images, true) ?: []);
                    @endphp

                    @if(count($images) > 0)
                        <div>
                            <div class="report-field-label" style="margin-bottom: 8px;">Hình ảnh đính kèm hiện trường ({{ count($images) }})</div>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px;">
                                @foreach($images as $img)
                                    <a data-fancybox="gallery" href="{{ asset('storage/' . $img) }}">
                                        <img src="{{ asset('storage/' . $img) }}" class="report-img-thumb" alt="Hình ảnh hiện trường">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                @if($report->officer_note || $report->assigned_department)
                    <div class="report-detail-card">
                        <h2 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 16px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                            <i class="ph ph-notebook"></i> Nhật ký Xử lý & Kết quả
                        </h2>

                        <div style="margin-bottom: 12px;">
                            <div class="report-field-label">Bộ phận được phân công</div>
                            <div class="report-field-value" style="font-weight: 600;">
                                {{ $report->assigned_department_label }}
                            </div>
                        </div>

                        <div>
                            <div class="report-field-label">Ghi chú / Kết quả phản hồi</div>
                            <div style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 6px; padding: 12px; font-size: 14px; color: #92400E; margin-top: 4px;">
                                {{ $report->officer_note ?: 'Chưa có ghi chú xử lý.' }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Cột phải: Thông tin người gửi & Form cập nhật trạng thái -->
            <div>
                <!-- Thông tin công dân -->
                <div class="report-detail-card">
                    <h2 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 16px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                        <i class="ph ph-user"></i> Người phản ánh
                    </h2>

                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div>
                            <div class="report-field-label">Họ và tên</div>
                            <div class="report-field-value" style="font-weight: 600;">{{ $report->user->full_name ?? 'Công dân Phường' }}</div>
                        </div>
                        <div>
                            <div class="report-field-label">Số điện thoại</div>
                            <div class="report-field-value">{{ $report->user->phone ?? 'Chưa cập nhật' }}</div>
                        </div>
                        <div>
                            <div class="report-field-label">Mã định danh / CCCD</div>
                            <div class="report-field-value">{{ $report->user->citizen_code ?? 'Chưa cập nhật' }}</div>
                        </div>
                        <div>
                            <div class="report-field-label">Địa chỉ đăng ký</div>
                            <div class="report-field-value">{{ $report->user->address ?? 'Toàn phường' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Action Cập nhật -->
                <div class="report-detail-card">
                    <h2 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 16px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                        <i class="ph ph-gear"></i> Phân công & Cập nhật Trạng thái
                    </h2>

                    <form action="{{ route('reports.updateStatus', $report->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div style="display: flex; flex-direction: column; gap: 14px;">
                            <div>
                                <label class="report-field-label" style="display: block; margin-bottom: 6px;">Bộ phận chuyên trách</label>
                                <input type="text" name="assigned_department" value="{{ $report->assigned_department ?? $report->assigned_department_label }}" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                            </div>

                            <div>
                                <label class="report-field-label" style="display: block; margin-bottom: 6px;">Trạng thái xử lý</label>
                                <select name="status" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px; background: white;">
                                    <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Chờ tiếp nhận</option>
                                    <option value="processing" {{ $report->status === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>Đã xử lý & Công khai</option>
                                    <option value="rejected" {{ $report->status === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                                </select>
                            </div>

                            <div>
                                <label class="report-field-label" style="display: block; margin-bottom: 6px;">Ghi chú / Thao tác xử lý</label>
                                <textarea name="officer_note" rows="4" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;" placeholder="Nhập kết quả xử lý hoặc lý do từ chối...">{{ $report->officer_note }}</textarea>
                            </div>

                            <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%; justify-content: center; padding: 12px; font-weight: 600; font-size: 14px;">
                                <i class="ph ph-floppy-disk"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind('[data-fancybox]', {});
    </script>
@endpush
