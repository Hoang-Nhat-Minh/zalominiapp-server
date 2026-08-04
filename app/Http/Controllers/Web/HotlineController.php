<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hotline;
use Illuminate\Http\Request;

class HotlineController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotline::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $hotlines = $query->orderBy('order', 'asc')->latest()->paginate(10)->withQueryString();

        $stats = (object)[
            'total'   => Hotline::count(),
            'police'  => Hotline::where('category', 'police')->count(),
            'medical' => Hotline::where('category', 'medical')->count(),
            'rescue'  => Hotline::where('category', 'rescue')->count(),
            'tdp'     => Hotline::where('category', 'tdp')->count(),
            'other'   => Hotline::where('category', 'other')->count(),
        ];

        return view('frontend.hotlines.index', compact('hotlines', 'stats'));
    }

    public function create()
    {
        return view('frontend.hotlines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:police,medical,rescue,tdp,other',
            'phone'       => 'required|string|max:50',
            'address'     => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->input('order', 0);

        Hotline::create($validated);

        return redirect()->route('hotlines.index')->with('success', 'Thêm số đường dây nóng thành công!');
    }

    public function edit(Hotline $hotline)
    {
        return view('frontend.hotlines.edit', compact('hotline'));
    }

    public function update(Request $request, Hotline $hotline)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:police,medical,rescue,tdp,other',
            'phone'       => 'required|string|max:50',
            'address'     => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->input('order', 0);

        $hotline->update($validated);

        return redirect()->route('hotlines.index')->with('success', 'Cập nhật thông tin đường dây nóng thành công!');
    }

    public function destroy(Hotline $hotline)
    {
        $hotline->delete();

        return redirect()->route('hotlines.index')->with('success', 'Xóa số đường dây nóng thành công!');
    }
}
