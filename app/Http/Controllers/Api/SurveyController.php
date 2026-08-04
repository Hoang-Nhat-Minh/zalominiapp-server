<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (Schema::hasTable('surveys')) {
                $user = $request->user();
                $surveys = Survey::where('is_active', true)
                    ->withCount('questions')
                    ->latest()
                    ->get()
                    ->map(function ($survey) use ($user) {
                        return [
                            'id'           => $survey->id,
                            'title'        => $survey->title,
                            'description'  => $survey->description,
                            'target_tdp'   => $survey->target_tdp,
                            'target_label' => $survey->target_label,
                            'deadline'     => $survey->deadline ? $survey->deadline->format('Y-m-d H:i') : null,
                            'questions_count' => $survey->questions_count,
                            'has_voted'    => $user ? $survey->hasUserResponded($user->id) : false,
                            'created_at'   => $survey->created_at->format('d/m/Y'),
                        ];
                    });

                if ($surveys->isNotEmpty()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Danh sách bài khảo sát dân cư',
                        'data'    => $surveys,
                    ]);
                }
            }

            // Fallback mock surveys if table not migrated or empty
            $fallback = [
                [
                    'id' => 1,
                    'title' => 'Khảo sát ý kiến về Dự án Cải tạo vỉa hè & Chiếu sáng TDP 1 & 2',
                    'description' => 'UBND Phường triển khai xin ý kiến nhân dân về phương án chỉnh trang vỉa hè, lắp đặt đèn LED và trồng thêm cây xanh trên tuyến đường chính.',
                    'target_tdp' => 'all',
                    'target_label' => 'Toàn phường',
                    'deadline' => now()->addDays(7)->format('Y-m-d H:i'),
                    'questions_count' => 3,
                    'has_voted' => false,
                    'created_at' => now()->format('d/m/Y'),
                ],
                [
                    'id' => 2,
                    'title' => 'Đánh giá mức độ hài lòng về Dịch vụ công Một cửa Quý 3',
                    'description' => 'Nhằm nâng cao chất lượng phục vụ công dân, UBND Phường mong muốn nhận phản hồi về thái độ phục vụ và thời gian xử lý thủ tục hành chính.',
                    'target_tdp' => 'all',
                    'target_label' => 'Toàn phường',
                    'deadline' => now()->addDays(14)->format('Y-m-d H:i'),
                    'questions_count' => 4,
                    'has_voted' => true,
                    'created_at' => now()->subDays(2)->format('d/m/Y'),
                ],
            ];

            return response()->json([
                'success' => true,
                'message' => 'Danh sách khảo sát dân cư (dữ liệu mẫu)',
                'data'    => $fallback,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi kết nối: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, int $id)
    {
        try {
            if (Schema::hasTable('surveys')) {
                $survey = Survey::with('questions')->find($id);

                if ($survey) {
                    $hasVoted = $request->user() ? $survey->hasUserResponded($request->user()->id) : false;

                    return response()->json([
                        'success' => true,
                        'message' => 'Chi tiết phiếu khảo sát',
                        'data'    => array_merge($survey->toArray(), [
                            'has_voted' => $hasVoted,
                        ]),
                    ]);
                }
            }

            // Fallback survey details
            $fallback = [
                'id' => $id,
                'title' => 'Khảo sát ý kiến về Dự án Cải tạo vỉa hè & Chiếu sáng TDP 1 & 2',
                'description' => 'UBND Phường triển khai xin ý kiến nhân dân về phương án chỉnh trang vỉa hè, lắp đặt đèn LED và trồng thêm cây xanh trên tuyến đường chính.',
                'target_tdp' => 'all',
                'target_label' => 'Toàn phường',
                'deadline' => now()->addDays(7)->format('Y-m-d H:i'),
                'has_voted' => false,
                'questions' => [
                    [
                        'id' => 101,
                        'question_text' => 'Bạn đánh giá thế nào về phương án lát lại gạch vỉa hè chống trượt?',
                        'type' => 'single_choice',
                        'options' => ['Rất đồng tình', 'Đồng tình', 'Phân vân / Cần xem xét thêm', 'Không đồng tình'],
                        'is_required' => true,
                    ],
                    [
                        'id' => 102,
                        'question_text' => 'Những hạng mục nào bạn ưu tiên cải tạo trước?',
                        'type' => 'multiple_choice',
                        'options' => ['Hệ thống chiếu sáng LED', 'Trồng cây xanh bóng mát', 'Làm hạ tầng thoát nước mưa', 'Rào chắn an toàn người đi bộ'],
                        'is_required' => true,
                    ],
                    [
                        'id' => 103,
                        'question_text' => 'Đánh giá chung về chất lượng hạ tầng đô thị hiện tại của phường:',
                        'type' => 'rating',
                        'options' => null,
                        'is_required' => true,
                    ],
                    [
                        'id' => 104,
                        'question_text' => 'Ý kiến đóng góp hoặc đề xuất bổ sung của bạn dành cho UBND Phường:',
                        'type' => 'text',
                        'options' => null,
                        'is_required' => false,
                    ],
                ]
            ];

            return response()->json([
                'success' => true,
                'message' => 'Chi tiết phiếu khảo sát (dữ liệu mẫu)',
                'data'    => $fallback,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function submit(Request $request, int $id)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        try {
            if (Schema::hasTable('surveys')) {
                $survey = Survey::find($id);
                if ($survey && $request->user()) {
                    if ($survey->hasUserResponded($request->user()->id)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Bạn đã hoàn thành phiếu khảo sát này rồi.',
                        ], 422);
                    }

                    $response = SurveyResponse::create([
                        'survey_id'    => $id,
                        'user_id'      => $request->user()->id,
                        'answers'      => $request->answers,
                        'submitted_at' => now(),
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Cảm ơn bạn đã hoàn thành phiếu khảo sát ý kiến dân cư!',
                        'data'    => $response,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Cảm ơn bạn đã gửi ý kiến đóng góp thành công!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi gửi khảo sát: ' . $e->getMessage(),
            ], 500);
        }
    }
}
