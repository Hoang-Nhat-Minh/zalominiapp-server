<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('officer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            /** @var \App\Models\Officer $officer */
            $officer = Auth::guard('officer')->user();

            if ($officer->status !== 'active') {
                Auth::guard('officer')->logout();
                return back()->withErrors([
                    'email' => 'Tài khoản cán bộ này đã bị vô hiệu hóa.',
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

    public function profile()
    {
        $officer = Auth::guard('officer')->user() ?: Auth::user();
        return view('frontend.officers.profile', compact('officer'));
    }

    public function updateProfile(Request $request)
    {
        $officer = Auth::guard('officer')->user() ?: Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:officers,email,' . $officer->id,
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required'      => 'Vui lòng nhập họ và tên',
            'email.required'     => 'Vui lòng nhập địa chỉ email',
            'email.unique'       => 'Email này đã được sử dụng trên hệ thống',
            'password.min'       => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu mới không khớp',
        ]);

        $updateData = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $officer->update($updateData);

        return redirect()->back()->with('success', 'Cập nhật thông tin hồ sơ cá nhân thành công!');
    }

    public function logout(Request $request): RedirectResponse
    {
        if (Auth::guard('officer')->check()) {
            Auth::guard('officer')->logout();
        }
        if (Auth::check()) {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất khỏi hệ thống thành công!');
    }
}