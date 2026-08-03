<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        try {
            $profiles = Profile::where('user_id', $request->user()->id)
                ->with('timelines')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Danh sách hồ sơ',
                'data'    => $profiles,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $code)
    {
        try {
            $profile = Profile::where('code', $code)
                ->with(['timelines', 'officer'])
                ->first();

            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy hồ sơ',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Chi tiết hồ sơ',
                'data'    => $profile,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        try {
            $profiles = Profile::where('user_id', $request->user()->id)
                ->where(function ($query) use ($request) {
                    $query->where('code', 'like', '%' . $request->q . '%')
                        ->orWhere('type', 'like', '%' . $request->q . '%')
                        ->orWhere('description', 'like', '%' . $request->q . '%');
                })
                ->with('timelines')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Kết quả tìm kiếm',
                'data'    => $profiles,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }
}
