<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View|RedirectResponse
    {
        if (Auth::guard('officer')->check()) {
            return redirect()->route('dashboard');
        }

        return view('frontend.auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Vui lòng nhập email.',
            'email.email'       => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('officer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            /** @var \App\Models\Officer $officer */
            $officer = Auth::guard('officer')->user();

            // Chặn tài khoản bị vô hiệu hóa
            if ($officer->status !== 'active') {
                Auth::guard('officer')->logout();

                return back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'email' => 'Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên.',
                    ]);
            }

            // Cập nhật thời gian đăng nhập cuối
            $officer->updateQuietly(['last_login_at' => now()]);

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Email hoặc mật khẩu không đúng.',
            ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('officer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}