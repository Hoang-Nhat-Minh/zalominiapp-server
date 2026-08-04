@extends('layouts.main')

@section('title', 'Thêm cơ sở giáo dục - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper school-create-wrapper" style="padding: 28px; max-width: 1000px; margin: 0 auto;">
        <!-- Header & Breadcrumb Navigation -->
        <div
            style="margin-bottom: 28px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <a href="{{ route('schools.index') }}" class="admin-btn admin-btn-secondary"
                    style="padding: 9px 14px; border-radius: 8px;">
                    <i class="ph ph-arrow-left" style="font-size: 18px;"></i> Quay lại
                </a>
                <div>
                    <h1
                        style="font-size: 22px; font-weight: 700; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 8px;">
                        <i class="ph ph-plus-circle" style="color: var(--primary); font-size: 26px;"></i>
                        Thêm cơ sở giáo dục mới
                    </h1>
                    <p style="color: var(--text-muted); font-size: 13.5px; margin: 3px 0 0 0;">Cập nhật thông tin địa điểm
                        trường học hiển thị trên bản đồ tra cứu của người dân.</p>
                </div>
            </div>
        </div>

        <!-- Validation Alert Box -->
        @if ($errors->any())
            <div
                style="padding: 14px 18px; background-color: var(--danger-bg, #FFEBEE); border: 1px solid var(--danger-border, #FFCDD2); color: var(--danger, #D32F2F); border-radius: 10px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 12px; box-shadow: 0 2px 8px rgba(211, 47, 47, 0.08);">
                <i class="ph ph-warning-circle" style="font-size: 22px; flex-shrink: 0; margin-top: 1px;"></i>
                <div>
                    <strong style="font-size: 14px; display: block; margin-bottom: 4px;">Vui lòng kiểm tra lại thông tin
                        nhập vào:</strong>
                    <ul style="margin: 0; padding-left: 18px; font-size: 13.5px; line-height: 1.5;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Main Form Card -->
        <div class="gov-card">
            <div class="gov-card-header">
                <h2 class="gov-card-title">
                    <i class="ph ph-bank"></i>
                    Thông tin cơ sở giáo dục
                </h2>
                <span style="font-size: 12.5px; color: var(--text-muted);"><span class="required"
                        style="color: #EF4444;">*</span> Bắt buộc nhập</span>
            </div>

            <form action="{{ route('schools.store') }}" method="POST">
                @csrf
                <div class="gov-card-body">

                    <!-- SECTION 1: PHẦN THÔNG TIN CƠ BẢN -->
                    <div class="gov-form-section">
                        <div class="gov-form-section-title">
                            <i class="ph ph-identification-card"></i> 1. Thông tin chung
                        </div>
                        <div class="gov-form-section-desc">Tên trường và cấp học quản lý chính thức</div>

                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                            <div>
                                <label class="gov-label">Tên cơ sở giáo dục <span class="required">*</span></label>
                                <div class="gov-input-group">
                                    <i class="ph ph-buildings gov-input-icon"></i>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        placeholder="Ví dụ: Trường THCS Lý Thường Kiệt..." class="e-input" required>
                                </div>
                            </div>
                            <div>
                                <label class="gov-label">Cấp học <span class="required">*</span></label>
                                <div class="gov-input-group">
                                    <i class="ph ph-student gov-input-icon"></i>
                                    <select name="level" class="e-input" required>
                                        <option value="">-- Chọn cấp học --</option>
                                        <option value="kindergarten"
                                            {{ old('level') === 'kindergarten' ? 'selected' : '' }}>Mầm non</option>
                                        <option value="primary" {{ old('level') === 'primary' ? 'selected' : '' }}>Tiểu học
                                        </option>
                                        <option value="secondary" {{ old('level') === 'secondary' ? 'selected' : '' }}>Trung
                                            học cơ sở (THCS)</option>
                                        <option value="high_school" {{ old('level') === 'high_school' ? 'selected' : '' }}>
                                            Trung học phổ thông (THPT)</option>
                                        <option value="other" {{ old('level') === 'other' ? 'selected' : '' }}>Khác / Liên
                                            cấp</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: THÔNG TIN LIÊN HỆ & ĐỊA CHỈ -->
                    <div class="gov-form-section">
                        <div class="gov-form-section-title">
                            <i class="ph ph-address-book"></i> 2. Liên hệ & Địa chỉ trụ sở
                        </div>
                        <div class="gov-form-section-desc">Địa chỉ chính xác và các kênh liên lạc chính thức với nhà trường
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label class="gov-label">Địa chỉ chính xác <span class="required">*</span></label>
                            <div class="gov-input-group">
                                <i class="ph ph-map-pin gov-input-icon"></i>
                                <input type="text" name="address" value="{{ old('address') }}"
                                    placeholder="Số nhà, tên đường, tổ dân phố, phường/xã..." class="e-input" required>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                            <div>
                                <label class="gov-label">Số điện thoại liên hệ <span class="required">*</span></label>
                                <div class="gov-input-group">
                                    <i class="ph ph-phone gov-input-icon"></i>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                        placeholder="Ví dụ: 024.3825.xxxx" class="e-input" required>
                                </div>
                            </div>
                            <div>
                                <label class="gov-label">Địa chỉ Email</label>
                                <div class="gov-input-group">
                                    <i class="ph ph-envelope-simple gov-input-icon"></i>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        placeholder="truong@edu.vn..." class="e-input">
                                </div>
                            </div>
                            <div>
                                <label class="gov-label">Trang Web (URL)</label>
                                <div class="gov-input-group">
                                    <i class="ph ph-globe gov-input-icon"></i>
                                    <input type="url" name="website" value="{{ old('website') }}"
                                        placeholder="https://truong.edu.vn" class="e-input">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: TỌA ĐỘ BẢN ĐỒ GPS -->
                    <div class="gov-form-section">
                        <div class="gov-form-section-title">
                            <i class="ph ph-navigation-arrow"></i> 3. Tọa độ bản đồ GPS
                        </div>
                        <div class="gov-form-section-desc">Tọa độ để người dân chỉ đường chính xác trên Zalo Mini App bản đồ
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <label class="gov-label">Vĩ độ GPS (Latitude)</label>
                                <div class="gov-input-group">
                                    <i class="ph ph-compass gov-input-icon"></i>
                                    <input type="number" step="any" name="latitude" value="{{ old('latitude') }}"
                                        placeholder="Ví dụ: 20.9723" class="e-input">
                                </div>
                                <div class="gov-help-text">
                                    <i class="ph ph-info"></i> Nhập giá trị vĩ độ (ví dụ 20.9723)
                                </div>
                            </div>
                            <div>
                                <label class="gov-label">Kinh độ GPS (Longitude)</label>
                                <div class="gov-input-group">
                                    <i class="ph ph-compass gov-input-icon"></i>
                                    <input type="number" step="any" name="longitude"
                                        value="{{ old('longitude') }}" placeholder="Ví dụ: 105.7742" class="e-input">
                                </div>
                                <div class="gov-help-text">
                                    <i class="ph ph-info"></i> Nhập giá trị kinh độ (ví dụ 105.7742)
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: GIỚI THIỆU VỀ TRƯỜNG -->
                    <div class="gov-form-section">
                        <div class="gov-form-section-title">
                            <i class="ph ph-article"></i> 4. Giới thiệu & Thông tin bổ sung
                        </div>
                        <div class="gov-form-section-desc">Mô tả ngắn gọn về truyền thống, quy mô hoặc lưu ý cho người dân
                        </div>

                        <div>
                            <label class="gov-label">Giới thiệu ngắn về nhà trường</label>
                            <textarea name="description" rows="4"
                                placeholder="Nhập tóm tắt lịch sử thành lập, cơ sở vật chất, tiêu chuẩn đạt chuẩn quốc gia..." class="e-input">{{ old('description') }}</textarea>
                        </div>
                    </div>

                </div>

                <!-- Form Card Footer with Actions -->
                <div class="gov-card-footer">
                    <a href="{{ route('schools.index') }}" class="admin-btn admin-btn-secondary">
                        <i class="ph ph-x"></i> Hủy bỏ
                    </a>
                    <button type="submit" class="admin-btn admin-btn-primary">
                        <i class="ph ph-floppy-disk"></i> Lưu thông tin trường
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
