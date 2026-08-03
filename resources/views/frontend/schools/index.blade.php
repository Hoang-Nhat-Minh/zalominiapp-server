@extends('layouts.main')

@section('title', 'Quản lý cơ sở giáo dục - Hệ thống Quản Trị')

@section('content')
    <main class="admin-content-wrapper schools-wrapper">
        <div class="schools-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div class="schools-header-info">
                <h1 class="schools-title" style="font-size: 24px; font-weight: 600; color: var(--text-main); margin-bottom: 4px;">Quản lý cơ sở giáo dục</h1>
                <p class="schools-subtitle" style="color: var(--text-muted); font-size: 14px;">Quản lý danh sách trường học, cấp học và thông tin liên hệ trên địa bàn phường.</p>
            </div>
            <a href="{{ route('schools.create') }}" class="admin-btn admin-btn-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">
                <i class="ph ph-plus-circle" style="font-size: 18px;"></i> Thêm cơ sở
            </a>
        </div>

        @if(session('success'))
            <div style="padding: 12px 16px; background-color: var(--success-bg); border: 1px solid var(--success-border); color: var(--success); border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="ph ph-check-circle" style="font-size: 18px;"></i>
                <strong>{{ session('success') }}</strong>
            </div>
        @endif

        <div class="schools-stats-grid" style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px; margin-bottom: 24px;">
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #E8F0FE; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-buildings"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Tổng số trường</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->total }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #FFEBEE; color: #EF4444; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-baby"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Mầm non</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->kindergarten }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #E8F5E9; color: #10B981; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-book-open"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Tiểu học</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->primary }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #E8F0FE; color: #3B82F6; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-notebook"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">THCS</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->secondary }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #FFF3E0; color: #F97316; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-chalkboard-teacher"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">THPT</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->high_school }}</div>
                </div>
            </div>
            <div class="gov-card" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #FFF9C4; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="ph ph-asterisk"></i></div>
                <div>
                    <div style="font-size: 12px; color: var(--text-muted);">Khác</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $stats->other }}</div>
                </div>
            </div>
        </div>

        <div class="schools-filter-bar" style="background: var(--surface); padding: 16px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <form action="{{ route('schools.index') }}" method="GET" style="display: flex; align-items: center; gap: 12px; flex: 1; flex-wrap: wrap;">
                <div style="position: relative; display: flex; align-items: center; min-width: 280px; flex: 1;">
                    <i class="ph ph-magnifying-glass" style="position: absolute; left: 12px; color: var(--text-muted); font-size: 18px;"></i>
                    <input type="text" name="search" placeholder="Tìm theo tên trường, địa chỉ, SĐT..." value="{{ request('search') }}" style="width: 100%; padding: 8px 12px 8px 40px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px;">
                </div>
                <select name="level" style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px; background: white; min-width: 150px;">
                    <option value="">Tất cả cấp học</option>
                    <option value="kindergarten" {{ request('level') === 'kindergarten' ? 'selected' : '' }}>Mầm non</option>
                    <option value="primary" {{ request('level') === 'primary' ? 'selected' : '' }}>Tiểu học</option>
                    <option value="secondary" {{ request('level') === 'secondary' ? 'selected' : '' }}>THCS</option>
                    <option value="high_school" {{ request('level') === 'high_school' ? 'selected' : '' }}>THPT</option>
                    <option value="other" {{ request('level') === 'other' ? 'selected' : '' }}>Khác/Liên cấp</option>
                </select>
                <button type="submit" class="admin-btn admin-btn-primary" style="padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px; border: none; cursor: pointer;">
                    <i class="ph ph-funnel"></i> Lọc dữ liệu
                </button>
                @if(request()->anyFilled(['search', 'level']))
                    <a href="{{ route('schools.index') }}" class="admin-btn" style="padding: 8px 16px; border-radius: 6px; background: #e0e0e0; color: #333; text-decoration: none; font-weight: 600; font-size: 14px;">
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
                        <th width="25%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Tên cơ sở giáo dục</th>
                        <th width="15%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Cấp học</th>
                        <th width="25%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Địa chỉ</th>
                        <th width="15%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main);">Số điện thoại</th>
                        <th width="15%" style="padding: 12px 16px; font-weight: 600; color: var(--text-main); text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schools as $index => $school)
                        <tr style="border-bottom: 1px solid var(--border); transition: background 0.15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 14px 16px; color: var(--text-muted);">{{ ($schools->currentPage() - 1) * $schools->perPage() + $index + 1 }}</td>
                            <td style="padding: 14px 16px;">
                                <strong style="color: var(--text-main); font-size: 14px;">{{ $school->name }}</strong>
                                @if($school->website)
                                    <div style="font-size: 12px; color: var(--primary); margin-top: 2px;">
                                        <i class="ph ph-globe" style="vertical-align: middle;"></i> <a href="{{ $school->website }}" target="_blank" style="color: inherit; text-decoration: none;">{{ $school->website }}</a>
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 14px 16px;">
                                <span class="badge {{ $school->level_config['class'] }}" style="font-size: 11px; letter-spacing: 0.5px; padding: 4px 10px; font-weight: 600; border-radius: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <span style="width: 6px; height: 6px; borderRadius: 50%; background: {{ $school->level_config['dot'] }}; display: inline-block;"></span>
                                    {{ $school->level_config['label'] }}
                                </span>
                            </td>
                            <td style="padding: 14px 16px; color: var(--text-muted); font-size: 13px;">{{ $school->address }}</td>
                            <td style="padding: 14px 16px; color: var(--text-main); font-weight: 500;">{{ $school->phone }}</td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="{{ route('schools.edit', $school->id) }}" class="admin-btn" style="padding: 6px; border-radius: 4px; background: #FFF3E0; color: #ED6C02; border: 1px solid #FFE0B2; text-decoration: none; display: inline-flex; align-items: center;" title="Sửa">
                                        <i class="ph ph-pencil-simple" style="font-size: 16px;"></i>
                                    </a>
                                    <form action="{{ route('schools.destroy', $school->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa cơ sở giáo dục này?')" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-btn" style="padding: 6px; border-radius: 4px; background: #FFEBEE; color: #D32F2F; border: 1px solid #FFCDD2; cursor: pointer; display: inline-flex; align-items: center;" title="Xóa">
                                            <i class="ph ph-trash" style="font-size: 16px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 40px 16px; text-align: center; color: var(--text-muted);">
                                <div style="font-size: 40px; margin-bottom: 12px; color: #ccc;"><i class="ph ph-buildings"></i></div>
                                Không tìm thấy cơ sở giáo dục nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($schools->hasPages())
                <div class="pagination-wrapper" style="padding: 16px; border-top: 1px solid var(--border);">
                    {{ $schools->links('frontend.components.pagination') }}
                </div>
            @endif
        </div>
    </main>
@endsection
