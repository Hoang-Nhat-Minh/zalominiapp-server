@extends('layouts.main')

@section('title', 'Quản lý khảo sát dân cư - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper surveys-wrapper">
        <div class="surveys-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div class="surveys-header-info">
                <h1 class="surveys-title" style="font-size: 24px; font-weight: 600; color: var(--text-main); margin-bottom: 4px;">Quản lý & Biểu mẫu Khảo sát Dân cư</h1>
                <p class="surveys-subtitle" style="color: var(--text-muted); font-size: 14px;">Tạo phiếu điều tra, lấy ý kiến nhân dân toàn phường hoặc theo từng Tổ dân phố.</p>
            </div>
            <a href="{{ route('surveys.create') }}" class="admin-btn admin-btn-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">
                <i class="ph ph-plus-circle" style="font-size: 18px;"></i> Tạo phiếu khảo sát mới
            </a>
        </div>

        @if(session('success'))
            <div style="padding: 12px 16px; background-color: #ECFDF5; border: 1px solid #10B981; color: #065F46; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="ph ph-check-circle" style="font-size: 18px;"></i>
                <strong>{{ session('success') }}</strong>
            </div>
        @endif

        <div class="surveys-stats-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 44px; height: 44px; border-radius: 8px; background: #E8F0FE; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 22px;"><i class="ph ph-clipboard-text"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Tổng số phiếu khảo sát</div>
                    <div style="font-size: 22px; font-weight: 700; color: var(--text-main);">{{ $stats->total }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 44px; height: 44px; border-radius: 8px; background: #ECFDF5; color: #10B981; display: flex; align-items: center; justify-content: center; font-size: 22px;"><i class="ph ph-broadcast"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Đang mở khảo sát</div>
                    <div style="font-size: 22px; font-weight: 700; color: var(--text-main);">{{ $stats->active }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 44px; height: 44px; border-radius: 8px; background: #F5F3FF; color: #8B5CF6; display: flex; align-items: center; justify-content: center; font-size: 22px;"><i class="ph ph-users-three"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Tổng số lượt dân tham gia</div>
                    <div style="font-size: 22px; font-weight: 700; color: var(--text-main);">{{ $stats->responses }}</div>
                </div>
            </div>
        </div>

        <div class="surveys-filter-bar" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <form action="{{ route('surveys.index') }}" method="GET" style="display: flex; align-items: center; gap: 12px; flex: 1; flex-wrap: wrap;">
                <div style="position: relative; display: flex; align-items: center; min-width: 280px; flex: 1;">
                    <i class="ph ph-magnifying-glass" style="position: absolute; left: 12px; color: var(--text-muted); font-size: 18px;"></i>
                    <input type="text" name="search" placeholder="Tìm tên cuộc khảo sát, mô tả, TDP..." value="{{ request('search') }}" style="width: 100%; padding: 8px 12px 8px 40px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px;">
                </div>
                <button type="submit" class="admin-btn admin-btn-primary" style="padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px; border: none; cursor: pointer;">
                    <i class="ph ph-funnel"></i> Lọc khảo sát
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('surveys.index') }}" class="admin-btn" style="padding: 8px 16px; border-radius: 6px; background: #e0e0e0; color: #333; text-decoration: none; font-weight: 600; font-size: 14px;">
                        Xóa lọc
                    </a>
                @endif
            </form>
        </div>

        <div class="gov-card" style="background: var(--surface); border-radius: 8px; border: 1px solid var(--border); overflow: hidden;">
            <table class="admin-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: var(--background); border-bottom: 1px solid var(--border);">
                        <th width="5%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">STT</th>
                        <th width="30%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Tiêu đề phiếu khảo sát</th>
                        <th width="15%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Phạm vi đối tượng</th>
                        <th width="10%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Số câu hỏi</th>
                        <th width="15%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Lượt hoàn thành</th>
                        <th width="12%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Trạng thái</th>
                        <th width="13%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main); text-align: right;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surveys as $index => $item)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px 16px; font-size: 14px;">{{ $surveys->firstItem() + $index }}</td>
                            <td style="padding: 12px 16px;">
                                <a href="{{ route('surveys.show', $item->id) }}" style="font-weight: 600; color: var(--text-main); font-size: 14px; text-decoration: none;">
                                    {{ $item->title }}
                                </a>
                                @if($item->deadline)
                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                        <i class="ph ph-clock"></i> Hạn chót: {{ $item->deadline->format('H:i d/m/Y') }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 12px 16px;">
                                <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #EBF1FF; color: #0057FF;">
                                    <i class="ph ph-users"></i> {{ $item->target_label }}
                                </span>
                            </td>
                            <td style="padding: 12px 16px; font-size: 14px; font-weight: 600; color: var(--text-main);">
                                {{ $item->questions_count }} câu
                            </td>
                            <td style="padding: 12px 16px;">
                                <span style="font-size: 15px; font-weight: 700; color: #10B981;">
                                    {{ $item->responses_count }} lượt
                                </span>
                            </td>
                            <td style="padding: 12px 16px;">
                                @if($item->is_active)
                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #ECFDF5; color: #10B981;">
                                        Đang mở
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #F3F4F6; color: #6B7280;">
                                        Đóng/Đã ẩn
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 12px 16px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a href="{{ route('surveys.show', $item->id) }}" title="Xem kết quả & Thống kê" style="padding: 6px 10px; border-radius: 4px; background: #E8F0FE; color: var(--primary); text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="ph ph-chart-bar"></i> Kết quả
                                    </a>
                                    <form action="{{ route('surveys.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài khảo sát này?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Xóa" style="padding: 6px; border-radius: 4px; background: #FFEBEE; color: #EF4444; border: none; cursor: pointer;">
                                            <i class="ph ph-trash" style="font-size: 16px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 40px; text-align: center; color: var(--text-muted);">
                                <i class="ph ph-clipboard" style="font-size: 40px; margin-bottom: 8px; display: block;"></i>
                                Chưa có phiếu khảo sát dân cư nào. Bấm "Tạo phiếu khảo sát mới" để phát hành.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($surveys->hasPages())
            <div style="margin-top: 20px;">
                {{ $surveys->links() }}
            </div>
        @endif
    </main>
@endsection
