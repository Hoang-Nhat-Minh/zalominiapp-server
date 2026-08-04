@extends('layouts.main')

@section('title', 'Phát hành cảnh báo thời tiết mới - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper weather-wrapper">
        <div class="weather-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h1 style="font-size: 24px; font-weight: 600; color: var(--text-main); margin-bottom: 4px;">Tạo bản tin cảnh báo thời tiết cực đoan</h1>
                <p style="color: var(--text-muted); font-size: 14px;">Phát hành thông tin cảnh báo thiên tai, mưa bão, giông lốc hoặc nắng nóng tới dân cư.</p>
            </div>
            <a href="{{ route('weather-alerts.index') }}" class="admin-btn" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 10px 16px; border-radius: 6px; background: #e0e0e0; color: #333; font-weight: 600; font-size: 14px;">
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
            <form action="{{ route('weather-alerts.store') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div style="grid-column: span 2;">
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Tiêu đề bản tin cảnh báo <span style="color: red;">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="Ví dụ: Cảnh báo mưa lớn diện rộng & nguy cơ ngập lụt cục bộ..." required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;">
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Cấp độ cảnh báo <span style="color: red;">*</span></label>
                        <select name="level" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; background: white;">
                            <option value="warning" {{ old('level') === 'warning' ? 'selected' : '' }}>Cảnh báo (Mức vừa)</option>
                            <option value="danger" {{ old('level') === 'danger' ? 'selected' : '' }}>Cực đoan - Nguy hiểm khẩn cấp (Mức đỏ)</option>
                            <option value="info" {{ old('level') === 'info' ? 'selected' : '' }}>Thông tin thời tiết chung (Mức xanh)</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Khu vực ảnh hưởng <span style="color: red;">*</span></label>
                        <input type="text" name="area" value="{{ old('area', 'Toàn phường') }}" placeholder="Ví dụ: Toàn phường, TDP 1, TDP 2..." required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;">
                    </div>

                    <div style="grid-column: span 2;">
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Nội dung cảnh báo & Hướng dẫn phòng tránh <span style="color: red;">*</span></label>
                        <textarea name="content" rows="5" placeholder="Mô tả tình hình dự báo, diễn biến thời tiết và khuyến cáo phòng chống an toàn cho người dân..." required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;">{{ old('content') }}</textarea>
                    </div>

                    <div style="display: flex; align-items: center; margin-top: 16px; grid-column: span 2;">
                        <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px; cursor: pointer;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width: 18px; height: 18px;">
                            Phát hành ngay lên trang chủ & trang thời tiết của Zalo Mini App
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px; border-top: 1px solid var(--border); padding-top: 16px;">
                    <a href="{{ route('weather-alerts.index') }}" class="admin-btn" style="padding: 10px 20px; border-radius: 6px; background: #e0e0e0; color: #333; text-decoration: none; font-weight: 600;">Hủy bỏ</a>
                    <button type="submit" class="admin-btn admin-btn-primary" style="padding: 10px 24px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;">Phát hành Cảnh báo</button>
                </div>
            </form>
        </div>
    </main>
@endsection
