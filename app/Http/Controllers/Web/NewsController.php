<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['newsCategory', 'author']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->search($search);
        }

        if ($request->filled('category_id')) {
            $query->where('news_category_id', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $newsList = $query->orderBy('published_at', 'desc')->latest()->paginate(10)->withQueryString();
        $categories = NewsCategory::orderBy('name', 'asc')->get();

        $stats = (object)[
            'total'     => Post::count(),
            'published' => Post::where('status', 'published')->count(),
            'draft'     => Post::where('status', 'draft')->count(),
            'featured'  => Post::where('is_featured', true)->count(),
        ];

        return view('frontend.news.index', compact('newsList', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = NewsCategory::active()->get();
        return view('frontend.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'news_category_id' => 'nullable|exists:news_categories,id',
            'summary'          => 'nullable|string|max:1000',
            'content'          => 'required|string',
            'image_url'        => 'nullable|url',
            'image_file'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'status'           => 'required|in:draft,published,archived',
            'published_at'     => 'nullable|date',
            'is_featured'      => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('posts', 'public');
            $imagePath = asset('storage/' . $path);
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }

        $publishedAt = $request->filled('published_at') 
            ? $request->input('published_at') 
            : ($validated['status'] === 'published' ? now() : null);

        Post::create([
            'author_id'        => auth()->id() ?? 1,
            'title'            => $validated['title'],
            'summary'          => $validated['summary'] ?? null,
            'content'          => $validated['content'],
            'category'         => 'news',
            'news_category_id' => $validated['news_category_id'] ?? null,
            'image'            => $imagePath,
            'status'           => $validated['status'],
            'published_at'     => $publishedAt,
            'is_featured'      => $request->has('is_featured'),
        ]);

        return redirect()->route('news.index')->with('success', 'Thêm bài viết tin tức mới thành công!');
    }

    public function show(Post $news)
    {
        $news->load(['newsCategory', 'author']);
        return view('frontend.news.show', ['news' => $news]);
    }

    public function edit(Post $news)
    {
        $categories = NewsCategory::active()->get();
        return view('frontend.news.edit', ['news' => $news, 'categories' => $categories]);
    }

    public function update(Request $request, Post $news)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'news_category_id' => 'nullable|exists:news_categories,id',
            'summary'          => 'nullable|string|max:1000',
            'content'          => 'required|string',
            'image_url'        => 'nullable|url',
            'image_file'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'status'           => 'required|in:draft,published,archived',
            'published_at'     => 'nullable|date',
            'is_featured'      => 'nullable|boolean',
        ]);

        $imagePath = $news->image;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('posts', 'public');
            $imagePath = asset('storage/' . $path);
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }

        $publishedAt = $request->filled('published_at') 
            ? $request->input('published_at') 
            : ($validated['status'] === 'published' && !$news->published_at ? now() : $news->published_at);

        $news->update([
            'title'            => $validated['title'],
            'summary'          => $validated['summary'] ?? null,
            'content'          => $validated['content'],
            'news_category_id' => $validated['news_category_id'] ?? null,
            'image'            => $imagePath,
            'status'           => $validated['status'],
            'published_at'     => $publishedAt,
            'is_featured'      => $request->has('is_featured'),
        ]);

        return redirect()->route('news.index')->with('success', 'Cập nhật bài viết tin tức thành công!');
    }

    public function destroy(Post $news)
    {
        $news->delete();

        return redirect()->route('news.index')->with('success', 'Xóa bài viết tin tức thành công!');
    }
}
