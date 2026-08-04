<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsCategory::withCount('posts');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $categories = $query->orderBy('order', 'asc')->latest()->paginate(10)->withQueryString();

        $stats = (object)[
            'total' => NewsCategory::count(),
            'active' => NewsCategory::where('is_active', true)->count(),
            'inactive' => NewsCategory::where('is_active', false)->count(),
        ];

        return view('frontend.news_categories.index', compact('categories', 'stats'));
    }

    public function create()
    {
        return view('frontend.news_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:news_categories,name',
            'description' => 'nullable|string|max:1000',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->input('order', 0);

        NewsCategory::create($validated);

        return redirect()->route('news-categories.index')->with('success', 'Thêm danh mục tin tức thành công!');
    }

    public function edit(NewsCategory $newsCategory)
    {
        return view('frontend.news_categories.edit', ['category' => $newsCategory]);
    }

    public function update(Request $request, NewsCategory $newsCategory)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:news_categories,name,' . $newsCategory->id,
            'description' => 'nullable|string|max:1000',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->input('order', 0);

        $newsCategory->update($validated);

        return redirect()->route('news-categories.index')->with('success', 'Cập nhật danh mục tin tức thành công!');
    }

    public function destroy(NewsCategory $newsCategory)
    {
        if ($newsCategory->posts()->count() > 0) {
            return redirect()->route('news-categories.index')->with('error', 'Không thể xóa danh mục đang có bài viết!');
        }

        $newsCategory->delete();

        return redirect()->route('news-categories.index')->with('success', 'Xóa danh mục tin tức thành công!');
    }
}
