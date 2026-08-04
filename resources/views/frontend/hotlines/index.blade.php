@extends('layouts.main')

@section('title', 'Quản lý đường dây nóng - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper hotlines-wrapper">
        <div class="hotlines-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div class="hotlines-header-info">
                <h1 class="hotlines-title" style="font-size: 24px; font-weight: 600; color: var(--text-main); margin-bottom: 4px;">Quản lý đường dây nóng (Hotline)</h1>
                <p class="hotlines-subtitle" style="color: var(--text-muted); font-size: 14px;">Quản lý danh sách số điện thoại khẩn cấp, Công an, Y tế, Cứu hộ và Tổ trưởng TDP phục vụ người dân.</p>
            </div>
            <a href="{{ route('hotlines.create') }}" class="admin-btn admin-btn-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">
                <i class="ph ph-plus-circle" style="font-size: 18px;"></i> Thêm số hotline
            </a>
        </div>

        @if(session('success'))
            <div style="padding: 12px 16px; background-color: #ECFDF5; border: 1px solid #10B981; color: #065F46; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="ph ph-check-circle" style="font-size: 18px;"></i>
                <strong>{{ session('success') }}</strong>
            </div>
        @endif

        <div class="hotlines-stats-grid" style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px; margin-bottom: 24px;">
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #E8F0FE; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-phone-call"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Tổng số Hotline</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->total }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #EBF1FF; color: #0057FF; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-shield-check"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Công an / ANTT</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->police }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #ECFDF5; color: #10B981; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-first-aid-kit"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Y tế / Cấp cứu</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->medical }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #FEF2F2; color: #EF4444; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-fire"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">PCCC / Cứu hộ</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->rescue }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #F5F3FF; color: #8B5CF6; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-users"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Tổ trưởng TDP</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->tdp }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #FFF9C4; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-info"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Khác / Tổng đài</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->other }}</div>
                </div>
            </div>
        </div>

        <div class="hotlines-filter-bar" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <form action="{{ route('hotlines.index') }}" method="GET" style="display: flex; align-items: center; gap: 12px; flex: 1; flex-wrap: wrap;">
                <div style="position: relative; display: flex; align-items: center; min-width: 280px; flex: 1;">
                    <i class="ph ph-magnifying-glass" style="position: absolute; left: 12px; color: var(--text-muted); font-size: 18px;"></i>
                    <input type="text" name="search" placeholder="Tìm theo tên đơn vị, SĐT, địa chỉ, mô tả..." value="{{ request('search') }}" style="width: 100%; padding: 8px 12px 8px 40px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px;">
                </div>
                <select name="category" style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px; background: white; min-width: 160px;">
                    <option value="">Tất cả phân loại</option>
                    <option value="police" {{ request('category') === 'police' ? 'selected' : '' }}>Công an / ANTT</option>
                    <option value="medical" {{ request('category') === 'medical' ? 'selected' : '' }}>Y tế / Cấp cứu</option>
                    <option value="rescue" {{ request('category') === 'rescue' ? 'selected' : '' }}>PCCC / Cứu hộ</option>
                    <option value="tdp" {{ request('category') === 'tdp' ? 'selected' : '' }}>Tổ trưởng TDP</option>
                    <option value="other" {{ request('category') === 'other' ? 'selected' : '' }}>Khác / Tổng đài</option>
                </select>
                <button type="submit" class="admin-btn admin-btn-primary" style="padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px; border: none; cursor: pointer;">
                    <i class="ph ph-funnel"></i> Lọc dữ liệu
                </button>
                @if(request()->anyFilled(['search', 'category']))
                    <a href="{{ route('hotlines.index') }}" class="admin-btn" style="padding: 8px 16px; border-radius: 6px; background: #e0e0e0; color: #333; text-decoration: none; font-weight: 600; font-size: 14px;">
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
                        <th width="25%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Tên đơn vị / Cá nhân</th>
                        <th width="15%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Phân loại</th>
                        <th width="15%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Số điện thoại</th>
                        <th width="20%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Mô tả / Trực ban</th>
                        <th width="10%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Trạng thái</th>
                        <th width="10%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main); text-align: right;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hotlines as $index => $item)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px 16px; font-size: 14px;">{{ $hotlines->firstItem() + $index }}</td>
                            <td style="padding: 12px 16px;">
                                <div style="font-weight: 600; color: var(--text-main); font-size: 14px;">{{ $item->name }}</div>
                                @if($item->address)
                                    <div style="font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 4px; margin-top: 2px;">
                                        <i class="ph ph-map-pin"></i> {{ $item->address }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 12px 16px;">
                                @php
                                    $badgeBg = match($item->category) {
                                        'police' => '#EBF1FF',
                                        'medical' => '#ECFDF5',
                                        'rescue' => '#FEF2F2',
                                        'tdp' => '#F5F3FF',
                                        default => '#FFF9C4'
                                    };
                                    $badgeColor = match($item->category) {
                                        'police' => '#0057FF',
                                        'medical' => '#10B981',
                                        'rescue' => '#EF4444',
                                        'tdp' => '#8B5CF6',
                                        default => '#D97706'
                                    };
                                @endphp
                                <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; background: {{ $badgeBg }}; color: {{ $badgeColor }};">
                                    {{ $item->category_label }}
                                </span>
                            </td>
                            <td style="padding: 12px 16px;">
                                <a href="tel:{{ $item->phone }}" style="font-weight: 700; color: #0057FF; text-decoration: none; font-size: 15px; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="ph ph-phone-call"></i> {{ $item->phone }}
                                </a>
                            </td>
                            <td style="padding: 12px 16px; font-size: 13px; color: var(--text-muted);">
                                {{ $item->description ?? '—' }}
                            </td>
                            <td style="padding: 12px 16px;">
                                @if($item->is_active)
                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #ECFDF5; color: #10B981;">
                                        Đang hiện
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #F3F4F6; color: #6B7280;">
                                        Đã ẩn
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 12px 16px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a href="{{ route('hotlines.edit', $item->id) }}" title="Chỉnh sửa" style="padding: 6px; border-radius: 4px; background: #E8F0FE; color: var(--primary); text-decoration: none;">
                                        <i class="ph ph-pencil-simple" style="font-size: 16px;"></i>
                                    </a>
                                    <form action="{{ route('hotlines.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa số hotline này?');" style="display: inline;">
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
                                <i class="ph ph-phone-slash" style="font-size: 40px; margin-bottom: 8px; display: block;"></i>
                                Không tìm thấy số hotline nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($hotlines->hasPages())
            <div style="margin-top: 20px;">
                {{ $hotlines->links() }}
            </div>
        @endif
    </main>
@endsection
