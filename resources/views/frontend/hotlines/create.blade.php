@extends('layouts.main')

@section('title', 'Thêm số Hotline mới - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper hotlines-wrapper">
        <div class="hotlines-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h1 style="font-size: 24px; font-weight: 600; color: var(--text-main); margin-bottom: 4px;">Thêm mới đường dây nóng (Hotline)</h1>
                <p style="color: var(--text-muted); font-size: 14px;">Tạo thông tin liên hệ khẩn cấp hiển thị trực tiếp trên Zalo Mini App cho công dân.</p>
            </div>
            <a href="{{ route('hotlines.index') }}" class="admin-btn" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 10px 16px; border-radius: 6px; background: #e0e0e0; color: #333; font-weight: 600; font-size: 14px;">
                <i class="ph ph-arrow-left"></i> Quay lại
            </a>
        </div>

        @if ($errors->any())
            <div style="padding: 12px 16px; background-color: #FEF2F2; border: 1px solid #EF4444; color: #991B1B; border-radius: 6px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="gov-card" style="background: var(--surface); padding: 24px; border-radius: 8px; border: 1px solid var(--border); max-width: 800px;">
            <form action="{{ route('hotlines.store') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div style="grid-column: span 2;">
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Tên đơn vị / Tên liên hệ <span style="color: red;">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Ví dụ: Công an Phường, Trạm Y tế, Tổ trưởng TDP 1..." required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;">
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Phân loại đường dây nóng <span style="color: red;">*</span></label>
                        <select name="category" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; background: white;">
                            <option value="police" {{ old('category') === 'police' ? 'selected' : '' }}>Công an / Giữ gìn ANTT</option>
                            <option value="medical" {{ old('category') === 'medical' ? 'selected' : '' }}>Y tế / Cấp cứu</option>
                            <option value="rescue" {{ old('category') === 'rescue' ? 'selected' : '' }}>PCCC / Cứu hộ khẩn cấp</option>
                            <option value="tdp" {{ old('category') === 'tdp' ? 'selected' : '' }}>Tổ trưởng Tổ dân phố</option>
                            <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>Khác / Tổng đài tư vấn</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Số điện thoại quay số <span style="color: red;">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Ví dụ: 0243.825.xxxx, 115, 0912345678" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;">
                    </div>

                    <div style="grid-column: span 2;">
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Địa chỉ / Vị trí trụ sở</label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="Số nhà, đường, khu dân cư..." style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;">
                    </div>

                    <div style="grid-column: span 2;">
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Mô tả chi tiết / Lịch trực ban</label>
                        <textarea name="description" rows="3" placeholder="Nhập mô tả nhiệm vụ, giờ trực ban, hướng dẫn liên hệ..." style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Thứ tự hiển thị</label>
                        <input type="number" name="order" value="{{ old('order', 0) }}" min="0" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;">
                    </div>

                    <div style="display: flex; align-items: center; margin-top: 24px;">
                        <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px; cursor: pointer;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width: 18px; height: 18px;">
                            Hiển thị ngay trên Mini App người dân
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px; border-top: 1px solid var(--border); padding-top: 16px;">
                    <a href="{{ route('hotlines.index') }}" class="admin-btn" style="padding: 10px 20px; border-radius: 6px; background: #e0e0e0; color: #333; text-decoration: none; font-weight: 600;">Hủy bỏ</a>
                    <button type="submit" class="admin-btn admin-btn-primary" style="padding: 10px 24px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;">Lưu Hotline</button>
                </div>
            </form>
        </div>
    </main>
@endsection
