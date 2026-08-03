@extends('layouts.main')

@section('title', 'Chỉnh sửa cơ sở giáo dục - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper school-edit-wrapper" style="padding: 24px;">
        <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <a href="{{ route('schools.index') }}" class="admin-btn" style="padding: 8px 12px; border-radius: 6px; background: #e0e0e0; color: #333; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 4px;">
                <i class="ph ph-arrow-left"></i> Quay lại
            </a>
            <div>
                <h1 style="font-size: 24px; font-weight: 600; color: var(--text-main); margin: 0;">Chỉnh sửa thông tin cơ sở giáo dục</h1>
                <p style="color: var(--text-muted); font-size: 14px; margin: 2px 0 0 0;">Cập nhật thông tin chi tiết cho trường học: <strong>{{ $school->name }}</strong></p>
            </div>
        </div>

        @if ($errors->any())
            <div style="padding: 12px 16px; background-color: var(--danger-bg); border: 1px solid var(--danger-border); color: var(--danger); border-radius: 6px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="gov-card" style="background: var(--surface); padding: 24px; border-radius: 8px; border: 1px solid var(--border); max-width: 800px;">
            <form action="{{ route('schools.update', $school->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Tên cơ sở giáo dục *</label>
                        <input type="text" name="name" value="{{ old('name', $school->name) }}" placeholder="Nhập tên trường học..." class="e-input" style="width: 100%;" required>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Cấp học *</label>
                        <select name="level" class="e-input" style="width: 100%; height: 48px;" required>
                            <option value="">-- Chọn cấp học --</option>
                            <option value="kindergarten" {{ old('level', $school->level) === 'kindergarten' ? 'selected' : '' }}>Mầm non</option>
                            <option value="primary" {{ old('level', $school->level) === 'primary' ? 'selected' : '' }}>Tiểu học</option>
                            <option value="secondary" {{ old('level', $school->level) === 'secondary' ? 'selected' : '' }}>Trung học cơ sở (THCS)</option>
                            <option value="high_school" {{ old('level', $school->level) === 'high_school' ? 'selected' : '' }}>Trung học phổ thông (THPT)</option>
                            <option value="other" {{ old('level', $school->level) === 'other' ? 'selected' : '' }}>Khác/Liên cấp</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Địa chỉ chính xác *</label>
                    <input type="text" name="address" value="{{ old('address', $school->address) }}" placeholder="Số nhà, tên đường, tổ dân phố..." class="e-input" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Số điện thoại liên hệ *</label>
                        <input type="text" name="phone" value="{{ old('phone', $school->phone) }}" placeholder="Nhập số điện thoại..." class="e-input" required>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Địa chỉ Email</label>
                        <input type="email" name="email" value="{{ old('email', $school->email) }}" placeholder="Nhập email liên hệ..." class="e-input">
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Địa chỉ Website (URL)</label>
                    <input type="url" name="website" value="{{ old('website', $school->website) }}" placeholder="http://example.edu.vn..." class="e-input">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Vĩ độ GPS (Latitude)</label>
                        <input type="number" step="any" name="latitude" value="{{ old('latitude', $school->latitude) }}" placeholder="Ví dụ: 20.9723" class="e-input">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Kinh độ GPS (Longitude)</label>
                        <input type="number" step="any" name="longitude" value="{{ old('longitude', $school->longitude) }}" placeholder="Ví dụ: 105.7742" class="e-input">
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Giới thiệu về trường</label>
                    <textarea name="description" rows="4" placeholder="Nhập nội dung giới thiệu ngắn..." class="e-input" style="resize: none; height: auto;">{{ old('description', $school->description) }}</textarea>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="{{ route('schools.index') }}" class="admin-btn" style="padding: 10px 20px; border-radius: 6px; background: #e0e0e0; color: #333; text-decoration: none; font-weight: 600; font-size: 14px;">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="admin-btn admin-btn-primary" style="padding: 10px 20px; border-radius: 6px; font-weight: 600; font-size: 14px; border: none; cursor: pointer;">
                        Cập nhật thông tin
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
