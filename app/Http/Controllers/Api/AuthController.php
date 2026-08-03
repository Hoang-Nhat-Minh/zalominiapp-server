<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function zalo(Request $request)
    {
        //Demo - Remove this on production
        if (!$request->filled('access_token')) {

            $user = User::find(1);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Demo user not found',
                ], 404);
            }

            $token = $user->createToken('demo-login')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Demo login success',
                'data' => [
                    'token' => $token,
                    'user'  => $user,
                ],
            ]);
        }

        


        //Check Auth
        $request->validate([
            'access_token' => 'required|string',
        ]);

        try {
            $userResponse = Http::withHeaders([
                'access_token' => $request->access_token,
            ])->get('https://graph.zalo.me/v2.0/me', [
                'fields' => 'id,name,picture',
            ]);

            Log::info('Zalo user response', [
                'status'   => $userResponse->status(),
                'response' => $userResponse->body(),
            ]);

            if ($userResponse->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể lấy thông tin người dùng Zalo',
                ], 401);
            }

            $zaloUser = $userResponse->json();

            if (isset($zaloUser['error']) && $zaloUser['error'] !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access token Zalo không hợp lệ',
                ], 401);
            }

            $user = User::updateOrCreate(
                ['zalo_id' => $zaloUser['id']],
                [
                    'full_name'     => $zaloUser['name'] ?? null,
                    'avatar'        => $zaloUser['picture']['data']['url'] ?? null,
                    'last_login_at' => now(),
                ]
            );

            $token = $user->createToken('zalo-auth')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'data'    => [
                    'token' => $token,
                    'user'  => $user,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        try {
            $otp = rand(100000, 999999);

            cache()->put('otp_' . $request->phone, $otp, 300);

            // TODO: Tích hợp SMS service gửi OTP thật
            return response()->json([
                'success' => true,
                'message' => 'Đã gửi OTP tới số ' . $request->phone,
                'data'    => ['otp' => $otp], // Bỏ dòng này khi production
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'otp'   => 'required|string|size:6',
        ]);

        try {
            $cachedOtp = cache()->get('otp_' . $request->phone);

            if (!$cachedOtp || $cachedOtp != $request->otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP không hợp lệ hoặc đã hết hạn',
                ], 401);
            }

            $user = User::updateOrCreate(
                ['phone' => $request->phone],
                ['last_login_at' => now()]
            );

            cache()->forget('otp_' . $request->phone);

            $token = $user->createToken('otp-auth')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'data'    => [
                    'token' => $token,
                    'user'  => $user,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Thông tin người dùng',
            'data'    => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = $request->user();
        $user->update($request->only(['phone', 'address']));

        return response()->json([
            'success' => true,
            'data'    => $user,
        ]);
    }
}
