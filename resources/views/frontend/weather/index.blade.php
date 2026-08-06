@extends('layouts.main')

@section('title', 'Quản lý Thời tiết & Cảnh báo - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper weather-wrapper">
        <div class="weather-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div class="weather-header-info">
                <h1 class="weather-title" style="font-size: 24px; font-weight: 600; color: var(--text-main); margin-bottom: 4px;">Quản lý Dự báo Thời tiết & Cảnh báo Cực đoan</h1>
                <p class="weather-subtitle" style="color: var(--text-muted); font-size: 14px;">Phát hành các thông báo, khuyến cáo phòng tránh thiên tai và cảnh báo cực đoan tới người dân.</p>
            </div>
            <a href="{{ route('weather-alerts.create') }}" class="admin-btn admin-btn-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">
                <i class="ph ph-warning-circle" style="font-size: 18px;"></i> Phát hành cảnh báo mới
            </a>
        </div>

        @if(session('success'))
            <div style="padding: 12px 16px; background-color: #ECFDF5; border: 1px solid #10B981; color: #065F46; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="ph ph-check-circle" style="font-size: 18px;"></i>
                <strong>{{ session('success') }}</strong>
            </div>
        @endif

        {{-- Widget Dữ liệu Thời tiết Thực tế --}}
        <div class="gov-card" style="background: linear-gradient(135deg, #0057FF 0%, #0099FF 100%); padding: 20px; border-radius: 12px; color: white; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,87,255,0.25);">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; opacity: 0.9; font-weight: 500; margin-bottom: 6px;">
                        <i class="ph ph-map-pin" style="font-size: 16px;"></i>
                        <span>Thời tiết thời gian thực tại: <strong style="font-size: 15px; font-weight: 700;">{{ $location ?? ($weatherData['location'] ?? 'Hà Nội') }}</strong></span>
                        <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px; font-size: 11px;">
                            Cập nhật: {{ $currentWeather['updated_at'] ?? now()->format('H:i - d/m/Y') }}
                        </span>
                    </div>
                    @if($currentWeather)
                        <div style="display: flex; align-items: center; gap: 16px; margin-top: 6px;">
                            <div style="font-size: 40px; font-weight: 800; line-height: 1;">
                                {{ $currentWeather['temp'] }}°C
                            </div>
                            <div>
                                <div style="font-size: 16px; font-weight: 700;">{{ $currentWeather['condition_text'] }}</div>
                                <div style="font-size: 12px; opacity: 0.85; margin-top: 2px;">Cảm giác như {{ $currentWeather['feels_like'] }}°C</div>
                            </div>
                        </div>
                    @else
                        <div style="display: flex; align-items: center; gap: 8px; margin-top: 6px; background: rgba(239, 68, 68, 0.2); padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(239, 68, 68, 0.4);">
                            <i class="ph ph-warning-circle" style="font-size: 20px; color: #FCA5A5;"></i>
                            <span style="font-size: 13px; color: #FEF2F2; font-weight: 500;">
                                Chưa nhận được dữ liệu thời tiết trực tuyến (Hệ thống đã ghi thông báo vào Log).
                            </span>
                        </div>
                    @endif
                </div>

                @if($currentWeather)
                    <div style="display: flex; gap: 20px; background: rgba(255,255,255,0.15); padding: 12px 18px; border-radius: 10px; backdrop-filter: blur(4px);">
                        <div style="text-align: center;">
                            <div style="font-size: 11px; opacity: 0.85;"><i class="ph ph-drop"></i> Độ ẩm</div>
                            <div style="font-size: 15px; font-weight: 700; margin-top: 2px;">{{ $currentWeather['humidity'] }}%</div>
                        </div>
                        <div style="text-align: center; border-left: 1px solid rgba(255,255,255,0.2); padding-left: 16px;">
                            <div style="font-size: 11px; opacity: 0.85;"><i class="ph ph-wind"></i> Tốc độ gió</div>
                            <div style="font-size: 15px; font-weight: 700; margin-top: 2px;">{{ $currentWeather['wind_speed'] }} km/h</div>
                        </div>
                        <div style="text-align: center; border-left: 1px solid rgba(255,255,255,0.2); padding-left: 16px;">
                            <div style="font-size: 11px; opacity: 0.85;"><i class="ph ph-sun"></i> Chỉ số UV</div>
                            <div style="font-size: 15px; font-weight: 700; margin-top: 2px;">{{ $currentWeather['uv_index'] }}/12</div>
                        </div>
                        <div style="text-align: center; border-left: 1px solid rgba(255,255,255,0.2); padding-left: 16px;">
                            <div style="font-size: 11px; opacity: 0.85;"><i class="ph ph-gauge"></i> Áp suất</div>
                            <div style="font-size: 15px; font-weight: 700; margin-top: 2px;">{{ $currentWeather['pressure'] }} hPa</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="weather-stats-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 24px;">
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #E8F0FE; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-cloud-sun"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Tổng số bản tin</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->total }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #FEF2F2; color: #EF4444; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-warning"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Nguy hiểm khẩn cấp</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->danger }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #FFFBEB; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-bell"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Cảnh báo vừa</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->warning }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #EBF1FF; color: #0057FF; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-info"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Thông tin chung</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->info }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #ECFDF5; color: #10B981; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-broadcast"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Đang hiển thị</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->active }}</div>
                </div>
            </div>
        </div>

        <div class="weather-filter-bar" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <form action="{{ route('weather-alerts.index') }}" method="GET" style="display: flex; align-items: center; gap: 12px; flex: 1; flex-wrap: wrap;">
                <div style="position: relative; display: flex; align-items: center; min-width: 280px; flex: 1;">
                    <i class="ph ph-magnifying-glass" style="position: absolute; left: 12px; color: var(--text-muted); font-size: 18px;"></i>
                    <input type="text" name="search" placeholder="Tìm theo tiêu đề, nội dung, khu vực..." value="{{ request('search') }}" style="width: 100%; padding: 8px 12px 8px 40px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px;">
                </div>
                <select name="level" style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px; background: white; min-width: 170px;">
                    <option value="">Tất cả cấp độ</option>
                    <option value="danger" {{ request('level') === 'danger' ? 'selected' : '' }}>Nguy hiểm khẩn cấp</option>
                    <option value="warning" {{ request('level') === 'warning' ? 'selected' : '' }}>Cảnh báo</option>
                    <option value="info" {{ request('level') === 'info' ? 'selected' : '' }}>Thông tin thời tiết</option>
                </select>
                <button type="submit" class="admin-btn admin-btn-primary" style="padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px; border: none; cursor: pointer;">
                    <i class="ph ph-funnel"></i> Lọc dữ liệu
                </button>
                @if(request()->anyFilled(['search', 'level']))
                    <a href="{{ route('weather-alerts.index') }}" class="admin-btn" style="padding: 8px 16px; border-radius: 6px; background: #e0e0e0; color: #333; text-decoration: none; font-weight: 600; font-size: 14px;">
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
                        <th width="25%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Tiêu đề cảnh báo</th>
                        <th width="15%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Cấp độ</th>
                        <th width="15%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Khu vực ảnh hưởng</th>
                        <th width="20%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Nội dung chi tiết</th>
                        <th width="10%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Trạng thái</th>
                        <th width="10%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main); text-align: right;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alerts as $index => $item)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px 16px; font-size: 14px;">{{ $alerts->firstItem() + $index }}</td>
                            <td style="padding: 12px 16px;">
                                <div style="font-weight: 600; color: var(--text-main); font-size: 14px;">{{ $item->title }}</div>
                                <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                    Tạo lúc: {{ $item->created_at->format('H:i d/m/Y') }}
                                </div>
                            </td>
                            <td style="padding: 12px 16px;">
                                <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; background: {{ $item->level_bg }}; color: {{ $item->level_color }};">
                                    {{ $item->level_label }}
                                </span>
                            </td>
                            <td style="padding: 12px 16px; font-size: 14px; font-weight: 600; color: var(--text-main);">
                                <i class="ph ph-map-pin" style="color: var(--text-muted);"></i> {{ $item->area }}
                            </td>
                            <td style="padding: 12px 16px; font-size: 13px; color: var(--text-muted);">
                                {{ Str::limit($item->content, 80) }}
                            </td>
                            <td style="padding: 12px 16px;">
                                @if($item->is_active)
                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #ECFDF5; color: #10B981;">
                                        Đang phát
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #F3F4F6; color: #6B7280;">
                                        Đã ẩn
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 12px 16px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a href="{{ route('weather-alerts.edit', $item->id) }}" title="Chỉnh sửa" style="padding: 6px; border-radius: 4px; background: #E8F0FE; color: var(--primary); text-decoration: none;">
                                        <i class="ph ph-pencil-simple" style="font-size: 16px;"></i>
                                    </a>
                                    <form action="{{ route('weather-alerts.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bản tin cảnh báo này?');" style="display: inline;">
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
                                <i class="ph ph-cloud-rain" style="font-size: 40px; margin-bottom: 8px; display: block;"></i>
                                Chưa có bản tin cảnh báo thời tiết nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($alerts->hasPages())
            <div style="margin-top: 20px;">
                {{ $alerts->links() }}
            </div>
        @endif
    </main>
@endsection
