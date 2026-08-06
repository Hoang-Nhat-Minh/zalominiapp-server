@extends('layouts.main')

@section('title', 'Hồ sơ cá nhân cán bộ')

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

        <div style="margin-bottom: 24px;">
            <h1 style="font-size: 24px; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">
                <i class="ph ph-user-circle"></i> Hồ sơ cá nhân cán bộ
            </h1>
            <p style="color: var(--text-muted); font-size: 14px;">Quản lý thông tin tài khoản và cập nhật mật khẩu đăng nhập hệ thống.</p>
        </div>

        <div style="display: grid; grid-template-columns: 320px 1fr; gap: 24px; align-items: start;">
            <!-- Cột trái: Thông tin tổng quan Cán bộ -->
            <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px; text-align: center;">
                <div style="width: 90px; height: 90px; border-radius: 50%; background: linear-gradient(135deg, #0057FF 0%, #0099FF 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 36px; font-weight: 700; margin: 0 auto 16px; box-shadow: 0 4px 12px rgba(0,87,255,0.25);">
                    {{ strtoupper(substr($officer->name ?? 'C', 0, 1)) }}
                </div>
                
                <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main); margin: 0 0 4px;">
                    {{ $officer->name }}
                </h3>
                <p style="font-size: 13px; color: var(--text-muted); margin: 0 0 12px;">
                    {{ $officer->email }}
                </p>

                <div style="display: inline-flex; align-items: center; gap: 6px; background: #DEF7EC; color: #03543F; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 20px;">
                    <i class="ph ph-check-circle"></i> Tài khoản Đang hoạt động
                </div>

                <div style="border-top: 1px solid var(--border); padding-top: 16px; text-align: left; font-size: 13px; color: var(--text-muted); display: flex; flex-direction: column; gap: 10px;">
                    <div>
                        <strong style="color: var(--text-main);">Số điện thoại:</strong> {{ $officer->phone ?: 'Chưa cập nhật' }}
                    </div>
                    <div>
                        <strong style="color: var(--text-main);">Mã phòng ban:</strong> Phòng/Ban #{{ $officer->department_id }}
                    </div>
                    <div>
                        <strong style="color: var(--text-main);">Đăng nhập gần nhất:</strong><br>
                        <i class="ph ph-clock" style="vertical-align: middle;"></i> {{ $officer->last_login_at ? $officer->last_login_at->format('H:i - d/m/Y') : 'Vừa đăng nhập' }}
                    </div>
                </div>
            </div>

            <!-- Cột phải: Form cập nhật thông tin & Đổi mật khẩu -->
            <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <i class="ph ph-note-pencil" style="color: var(--primary);"></i> Chỉnh sửa thông tin cá nhân
                </h2>

                <form action="{{ route('officer.profile.update') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Họ và tên <span style="color:red;">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $officer->name) }}" required style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Địa chỉ Email <span style="color:red;">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $officer->email) }}" required style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Số điện thoại liên hệ</label>
                        <input type="text" name="phone" value="{{ old('phone', $officer->phone) }}" placeholder="Nhập số điện thoại..." style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                    </div>

                    <h2 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <i class="ph ph-lock-key" style="color: var(--warning);"></i> Đổi mật khẩu đăng nhập (Bỏ trống nếu không đổi)
                    </h2>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Mật khẩu mới</label>
                            <input type="password" name="password" placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)..." style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: var(--text-main);">Xác nhận mật khẩu mới</label>
                            <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu mới..." style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid var(--border); font-size: 14px;">
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="admin-btn admin-btn-primary" style="padding: 12px 28px; font-size: 15px; font-weight: 600;">
                            <i class="ph ph-floppy-disk"></i> Lưu Thay Đổi Hồ Sơ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
