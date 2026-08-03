<nav class="admin-sidebar-nav">
    <div class="admin-nav-group">
        <span class="admin-nav-title">Bảng Điều Khiển</span>
        <a href="{{ route('dashboard') }}" class="admin-nav-item {{ Route::is('dashboard')?'admin-active':'' }}">
            <i class="ph ph-squares-four"></i>
            <span class="admin-nav-text">Tổng quan</span>
        </a>
    </div>

    <div class="admin-nav-group">
        <span class="admin-nav-title">Nghiệp Vụ Công Dân</span>
        <a href="{{ route('appointments') }}" class="admin-nav-item {{ Route::is('appointments')?'admin-active':'' }}">
            <i class="ph ph-calendar-check"></i>
            <span class="admin-nav-text">Quản lý lịch hẹn</span>
        </a>
        <a href="{{ route('profiles') }}" class="admin-nav-item {{ Route::is('profiles')?'admin-active':'' }}">
            <i class="ph ph-identification-card"></i>
            <span class="admin-nav-text">Hồ sơ công dân</span>
        </a>
        <a href="{{ route('notifications') }}" class="admin-nav-item {{ Route::is('notifications')?'admin-active':'' }}">
            <i class="ph ph-megaphone"></i>
            <span class="admin-nav-text">Thông báo công dân</span>
        </a>
        <a href="{{ route('citizens') }}" class="admin-nav-item {{ Route::is('citizens')?'admin-active':'' }}">
            <i class="ph ph-user-circle-gear"></i>
            <span class="admin-nav-text">Tài khoản công dân</span>
        </a>
        <a href="{{ route('documents') }}" class="admin-nav-item {{ Route::is('documents')?'admin-active':'' }}">
            <i class="ph ph-briefcase"></i>
            <span class="admin-nav-text">Tài liệu dịch vụ công</span>
        </a>
        <a href="{{ route('schools.index') }}" class="admin-nav-item {{ Route::is('schools.*')?'admin-active':'' }}">
            <i class="ph ph-graduation-cap"></i>
            <span class="admin-nav-text">Cơ sở giáo dục</span>
        </a>
    </div>

    <div class="admin-nav-group">
        <span class="admin-nav-title">Phản Ánh</span>
        <a href="{{ route('reports') }}" class="admin-nav-item {{ Route::is('reports')?'admin-active':'' }}">
            <i class="ph ph-chat-centered-text"></i>
            <span class="admin-nav-text">Tiếp nhận phản ánh</span>
        </a>
        <a href="{{ route('digitalmaps') }}" class="admin-nav-item {{ Route::is('digitalmaps')?'admin-active':'' }}">
            <i class="ph ph-map-pin-area"></i>
            <span class="admin-nav-text">Bản đồ số</span>
        </a>
    </div>

    <div class="admin-nav-group">
        <span class="admin-nav-title">Biểu Quyết</span>
        <a href="#" class="admin-nav-item">
            <i class="ph ph-check-square-offset"></i>
            <span class="admin-nav-text">Quản lý biểu quyết</span>
        </a>
        <a href="#" class="admin-nav-item">
            <i class="ph ph-chart-bar"></i>
            <span class="admin-nav-text">Kết quả biểu quyết</span>
        </a>
    </div>

    @if(auth()->check() && auth()->user()->role === 'admin')
        <div class="admin-nav-group">
            <span class="admin-nav-title">Quản Trị Hệ Thống</span>
            <a href="#" class="admin-nav-item">
                <i class="ph ph-users-three"></i>
                <span class="admin-nav-text">Người dùng hệ thống</span>
            </a>
            <a href="#" class="admin-nav-item">
                <i class="ph ph-bell-ringing"></i>
                <span class="admin-nav-text">Thông báo hệ thống</span>
            </a>
        </div>
    @endif
</nav>