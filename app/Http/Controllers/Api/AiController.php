<?php
//
//namespace App\Http\Controllers\Api;
//
//use App\Http\Controllers\Controller;
//use App\Models\AiChat;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Http;
//
//class AiController extends Controller
//{
//    public function chat(Request $request)
//    {
//        $request->validate([
//            'message' => 'required|string|max:2000',
//        ]);
//
//        try {
//            // Lấy lịch sử chat gần nhất để làm context
//            $history = AiChat::where('user_id', $request->user()->id)
//                ->orderBy('created_at', 'desc')
//                ->take(10)
//                ->get()
//                ->reverse()
//                ->map(fn($chat) => [
//                    ['role' => 'user',  'parts' => [['text' => $chat->message]]],
//                    ['role' => 'model', 'parts' => [['text' => $chat->reply]]],
//                ])
//                ->flatten(1)
//                ->values()
//                ->toArray();
//
//            // Thêm message hiện tại vào cuối
//            $history[] = [
//                'role'  => 'user',
//                'parts' => [['text' => $request->message]],
//            ];
//
//            // Gọi Gemini API
//            $response = Http::withHeaders([
//                'Content-Type' => 'application/json',
//            ])->post(
//                'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . config('services.gemini.api_key'),
//                [
//                    'system_instruction' => [
//                        'parts' => [[
//                            'text' => 'Bạn là trợ lý AI của UBND phường, hỗ trợ người dân tra cứu thông tin hành chính, hướng dẫn thủ tục, đặt lịch hẹn và giải đáp các thắc mắc liên quan đến dịch vụ công. Hãy trả lời ngắn gọn, rõ ràng, lịch sự bằng tiếng Việt.',
//                        ]],
//                    ],
//                    'contents' => $history,
//                ]
//            );
//
//            if ($response->failed()) {
//                return response()->json([
//                    'success' => false,
//                    'message' => 'Không thể kết nối AI, vui lòng thử lại',
//                ], 500);
//            }
//
//            if ($response->failed()) {
//                \Log::error('AI request failed', [
//                    'status' => $response->status(),
//                    'body' => $response->body(),
//                    'headers' => $response->headers(),
//                ]);
//
//                return response()->json([
//                    'success' => false,
//                    'message' => 'Không thể kết nối AI, vui lòng thử lại',
//                ], 500);
//            }
//
//            $reply = $response->json('candidates.0.content.parts.0.text') ?? 'Xin lỗi, tôi không thể trả lời lúc này.';
//
//            // Lưu lịch sử chat
//            AiChat::create([
//                'user_id' => $request->user()->id,
//                'message' => $request->message,
//                'reply'   => $reply,
//            ]);
//
//            return response()->json([
//                'success' => true,
//                'message' => 'Thành công',
//                'data'    => [
//                    'reply' => $reply,
//                ],
//            ]);
//        } catch (\Exception $e) {
//            \Log::error('AI request failed', [
//                'message' => $e,
//            ]);
//
//            return response()->json([
//                'success' => false,
//                'message' => 'Lỗi server: ' . $e->getMessage(),
//            ], 500);
//        }
//    }
//
//    public function history(Request $request)
//    {
//        try {
//            $chats = AiChat::where('user_id', $request->user()->id)
//                ->orderBy('created_at', 'asc')
//                ->paginate(20);
//
//            return response()->json([
//                'success' => true,
//                'message' => 'Lịch sử chat',
//                'data'    => $chats,
//            ]);
//        } catch (\Exception $e) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Lỗi server: ' . $e->getMessage(),
//            ], 500);
//        }
//    }
//}


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . config('services.gemini.api_key'),
                [
                    'system_instruction' => [
                        'parts' => [[
                            'text' => 'Bạn là trợ lý AI của UBND phường, hỗ trợ người dân tra cứu thông tin hành chính, hướng dẫn thủ tục, đặt lịch hẹn và giải đáp các thắc mắc liên quan đến dịch vụ công. Hãy trả lời ngắn gọn, rõ ràng, lịch sự bằng tiếng Việt.',
                        ]],
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [['text' => $request->message]],
                        ],
                    ],
                ]
            );

            if ($response->failed()) {
                \Log::error('Gemini API failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể kết nối AI, vui lòng thử lại',
                ], 500);
            }

            $reply = $response->json('candidates.0.content.parts.0.text')
                ?? 'Xin lỗi, tôi không thể trả lời lúc này.';

            return response()->json([
                'success' => true,
                'message' => 'Thành công',
                'data' => ['reply' => $reply],
            ]);

        } catch (\Exception $e) {
            \Log::error('AI chat error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }
}
