<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Post::where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now());

            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            $posts = $query->orderBy('published_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Danh sách bài viết',
                'data'    => $posts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $post = Post::where('id', $id)
                ->where('status', 'published')
                ->with('author')
                ->first();

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy bài viết',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Chi tiết bài viết',
                'data'    => $post,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }
}
