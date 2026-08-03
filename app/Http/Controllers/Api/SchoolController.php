<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = School::query();

            if ($request->filled('level') && $request->input('level') !== 'all') {
                $query->where('level', $request->input('level'));
            }

            if ($request->filled('q')) {
                $q = $request->input('q');
                $query->where(function($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('address', 'like', "%{$q}%");
                });
            }

            $schools = $query->orderBy('name', 'asc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Danh sách cơ sở giáo dục',
                'data' => $schools,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi máy chủ: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $school = School::find($id);

            if (!$school) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy cơ sở giáo dục',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Chi tiết cơ sở giáo dục',
                'data' => $school,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi máy chủ: ' . $e->getMessage(),
            ], 500);
        }
    }
}
