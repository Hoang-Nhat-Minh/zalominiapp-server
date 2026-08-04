<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Get active news categories
     */
    public function categories()
    {
        try {
            $categories = NewsCategory::active()
                ->withCount(['posts' => function ($q) {
                    $q->published();
                }])
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Danh sách danh mục tin tức',
                'data'    => $categories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get published news list with filter & search
     */
    public function index(Request $request)
    {
        try {
            $query = Post::published()->with(['newsCategory', 'author']);

            // Category filter (supports category_id or slug)
            if ($request->filled('category_id')) {
                $query->where('news_category_id', $request->input('category_id'));
            } elseif ($request->filled('category')) {
                $catParam = $request->input('category');
                if (is_numeric($catParam)) {
                    $query->where('news_category_id', $catParam);
                } else {
                    $query->whereHas('newsCategory', function ($q) use ($catParam) {
                        $q->where('slug', $catParam)->orWhere('category', $catParam);
                    });
                }
            }

            // Featured filter
            if ($request->has('featured') && $request->boolean('featured')) {
                $query->where('is_featured', true);
            }

            // Search filter
            if ($request->filled('search')) {
                $query->search($request->input('search'));
            } elseif ($request->filled('q')) {
                $query->search($request->input('q'));
            }

            $perPage = $request->input('limit', $request->input('per_page', 10));
            $posts = $query->orderBy('is_featured', 'desc')
                ->orderBy('published_at', 'desc')
                ->paginate($perPage);

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

    /**
     * Get single post detail & increment views count
     */
    public function show(int $id)
    {
        try {
            $post = Post::where('id', $id)
                ->where('status', 'published')
                ->with(['newsCategory', 'author'])
                ->first();

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy bài viết',
                ], 404);
            }

            // Increment views count
            $post->increment('views_count');

            // Related posts in same category
            $related = Post::published()
                ->where('id', '!=', $post->id)
                ->when($post->news_category_id, function ($q) use ($post) {
                    $q->where('news_category_id', $post->news_category_id);
                })
                ->orderBy('published_at', 'desc')
                ->limit(3)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Chi tiết bài viết',
                'data'    => [
                    'post'    => $post,
                    'related' => $related,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }
}
