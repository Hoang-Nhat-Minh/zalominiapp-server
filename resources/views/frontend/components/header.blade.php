<header class="admin-top-nav">
    <div class="admin-top-nav-left" style="display: flex; align-items: center; gap: 12px;">
        <button class="admin-icon-btn admin-sidebar-toggle" id="adminSidebarToggle" title="Thu gọn / Mở rộng Menu">
            <i class="ph ph-list"></i>
        </button>

        <div class="admin-search-bar">
            <div style="position: relative;">
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" placeholder="Tìm kiếm nhanh..." style="padding-left: 36px;">
            </div>
        </div>
    </div>


    <div class="admin-top-actions">
        <div class="admin-dropdown-wrap">
            <button class="admin-icon-btn admin-dropdown-trigger" data-target="notifyDropdown">
                <i class="ph ph-bell"></i>
                <span class="admin-badge admin-badge-danger">3</span>
            </button>

            <div class="admin-dropdown-menu admin-notify-menu" id="notifyDropdown">
                <div class="admin-notify-header">
                    <h4>Thông báo</h4>
                    <span class="admin-notify-count">3 chưa đọc</span>
                </div>
                <div class="admin-notify-list">
                    <a href="#" class="admin-notify-item admin-notify-unread">
                        <div class="admin-notify-icon" style="background: var(--info-bg); color: var(--info);">
                            <i class="ph ph-calendar-plus"></i>
                        </div>
                        <div class="admin-notify-content">
                            <p class="admin-notify-title">Lịch hẹn mới từ công dân</p>
                            <p class="admin-notify-desc">Nguyễn Văn A vừa đặt lịch hẹn làm thủ tục Cấp CCCD mới.</p>
                            <span class="admin-notify-time">5 phút trước</span>
                        </div>
                        <div class="admin-notify-status-dot"></div>
                    </a>

                    <a href="#" class="admin-notify-item admin-notify-unread">
                        <div class="admin-notify-icon" style="background: var(--warning-bg); color: var(--warning);">
                            <i class="ph ph-warning-circle"></i>
                        </div>
                        <div class="admin-notify-content">
                            <p class="admin-notify-title">Phản ánh chưa xử lý</p>
                            <p class="admin-notify-desc">Có 1 phản ánh về trật tự đô thị đã quá hạn tiếp nhận.</p>
                            <span class="admin-notify-time">45 phút trước</span>
                        </div>
                        <div class="admin-notify-status-dot"></div>
                    </a>

                    <a href="#" class="admin-notify-item admin-notify-unread">
                        <div class="admin-notify-icon" style="background: var(--success-bg); color: var(--success);">
                            <i class="ph ph-check-circle"></i>
                        </div>
                        <div class="admin-notify-content">
                            <p class="admin-notify-title">Hồ sơ hoàn thành</p>
                            <p class="admin-notify-desc">Hồ sơ HS-2606-004 đã được ký duyệt thành công.</p>
                            <span class="admin-notify-time">2 giờ trước</span>
                        </div>
                        <div class="admin-notify-status-dot"></div>
                    </a>

                    <a href="#" class="admin-notify-item">
                        <div class="admin-notify-icon" style="background: #E8F0FE; color: var(--primary);">
                            <i class="ph ph-paper-plane-tilt"></i>
                        </div>
                        <div class="admin-notify-content">
                            <p class="admin-notify-title">Hệ thống gửi thông báo</p>
                            <p class="admin-notify-desc">Chiến dịch gửi SMS cảnh báo bão số 3 đã hoàn tất.</p>
                            <span class="admin-notify-time">Hôm qua, 14:30</span>
                        </div>
                    </a>

                    <a href="#" class="admin-notify-item">
                        <div class="admin-notify-icon" style="background: var(--background); color: var(--text-muted);">
                            <i class="ph ph-file-pdf"></i>
                        </div>
                        <div class="admin-notify-content">
                            <p class="admin-notify-title">Xuất báo cáo thành công</p>
                            <p class="admin-notify-desc">Báo cáo tổng kết Quý II đã được xuất và lưu trữ.</p>
                            <span class="admin-notify-time">Hôm qua, 09:15</span>
                        </div>
                    </a>
                </div>
                <div class="admin-notify-footer">
                    <a href="#">Xem tất cả thông báo</a>
                </div>
            </div>
        </div>

        <div class="admin-dropdown-wrap">
            <div class="admin-user-profile admin-dropdown-trigger" data-target="profileMenu">
                <div class="admin-avatar">CB</div>
                <div class="admin-user-info">
                    <span class="admin-user-name">Cán bộ Tiếp dân</span>
                    <span class="admin-user-role">Phòng Hành chính</span>
                </div>
                <i class="ph ph-caret-down admin-profile-caret"></i>
            </div>

            <div class="admin-dropdown-menu admin-profile-menu" id="profileMenu">
                <div class="admin-profile-header">
                    <div class="admin-avatar admin-avatar-lg">CB</div>
                    <div class="admin-profile-header-info">
                        <strong>Nguyễn Văn Tiếp</strong>
                        <span>Cán bộ Tiếp dân</span>
                        <span class="admin-profile-dept">Phòng Hành chính</span>
                    </div>
                </div>
                <ul class="admin-profile-list">
                    <li>
                        <a href="{{ route('officer.profile') }}" class="admin-profile-item">
                            <i class="ph ph-user-circle"></i> Hồ sơ cá nhân
                        </a>
                    </li>
                    <li class="admin-profile-separator"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                            @csrf
                        </form>
                        <a href="{{ route('logout') }}" class="admin-profile-item admin-profile-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ph ph-sign-out"></i> Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>