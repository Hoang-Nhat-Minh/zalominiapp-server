@extends('layouts.main')

@section('title', 'Thêm danh mục tin tức - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper">
        <div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h1 style="font-size: 24px; font-weight: 600; color: var(--text-main); margin-bottom: 4px;">Thêm danh mục tin tức mới</h1>
                <p style="color: var(--text-muted); font-size: 14px;">Tạo danh mục mới để phân loại các bài viết, thông báo phục vụ người dân.</p>
            </div>
            <a href="{{ route('news-categories.index') }}" class="admin-btn" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 10px 16px; border-radius: 6px; background: #E5E7EB; color: #374151; font-weight: 600; font-size: 14px;">
                <i class="ph ph-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        @if($errors->any())
            <div style="padding: 12px 16px; background-color: #FEF2F2; border: 1px solid #EF4444; color: #991B1B; border-radius: 6px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="gov-card" style="background: var(--surface); padding: 24px; border-radius: 8px; border: 1px solid var(--border); max-width: 700px;">
            <form action="{{ route('news-categories.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Tên danh mục <span style="color: #EF4444;">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Ví dụ: Tin chính quyền, Thông báo dân cư..." required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Mô tả danh mục</label>
                    <textarea name="description" rows="4" placeholder="Mô tả ngắn gọn mục đích của danh mục này..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px; resize: vertical;">{{ old('description') }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Thứ tự hiển thị</label>
                        <input type="number" name="order" value="{{ old('order', 0) }}" min="0" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px;">
                    </div>
                    <div style="display: flex; align-items: center; margin-top: 24px;">
                        <label style="display: inline-flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer; color: var(--text-main);">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--primary);">
                            Kích hoạt danh mục này
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="{{ route('news-categories.index') }}" class="admin-btn" style="padding: 10px 20px; border-radius: 6px; background: #E5E7EB; color: #374151; text-decoration: none; font-weight: 600;">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="admin-btn admin-btn-primary" style="padding: 10px 24px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;">
                        <i class="ph ph-floppy-disk"></i> Lưu danh mục
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
