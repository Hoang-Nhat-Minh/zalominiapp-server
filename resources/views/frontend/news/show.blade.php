@extends('layouts.main')

@section('title', $news->title . ' - Xem bài viết')

@section('content')
    <main class="admin-content-wrapper">
        <div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                    <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #E8F0FE; color: var(--primary);">
                        {{ $news->newsCategory ? $news->newsCategory->name : 'Tin tức' }}
                    </span>
                    @if($news->is_featured)
                        <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 700; background: #F5F3FF; color: #8B5CF6;">
                            <i class="ph ph-star-fill"></i> Tin nổi bật
                        </span>
                    @endif
                </div>
                <h1 style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.3;">{{ $news->title }}</h1>
                <div style="display: flex; align-items: center; gap: 16px; margin-top: 8px; color: var(--text-muted); font-size: 13px;">
                    <span><i class="ph ph-calendar"></i> {{ $news->published_at ? $news->published_at->format('H:i, d/m/Y') : 'Chưa xuất bản' }}</span>
                    <span><i class="ph ph-user"></i> Tác giả: {{ $news->author ? $news->author->name : 'Quản trị viên' }}</span>
                    <span><i class="ph ph-eye"></i> {{ number_format($news->views_count ?? 0) }} lượt xem</span>
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('news.edit', $news->id) }}" class="admin-btn admin-btn-primary" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">
                    <i class="ph ph-pencil-simple"></i> Chỉnh sửa
                </a>
                <a href="{{ route('news.index') }}" class="admin-btn" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none; padding: 8px 16px; border-radius: 6px; background: #E5E7EB; color: #374151; font-weight: 600; font-size: 14px;">
                    <i class="ph ph-arrow-left"></i> Danh sách
                </a>
            </div>
        </div>

        <div class="gov-card" style="background: var(--surface); padding: 32px; border-radius: 8px; border: 1px solid var(--border); max-width: 900px; margin: 0 auto;">
            @if($news->summary)
                <div style="padding: 16px; background: #F8FAFC; border-left: 4px solid var(--primary); border-radius: 4px; font-weight: 600; color: #334155; margin-bottom: 24px; font-size: 15px; line-height: 1.6;">
                    {{ $news->summary }}
                </div>
            @endif

            @if($news->image)
                <div style="margin-bottom: 24px; text-align: center;">
                    <img src="{{ $news->image }}" alt="{{ $news->title }}" style="max-width: 100%; max-height: 450px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);">
                </div>
            @endif

            <div style="font-size: 16px; line-height: 1.8; color: var(--text-main); white-space: pre-line;">
                {!! nl2br(e($news->content)) !!}
            </div>
        </div>
    </main>
@endsection
