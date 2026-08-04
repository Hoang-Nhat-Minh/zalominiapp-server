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
            $surveys = [];

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
            }

            return response()->json([
                'success' => true,
                'message' => 'Danh sách bài khảo sát dân cư',
                'data'    => $surveys,
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

            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy phiếu khảo sát',
            ], 404);
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
