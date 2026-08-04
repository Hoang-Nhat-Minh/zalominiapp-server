@extends('layouts.main')

@section('title', 'Tạo bài viết tin tức mới - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper">
        <div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h1 style="font-size: 24px; font-weight: 600; color: var(--text-main); margin-bottom: 4px;">Tạo bài viết tin tức mới</h1>
                <p style="color: var(--text-muted); font-size: 14px;">Soạn thảo tin tức, thông báo hoặc hướng dẫn thủ tục để xuất bản tới ứng dụng Zalo Mini App.</p>
            </div>
            <a href="{{ route('news.index') }}" class="admin-btn" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 10px 16px; border-radius: 6px; background: #E5E7EB; color: #374151; font-weight: 600; font-size: 14px;">
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

        <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
                <!-- Main Form Body -->
                <div class="gov-card" style="background: var(--surface); padding: 24px; border-radius: 8px; border: 1px solid var(--border);">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Tiêu đề bài viết <span style="color: #EF4444;">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="Nhập tiêu đề tin tức, thông báo..." required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 15px; font-weight: 600;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Tóm tắt ngắn (Summary)</label>
                        <textarea name="summary" rows="3" placeholder="Tóm tắt nội dung chính hiển thị ở màn hình danh sách..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px; resize: vertical;">{{ old('summary') }}</textarea>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Nội dung chi tiết bài viết <span style="color: #EF4444;">*</span></label>
                        <textarea name="content" rows="12" placeholder="Nhập nội dung đầy đủ bài viết..." required style="width: 100%; padding: 12px 14px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px; line-height: 1.6; resize: vertical;">{{ old('content') }}</textarea>
                    </div>
                </div>

                <!-- Sidebar Settings -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div class="gov-card" style="background: var(--surface); padding: 20px; border-radius: 8px; border: 1px solid var(--border);">
                        <h3 style="font-size: 16px; font-weight: 600; color: var(--text-main); margin-bottom: 16px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">Thiết lập bài viết</h3>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-main); font-size: 13px;">Danh mục tin tức</label>
                            <select name="news_category_id" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px; background: white;">
                                <option value="">Chưa chọn danh mục</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('news_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-main); font-size: 13px;">Trạng thái xuất bản</label>
                            <select name="status" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px; background: white;">
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Xuất bản ngay</option>
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Lưu bản nháp</option>
                                <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-main); font-size: 13px;">Thời gian xuất bản</label>
                            <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 13px;">
                        </div>

                        <div style="margin-top: 16px;">
                            <label style="display: inline-flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer; color: var(--text-main); font-size: 14px;">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--primary);">
                                Đánh dấu Tin nổi bật (Pinned)
                            </label>
                        </div>
                    </div>

                    <div class="gov-card" style="background: var(--surface); padding: 20px; border-radius: 8px; border: 1px solid var(--border);">
                        <h3 style="font-size: 16px; font-weight: 600; color: var(--text-main); margin-bottom: 16px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">Hình ảnh đại diện</h3>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-main); font-size: 13px;">Tải ảnh lên từ máy tính</label>
                            <input type="file" name="image_file" accept="image/*" style="width: 100%; font-size: 13px;">
                        </div>

                        <div style="margin-bottom: 8px;">
                            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-main); font-size: 13px;">Hoặc dán URL ảnh có sẵn</label>
                            <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 13px;">
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 8px;">
                        <a href="{{ route('news.index') }}" class="admin-btn" style="padding: 10px 16px; border-radius: 6px; background: #E5E7EB; color: #374151; text-decoration: none; font-weight: 600; text-align: center; flex: 1;">
                            Hủy
                        </a>
                        <button type="submit" class="admin-btn admin-btn-primary" style="padding: 10px 20px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer; flex: 2; display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <i class="ph ph-paper-plane-right"></i> Đăng bài viết
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection
