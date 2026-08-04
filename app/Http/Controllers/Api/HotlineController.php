<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HotlineController extends Controller
{
    public function index(Request $request)
    {
        try {
            $hotlines = [];

            if (Schema::hasTable('hotlines')) {
                $query = Hotline::where('is_active', true);

                if ($request->has('category') && $request->category !== 'all') {
                    $query->where('category', $request->category);
                }

                $hotlines = $query->orderBy('order', 'asc')->get();
            }

            return response()->json([
                'success' => true,
                'message' => 'Danh sách đường dây nóng',
                'data'    => $hotlines,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi kết nối: ' . $e->getMessage(),
            ], 500);
        }
    }
}
