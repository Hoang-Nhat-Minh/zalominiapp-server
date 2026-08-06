@extends('layouts.main')

@section('title', 'Cấu hình hệ thống')

@section('content')
    <main class="admin-content-wrapper">
        @if(session('success'))
            <div style="background: #DEF7EC; color: #03543F; padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; justify-content: space-between;">
                <span><i class="ph ph-check-circle" style="font-size: 20px; vertical-align: middle; margin-right: 8px;"></i> {{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div style="background: #FDE8E8; color: #9B1C1C; padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-weight: 500;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h1 style="font-size: 24px; font-weight: 600; color: var(--text-main); margin-bottom: 4px;">
                    <i class="ph ph-gear"></i> Cấu hình hệ thống
                </h1>
                <p style="color: var(--text-muted); font-size: 14px;">Quản lý các thông số hệ thống, Biểu tượng Favicon và Tọa độ phục vụ dự báo thời tiết.</p>
            </div>
        </div>

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <!-- Khối 1: Favicon & Nhãn thương hiệu -->
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 24px;">
                    <h2 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <i class="ph ph-browsers" style="color: var(--primary);"></i> Biểu tượng Favicon & Thương hiệu
                    </h2>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">
                            Tên hệ thống
                        </label>
                        <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">
                            Favicon (Biểu tượng trang web)
                        </label>
                        
                        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                            <div style="width: 48px; height: 48px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; background: #F8FAFC; overflow: hidden;">
                                @if($settings['favicon'])
                                    <img src="{{ asset('storage/' . $settings['favicon']) }}" alt="Favicon" style="width: 32px; height: 32px; object-fit: contain;">
                                @else
                                    <i class="ph ph-globe" style="font-size: 24px; color: var(--text-muted);"></i>
                                @endif
                            </div>
                            <div style="font-size: 13px; color: var(--text-muted);">
                                {{ $settings['favicon'] ? 'Đã tải lên Favicon tùy chỉnh' : 'Đang sử dụng biểu tượng mặc định' }}
                            </div>
                        </div>

                        <input type="file" name="favicon" accept=".ico,.png,.jpg,.jpeg,.svg" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px; background: white;">
                        <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 6px;">Hỗ trợ định dạng: .ico, .png, .jpg, .svg (Dung lượng tối đa 2MB).</span>
                    </div>
                </div>

                <!-- Khối 2: Tọa độ & Thời tiết -->
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 24px;">
                    <h2 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <i class="ph ph-cloud-sun" style="color: #F59E0B;"></i> Tọa độ Dự báo Thời tiết
                    </h2>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">
                            Tên khu vực / Thành phố hiển thị
                        </label>
                        <input type="text" name="weather_city" value="{{ old('weather_city', $settings['weather_city']) }}" placeholder="VD: Hà Nội, Thái Nguyên..." style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">
                                Vĩ độ (Latitude) <span style="color:red;">*</span>
                            </label>
                            <input type="text" id="weather_lat" name="weather_lat" value="{{ old('weather_lat', $settings['weather_lat']) }}" required placeholder="21.0285" style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">
                                Kinh độ (Longitude) <span style="color:red;">*</span>
                            </label>
                            <input type="text" id="weather_lng" name="weather_lng" value="{{ old('weather_lng', $settings['weather_lng']) }}" required placeholder="105.8542" style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                        </div>
                    </div>

                    <!-- Gợi ý nhanh tọa độ một số khu vực -->
                    <div style="margin-bottom: 20px;">
                        <span style="font-size: 12px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 8px;">Chọn nhanh tọa độ tỉnh/thành phố:</span>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <button type="button" onclick="setCoords(21.0285, 105.8542, 'Hà Nội')" class="admin-btn" style="padding: 4px 10px; font-size: 12px; border-radius: 12px; background: #EBF1FF; color: #0057FF; border: none; font-weight: 600;">Hà Nội</button>
                            <button type="button" onclick="setCoords(21.5928, 105.8442, 'Thái Nguyên')" class="admin-btn" style="padding: 4px 10px; font-size: 12px; border-radius: 12px; background: #EBF1FF; color: #0057FF; border: none; font-weight: 600;">Thái Nguyên</button>
                            <button type="button" onclick="setCoords(16.0544, 108.2022, 'Đà Nẵng')" class="admin-btn" style="padding: 4px 10px; font-size: 12px; border-radius: 12px; background: #EBF1FF; color: #0057FF; border: none; font-weight: 600;">Đà Nẵng</button>
                            <button type="button" onclick="setCoords(10.8231, 106.6297, 'TP. Hồ Chí Minh')" class="admin-btn" style="padding: 4px 10px; font-size: 12px; border-radius: 12px; background: #EBF1FF; color: #0057FF; border: none; font-weight: 600;">TP. Hồ Chí Minh</button>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                <button type="submit" class="admin-btn admin-btn-primary" style="padding: 12px 28px; font-size: 15px; font-weight: 600;">
                    <i class="ph ph-floppy-disk"></i> Lưu Cấu Hình Hệ Thống
                </button>
            </div>
        </form>
    </main>
@endsection

@push('scripts')
    <script>
        function setCoords(lat, lng, city) {
            document.getElementById('weather_lat').value = lat;
            document.getElementById('weather_lng').value = lng;
            document.getElementsByName('weather_city')[0].value = city;
        }
    </script>
@endpush
