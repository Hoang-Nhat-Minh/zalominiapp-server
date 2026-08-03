<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Hệ thống Quản trị Nội bộ dành cho cơ quan nhà nước và bộ máy hành chính.">
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="Kennatech">
    <title>Đăng nhập – Dịch Vụ Số</title>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body class="gov-app">
<div class="login-layout">
    <div class="login-wrapper">
        <div class="login-card">

            <div class="login-header">
                <div class="login-brand-icon">
                    <i class="ph ph-buildings"></i>
                </div>
                <h1 class="login-title">Dịch Vụ Số</h1>
                <p class="login-subtitle">Hệ thống Quản lý Hành chính Nội bộ</p>
            </div>

            @if ($errors->any())
                <div class="login-alert login-alert-error">
                    <i class="ph ph-warning-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('status'))
                <div class="login-alert login-alert-info">
                    <i class="ph ph-info"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form id="loginForm"
                  class="login-form"
                  action="{{ route('login.authenticate') }}"
                  method="POST"
                  novalidate>
                @csrf

                <div class="login-form-group">
                    <label for="email" class="login-label">Email cán bộ</label>
                    <div class="login-input-with-icon @error('email') is-invalid @enderror">
                        <i class="ph ph-envelope"></i>
                        <input type="email"
                               id="email"
                               name="email"
                               class="login-input"
                               placeholder="Nhập email cán bộ..."
                               value="{{ old('email') }}"
                               autocomplete="email"
                               required>
                    </div>
                </div>

                <div class="login-form-group">
                    <label for="password" class="login-label">Mật khẩu</label>
                    <div class="login-input-with-icon">
                        <i class="ph ph-lock-key"></i>
                        <input type="password"
                               id="password"
                               name="password"
                               class="login-input"
                               placeholder="Nhập mật khẩu..."
                               autocomplete="current-password"
                               required>
                        <button type="button"
                                class="login-toggle-password"
                                id="loginTogglePassword"
                                aria-label="Hiện mật khẩu">
                            <i class="ph ph-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="login-form-actions">
                    <label class="login-checkbox-container">
                        <input type="checkbox"
                               name="remember"
                               class="login-checkbox"
                                {{ old('remember') ? 'checked' : '' }}>
                        <span class="login-checkmark"></span>
                        Ghi nhớ đăng nhập
                    </label>
                    <a href="#" class="login-forgot-password">Quên mật khẩu?</a>
                </div>

                <button type="submit"
                        class="login-btn login-btn-primary login-btn-block"
                        id="loginSubmitBtn">
                    <i class="ph ph-sign-in"></i>
                    <span>Đăng nhập hệ thống</span>
                </button>
            </form>

            <div class="login-system-warning">
                <i class="ph ph-shield-warning"></i>
                <p>Hệ thống chỉ dành cho cán bộ và nhân viên được cấp phép. Mọi hành vi truy cập trái phép sẽ bị ghi log và xử lý nghiêm theo quy định về an toàn thông tin.</p>
            </div>

        </div>

        <div class="login-footer">
            &copy; 2026 Cục Quản lý Công nghệ Thông tin. Hỗ trợ kỹ thuật: 1900-xxxx
        </div>
    </div>
</div>

<script>
    document.getElementById('loginTogglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon  = document.getElementById('togglePasswordIcon');
        const show  = input.type === 'password';

        input.type     = show ? 'text' : 'password';
        icon.className = show ? 'ph ph-eye-slash' : 'ph ph-eye';
        this.setAttribute('aria-label', show ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
    });

    document.getElementById('loginForm').addEventListener('submit', function () {
        const btn = document.getElementById('loginSubmitBtn');
        btn.disabled    = true;
        btn.innerHTML   = '<i class="ph ph-circle-notch ph-spin"></i> <span>Đang xử lý...</span>';
    });
</script>
</body>
</html>