@extends('layouts.main')

@section('title', 'Quản lý bài viết tin tức - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper news-wrapper">
        <div class="news-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div class="news-header-info">
                <h1 class="news-title" style="font-size: 24px; font-weight: 600; color: var(--text-main); margin-bottom: 4px;">Quản lý bài viết tin tức</h1>
                <p class="news-subtitle" style="color: var(--text-muted); font-size: 14px;">Tạo mới, biên tập và xuất bản tin tức, thông báo, truyền thông chính quyền tới người dân qua Zalo Mini App.</p>
            </div>
            <a href="{{ route('news.create') }}" class="admin-btn admin-btn-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">
                <i class="ph ph-plus-circle" style="font-size: 18px;"></i> Viết bài mới
            </a>
        </div>

        @if(session('success'))
            <div style="padding: 12px 16px; background-color: #ECFDF5; border: 1px solid #10B981; color: #065F46; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="ph ph-check-circle" style="font-size: 18px;"></i>
                <strong>{{ session('success') }}</strong>
            </div>
        @endif

        <div class="news-stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #E8F0FE; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-newspaper"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Tổng bài viết</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->total }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #ECFDF5; color: #10B981; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-check-circle"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Đã xuất bản</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->published }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #FFF9C4; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-pencil-line"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Bản nháp</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->draft }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #F5F3FF; color: #8B5CF6; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-star"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Tin nổi bật</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->featured }}</div>
                </div>
            </div>
        </div>

        <div class="news-filter-bar" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <form action="{{ route('news.index') }}" method="GET" style="display: flex; align-items: center; gap: 12px; flex: 1; flex-wrap: wrap;">
                <div style="position: relative; display: flex; align-items: center; min-width: 280px; flex: 1;">
                    <i class="ph ph-magnifying-glass" style="position: absolute; left: 12px; color: var(--text-muted); font-size: 18px;"></i>
                    <input type="text" name="search" placeholder="Tìm kiếm theo tiêu đề, tóm tắt, nội dung..." value="{{ request('search') }}" style="width: 100%; padding: 8px 12px 8px 40px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px;">
                </div>
                <select name="category_id" style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px; background: white; min-width: 160px;">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="status" style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px; background: white; min-width: 140px;">
                    <option value="">Tất cả trạng thái</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                </select>
                <button type="submit" class="admin-btn admin-btn-primary" style="padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px; border: none; cursor: pointer;">
                    <i class="ph ph-funnel"></i> Lọc dữ liệu
                </button>
                @if(request()->anyFilled(['search', 'category_id', 'status']))
                    <a href="{{ route('news.index') }}" class="admin-btn" style="padding: 8px 16px; border-radius: 6px; background: #e0e0e0; color: #333; text-decoration: none; font-weight: 600; font-size: 14px;">
                        Xóa lọc
                    </a>
                @endif
            </form>
        </div>

        <div class="gov-card" style="background: var(--surface); border-radius: 8px; border: 1px solid var(--border); overflow: hidden;">
            <table class="admin-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: var(--background); border-bottom: 1px solid var(--border);">
                        <th width="4%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">STT</th>
                        <th width="8%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Hình ảnh</th>
                        <th width="35%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Tiêu đề bài viết</th>
                        <th width="14%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Danh mục</th>
                        <th width="12%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Ngày đăng</th>
                        <th width="9%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Lượt xem</th>
                        <th width="9%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Trạng thái</th>
                        <th width="9%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main); text-align: right;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($newsList as $index => $item)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px 16px; font-size: 14px;">{{ $newsList->firstItem() + $index }}</td>
                            <td style="padding: 12px 16px;">
                                @if($item->image)
                                    <img src="{{ $item->image }}" alt="{{ $item->title }}" style="width: 54px; height: 38px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border);">
                                @else
                                    <div style="width: 54px; height: 38px; background: #F3F4F6; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #9CA3AF;">
                                        <i class="ph ph-image" style="font-size: 20px;"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 12px 16px;">
                                <div style="font-weight: 600; color: var(--text-main); font-size: 14px; line-height: 1.4;">
                                    <a href="{{ route('news.show', $item->id) }}" style="color: var(--text-main); text-decoration: none;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-main)'">
                                        {{ $item->title }}
                                    </a>
                                    @if($item->is_featured)
                                        <span style="display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: 700; background: #F5F3FF; color: #8B5CF6; margin-left: 6px;">
                                            <i class="ph ph-star-fill"></i> Nổi bật
                                        </span>
                                    @endif
                                </div>
                                @if($item->summary)
                                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $item->summary }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 12px 16px;">
                                <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #E8F0FE; color: var(--primary);">
                                    {{ $item->newsCategory ? $item->newsCategory->name : 'Tin tức' }}
                                </span>
                            </td>
                            <td style="padding: 12px 16px; font-size: 13px; color: var(--text-muted);">
                                {{ $item->published_at ? $item->published_at->format('H:i d/m/Y') : '—' }}
                            </td>
                            <td style="padding: 12px 16px; font-size: 13px; font-weight: 600; color: var(--text-main);">
                                <i class="ph ph-eye" style="color: var(--text-muted);"></i> {{ number_format($item->views_count ?? 0) }}
                            </td>
                            <td style="padding: 12px 16px;">
                                @if($item->status === 'published')
                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #ECFDF5; color: #10B981;">
                                        Xuất bản
                                    </span>
                                @elseif($item->status === 'draft')
                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #FFF9C4; color: #D97706;">
                                        Bản nháp
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #F3F4F6; color: #6B7280;">
                                        Lưu trữ
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 12px 16px; text-align: right;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    <a href="{{ route('news.show', $item->id) }}" title="Xem chi tiết" style="padding: 6px; border-radius: 4px; background: #F3F4F6; color: #4B5563; text-decoration: none;">
                                        <i class="ph ph-eye" style="font-size: 16px;"></i>
                                    </a>
                                    <a href="{{ route('news.edit', $item->id) }}" title="Chỉnh sửa" style="padding: 6px; border-radius: 4px; background: #E8F0FE; color: var(--primary); text-decoration: none;">
                                        <i class="ph ph-pencil-simple" style="font-size: 16px;"></i>
                                    </a>
                                    <form action="{{ route('news.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');" style="display: inline;">
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
                            <td colspan="8" style="padding: 40px; text-align: center; color: var(--text-muted);">
                                <i class="ph ph-article" style="font-size: 40px; margin-bottom: 8px; display: block;"></i>
                                Chưa có bài viết tin tức nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($newsList->hasPages())
            <div style="margin-top: 20px;">
                {{ $newsList->links() }}
            </div>
        @endif
    </main>
@endsection
