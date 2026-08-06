<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PartyDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = PartyDocument::with('author');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $documents = $query->latest()->paginate(10)->withQueryString();

        $stats = (object)[
            'total'     => PartyDocument::count(),
            'published' => PartyDocument::where('status', 'published')->count(),
            'draft'     => PartyDocument::where('status', 'draft')->count(),
            'archived'  => PartyDocument::where('status', 'archived')->count(),
        ];

        return view('frontend.documents.index', compact('stats', 'documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string',
            'status'      => 'required|in:draft,published,archived',
            'description' => 'nullable|string',
            'file'        => 'required|file|max:10240',
        ], [
            'title.required'    => 'Vui lòng nhập tiêu đề tài liệu',
            'category.required' => 'Vui lòng chọn danh mục tài liệu',
            'file.required'     => 'Vui lòng tải lên file tài liệu đính kèm',
            'file.max'          => 'Kích thước file tài liệu tối đa 10MB',
        ]);

        $filePath = $request->file('file')->store('documents', 'public');

        PartyDocument::create([
            'author_id'    => auth()->id() ?: 1,
            'title'        => $request->title,
            'description'  => $request->description,
            'category'     => $request->category,
            'status'       => $request->status,
            'file_path'    => $filePath,
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        return redirect()->route('documents')->with('success', 'Thêm tài liệu mới thành công!');
    }

    public function updateStatus(Request $request, $id)
    {
        $document = PartyDocument::findOrFail($id);

        $request->validate([
            'status' => 'required|in:draft,published,archived',
        ]);

        $updateData = ['status' => $request->status];
        if ($request->status === 'published' && !$document->published_at) {
            $updateData['published_at'] = now();
        }

        $document->update($updateData);

        return redirect()->back()->with('success', 'Cập nhật trạng thái tài liệu thành công!');
    }

    public function download($id)
    {
        $document = PartyDocument::findOrFail($id);

        if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'File tài liệu không tồn tại trên hệ thống!');
        }

        return Storage::disk('public')->download($document->file_path);
    }

    public function destroy($id)
    {
        $document = PartyDocument::findOrFail($id);

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->back()->with('success', 'Xóa tài liệu thành công!');
    }
}