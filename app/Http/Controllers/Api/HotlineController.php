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
            if (Schema::hasTable('hotlines')) {
                $query = Hotline::where('is_active', true);

                if ($request->has('category') && $request->category !== 'all') {
                    $query->where('category', $request->category);
                }

                $hotlines = $query->orderBy('order', 'asc')->get();

                if ($hotlines->isNotEmpty()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Danh sách đường dây nóng',
                        'data'    => $hotlines,
                    ]);
                }
            }

            // Fallback mock data if table not migrated or empty
            $fallback = [
                [
                    'id' => 1,
                    'name' => 'Công an Phường',
                    'category' => 'police',
                    'phone' => '0243.825.1234',
                    'address' => 'Số 10 Phố Chính, Phường',
                    'description' => 'Trực ban 24/7 tiếp nhận báo án, giữ gìn ANTT',
                    'is_active' => true,
                    'order' => 1,
                ],
                [
                    'id' => 2,
                    'name' => 'Cảnh sát PCCC & Cứu nạn 114',
                    'category' => 'rescue',
                    'phone' => '114',
                    'address' => 'Tổng đài Cứu hộ Quốc gia',
                    'description' => 'Báo cháy khẩn cấp & ứng cứu sự cố',
                    'is_active' => true,
                    'order' => 2,
                ],
                [
                    'id' => 3,
                    'name' => 'Cấp cứu Y tế 115 / Trạm Y tế Phường',
                    'category' => 'medical',
                    'phone' => '115',
                    'address' => 'Trạm Y tế Phường (Số 5 Đường Lớn)',
                    'description' => 'Cấp cứu y tế & hỗ trợ sức khỏe khẩn cấp',
                    'is_active' => true,
                    'order' => 3,
                ],
                [
                    'id' => 4,
                    'name' => 'Tổ trưởng Tổ dân phố 1',
                    'category' => 'tdp',
                    'phone' => '0912.345.678',
                    'address' => 'Khu dân cư số 1',
                    'description' => 'Tiếp nhận phản ánh sinh hoạt dân cư TDP 1',
                    'is_active' => true,
                    'order' => 4,
                ],
                [
                    'id' => 5,
                    'name' => 'Tổng đài Hỗ trợ Dịch vụ công',
                    'category' => 'other',
                    'phone' => '1022',
                    'address' => 'UBND Phường',
                    'description' => 'Giải đáp thắc mắc thủ tục hành chính & dịch vụ công',
                    'is_active' => true,
                    'order' => 5,
                ],
            ];

            return response()->json([
                'success' => true,
                'message' => 'Danh sách đường dây nóng (dữ liệu mẫu)',
                'data'    => $fallback,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi kết nối: ' . $e->getMessage(),
            ], 500);
        }
    }
}
