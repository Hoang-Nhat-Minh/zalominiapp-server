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
        <a href="{{ route('hotlines.index') }}" class="admin-nav-item {{ Route::is('hotlines.*')?'admin-active':'' }}">
            <i class="ph ph-phone-call"></i>
            <span class="admin-nav-text">Đường dây nóng</span>
        </a>
        <a href="{{ route('weather-alerts.index') }}" class="admin-nav-item {{ Route::is('weather-alerts.*')?'admin-active':'' }}">
            <i class="ph ph-cloud-sun"></i>
            <span class="admin-nav-text">Thời tiết & Cảnh báo</span>
        </a>
    </div>

    <div class="admin-nav-group">
        <span class="admin-nav-title">Tin Tức & Truyền Thông</span>
        <a href="{{ route('news.index') }}" class="admin-nav-item {{ Route::is('news.*')?'admin-active':'' }}">
            <i class="ph ph-newspaper"></i>
            <span class="admin-nav-text">Quản lý bài viết tin tức</span>
        </a>
        <a href="{{ route('news-categories.index') }}" class="admin-nav-item {{ Route::is('news-categories.*')?'admin-active':'' }}">
            <i class="ph ph-folders"></i>
            <span class="admin-nav-text">Danh mục tin tức</span>
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
        <span class="admin-nav-title">Biểu Quyết & Khảo Sát</span>
        <a href="{{ route('surveys.index') }}" class="admin-nav-item {{ Route::is('surveys.*')?'admin-active':'' }}">
            <i class="ph ph-clipboard-text"></i>
            <span class="admin-nav-text">Quản lý khảo sát dân cư</span>
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