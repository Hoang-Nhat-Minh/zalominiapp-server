<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $query = School::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->input('level'));
        }

        $schools = $query->latest()->paginate(10)->withQueryString();

        $stats = (object)[
            'total' => School::count(),
            'kindergarten' => School::where('level', 'kindergarten')->count(),
            'primary' => School::where('level', 'primary')->count(),
            'secondary' => School::where('level', 'secondary')->count(),
            'high_school' => School::where('level', 'high_school')->count(),
            'other' => School::where('level', 'other')->count(),
        ];

        return view('frontend.schools.index', compact('schools', 'stats'));
    }

    public function create()
    {
        return view('frontend.schools.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|in:kindergarten,primary,secondary,high_school,other',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'description' => 'nullable|string|max:1000',
        ]);

        School::create($validated);

        return redirect()->route('schools.index')->with('success', 'Thêm cơ sở giáo dục thành công!');
    }

    public function edit(School $school)
    {
        return view('frontend.schools.edit', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|in:kindergarten,primary,secondary,high_school,other',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'description' => 'nullable|string|max:1000',
        ]);

        $school->update($validated);

        return redirect()->route('schools.index')->with('success', 'Cập nhật thông tin cơ sở giáo dục thành công!');
    }

    public function destroy(School $school)
    {
        $school->delete();

        return redirect()->route('schools.index')->with('success', 'Xóa cơ sở giáo dục thành công!');
    }
}
