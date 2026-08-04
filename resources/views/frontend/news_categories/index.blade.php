@extends('layouts.main')

@section('title', 'Quản lý danh mục tin tức - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper news-categories-wrapper">
        <div class="news-categories-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div class="news-categories-header-info">
                <h1 class="news-categories-title" style="font-size: 24px; font-weight: 600; color: var(--text-main); margin-bottom: 4px;">Danh mục tin tức</h1>
                <p class="news-categories-subtitle" style="color: var(--text-muted); font-size: 14px;">Quản lý các chuyên mục tin tức, thông báo, cảnh báo an ninh và hướng dẫn thủ tục công dân.</p>
            </div>
            <a href="{{ route('news-categories.create') }}" class="admin-btn admin-btn-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">
                <i class="ph ph-plus-circle" style="font-size: 18px;"></i> Thêm danh mục mới
            </a>
        </div>

        @if(session('success'))
            <div style="padding: 12px 16px; background-color: #ECFDF5; border: 1px solid #10B981; color: #065F46; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="ph ph-check-circle" style="font-size: 18px;"></i>
                <strong>{{ session('success') }}</strong>
            </div>
        @endif

        @if(session('error'))
            <div style="padding: 12px 16px; background-color: #FEF2F2; border: 1px solid #EF4444; color: #991B1B; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="ph ph-warning-circle" style="font-size: 18px;"></i>
                <strong>{{ session('error') }}</strong>
            </div>
        @endif

        <div class="stats-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #E8F0FE; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-folders"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Tổng số danh mục</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->total }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #ECFDF5; color: #10B981; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-check-circle"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Đang hoạt động</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->active }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #F3F4F6; color: #6B7280; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-eye-slash"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Đang tạm ẩn</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->inactive }}</div>
                </div>
            </div>
        </div>

        <div class="filter-bar" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <form action="{{ route('news-categories.index') }}" method="GET" style="display: flex; align-items: center; gap: 12px; flex: 1;">
                <div style="position: relative; display: flex; align-items: center; flex: 1; max-width: 400px;">
                    <i class="ph ph-magnifying-glass" style="position: absolute; left: 12px; color: var(--text-muted); font-size: 18px;"></i>
                    <input type="text" name="search" placeholder="Tìm tên hoặc mô tả danh mục..." value="{{ request('search') }}" style="width: 100%; padding: 8px 12px 8px 40px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px;">
                </div>
                <button type="submit" class="admin-btn admin-btn-primary" style="padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px; border: none; cursor: pointer;">
                    <i class="ph ph-funnel"></i> Tìm kiếm
                </button>
                @if(request('search'))
                    <a href="{{ route('news-categories.index') }}" class="admin-btn" style="padding: 8px 16px; border-radius: 6px; background: #e0e0e0; color: #333; text-decoration: none; font-weight: 600; font-size: 14px;">
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
                        <th width="25%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Tên danh mục</th>
                        <th width="20%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Mã đường dẫn (Slug)</th>
                        <th width="25%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Mô tả</th>
                        <th width="10%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Số bài viết</th>
                        <th width="10%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Trạng thái</th>
                        <th width="5%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main); text-align: right;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $item)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px 16px; font-size: 14px;">{{ $categories->firstItem() + $index }}</td>
                            <td style="padding: 12px 16px;">
                                <div style="font-weight: 600; color: var(--text-main); font-size: 15px;">{{ $item->name }}</div>
                            </td>
                            <td style="padding: 12px 16px; font-size: 13px; color: var(--text-muted); font-family: monospace;">
                                {{ $item->slug }}
                            </td>
                            <td style="padding: 12px 16px; font-size: 13px; color: var(--text-muted);">
                                {{ $item->description ?? '—' }}
                            </td>
                            <td style="padding: 12px 16px;">
                                <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 700; background: #E8F0FE; color: var(--primary);">
                                    {{ $item->posts_count }} bài viết
                                </span>
                            </td>
                            <td style="padding: 12px 16px;">
                                @if($item->is_active)
                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #ECFDF5; color: #10B981;">
                                        Đang hiện
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #F3F4F6; color: #6B7280;">
                                        Đã ẩn
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 12px 16px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a href="{{ route('news-categories.edit', $item->id) }}" title="Chỉnh sửa" style="padding: 6px; border-radius: 4px; background: #E8F0FE; color: var(--primary); text-decoration: none;">
                                        <i class="ph ph-pencil-simple" style="font-size: 16px;"></i>
                                    </a>
                                    <form action="{{ route('news-categories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');" style="display: inline;">
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
                                <i class="ph ph-folder-notch-open" style="font-size: 40px; margin-bottom: 8px; display: block;"></i>
                                Chưa có danh mục tin tức nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div style="margin-top: 20px;">
                {{ $categories->links() }}
            </div>
        @endif
    </main>
@endsection
