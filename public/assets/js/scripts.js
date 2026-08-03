document.addEventListener("DOMContentLoaded", () => {
    const dropdownTriggers = document.querySelectorAll('.admin-dropdown-trigger');
    const dropdownMenus = document.querySelectorAll('.admin-dropdown-menu');
    const toggleBtn = document.getElementById('adminSidebarToggle');
    const body = document.body;

    // --- LOGIC DROPDOWN ---
    function closeAllDropdowns() {
        dropdownMenus.forEach(menu => {
            menu.classList.remove('active');
        });
        dropdownTriggers.forEach(trigger => {
            trigger.classList.remove('active');
        });
    }

    dropdownTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const targetId = this.getAttribute('data-target');
            const targetMenu = document.getElementById(targetId);

            // Bổ sung check null để tránh lỗi JS nếu lỡ gõ sai data-target ở HTML
            if (!targetMenu) return;

            const isCurrentlyActive = targetMenu.classList.contains('active');

            // Đóng tất cả trước
            closeAllDropdowns();

            if (!isCurrentlyActive) {
                targetMenu.classList.add('active');
                this.classList.add('active');
            }
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.admin-dropdown-wrap')) {
            closeAllDropdowns();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllDropdowns();
        }
    });

    dropdownMenus.forEach(menu => {
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // --- LOGIC SIDEBAR TOGGLE ---
    const overlay = document.createElement('div');
    overlay.className = 'admin-sidebar-overlay';
    document.body.appendChild(overlay);

    if (window.innerWidth > 768) {
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            body.classList.add('sidebar-collapsed');
        }
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (window.innerWidth <= 768) {
                body.classList.toggle('sidebar-mobile-open');
                console.log('[Sidebar] Mobile toggle →', body.classList.contains('sidebar-mobile-open') ? 'open' : 'closed');
            } else {
                body.classList.toggle('sidebar-collapsed');

                const isCollapsed = body.classList.contains('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', isCollapsed);

                console.log('[Sidebar] Desktop toggle →', isCollapsed ? 'collapsed' : 'expanded');

                setTimeout(() => {
                    window.dispatchEvent(new Event('resize'));
                }, 300);
            }
        });
    }

    overlay.addEventListener('click', function() {
        body.classList.remove('sidebar-mobile-open');
    });
});