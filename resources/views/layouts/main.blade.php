<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="@yield('meta_description', 'Hệ thống Quản trị Nội bộ dành cho cơ quan nhà nước và bộ máy hành chính.')">
    <meta name="keywords" content="@yield('meta_keywords', 'quản trị nội bộ, hệ thống hành chính, quản lý văn bản, điều hành')">
    <meta name="author" content="Kennatech">

    <meta name="robots" content="@yield('meta_robots', 'noindex, nofollow')">

    <title>@yield('title', 'Dịch Vụ Số')</title>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    @stack('styles')
</head>
<body class="gov-app">

<div class="admin-layout">
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-sidebar-brand">
            <i class="ph ph-buildings"></i>
            <span>Dịch Vụ Số</span>
        </div>
        @include('frontend.components.sidebar')
    </aside>
    <div class="admin-main-content">
        @include('frontend.components.header')
        @yield('content')
    </div>
</div>
@stack('scripts')

<script src="{{ asset("assets/js/scripts.js") }}"></script>
<script>
    // 1. Dùng cho các thông báo nhỏ góc màn hình (Toast)
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // 2. Dùng cho các Popup xác nhận lớn ở giữa màn hình (GovAlert)
    const GovAlert = Swal.mixin({
        customClass: {
            confirmButton: 'admin-btn admin-btn-primary',
            cancelButton: 'admin-btn'
        },
        buttonsStyling: false // Tắt style mặc định để dùng class CSS của hệ thống
    });
</script>
</body>
</html>