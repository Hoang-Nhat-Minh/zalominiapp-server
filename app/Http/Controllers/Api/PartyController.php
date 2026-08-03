<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PartyDocument;
use App\Models\PartyVote;
use App\Models\PartyVoteResponse;
use Illuminate\Http\Request;

class PartyController extends Controller
{
    // ==================== DOCUMENTS ====================

    public function documents(Request $request)
    {
        try {
            $query = PartyDocument::published();

            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            $documents = $query->orderBy('published_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Danh sách tài liệu Đảng',
                'data'    => $documents,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function documentShow(int $id)
    {
        try {
            $document = PartyDocument::published()->find($id);

            if (!$document) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy tài liệu',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Chi tiết tài liệu',
                'data'    => $document,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ==================== VOTES ====================

    public function votes(Request $request)
    {
        try {
            $votes = PartyVote::withCount('responses')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Danh sách biểu quyết',
                'data'    => $votes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function voteShow(Request $request, int $id)
    {
        try {
            $vote = PartyVote::withCount('responses')->find($id);

            if (!$vote) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy biểu quyết',
                ], 404);
            }

            $hasVoted = $vote->hasVoted($request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Chi tiết biểu quyết',
                'data'    => array_merge($vote->toArray(), [
                    'has_voted' => $hasVoted,
                ]),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function voteSubmit(Request $request, int $id)
    {
        $request->validate([
            'answer'  => 'required|in:agree,disagree,abstain',
            'comment' => 'nullable|string|max:1000',
        ]);

        try {
            $vote = PartyVote::find($id);

            if (!$vote) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy biểu quyết',
                ], 404);
            }

            if (!$vote->isOpen()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Biểu quyết đã đóng',
                ], 422);
            }

            if ($vote->hasVoted($request->user()->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã biểu quyết rồi',
                ], 422);
            }

            $response = PartyVoteResponse::create([
                'vote_id' => $id,
                'user_id' => $request->user()->id,
                'answer'  => $request->answer,
                'comment' => $request->comment,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Biểu quyết thành công',
                'data'    => $response,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }
}
