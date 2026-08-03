<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            $reports = Report::where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Danh sách phản ánh',
                'data'    => $reports,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'    => 'required|in:environment,urban_order,traffic,infrastructure,electricity_water',
            'title'       => 'required|string|min:10|max:255',
            'description' => 'required|string|min:15|max:1000',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'address'     => 'nullable|string|max:500',
        ], [
            'category.required'    => 'Vui lòng chọn danh mục',
            'category.in'          => 'Danh mục không hợp lệ',
            'title.required'       => 'Vui lòng nhập tiêu đề',
            'title.min'            => 'Tiêu đề phải có ít nhất 10 ký tự',
            'title.max'            => 'Tiêu đề không được vượt quá 255 ký tự',
            'description.required' => 'Vui lòng nhập nội dung chi tiết',
            'description.min'      => 'Nội dung phải có ít nhất 15 ký tự',
            'description.max'      => 'Nội dung không được vượt quá 1000 ký tự',
            'images.*.image'       => 'File phải là hình ảnh',
            'images.*.mimes'       => 'Hình ảnh phải có định dạng jpeg, png, jpg',
            'images.*.max'         => 'Mỗi ảnh không được vượt quá 5MB',
            'address.max'          => 'Địa chỉ không được vượt quá 500 ký tự',
        ]);

        try {
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $imagePaths[] = $img->store('reports/images', 'public');
                }
            }

            $report = Report::create([
                'user_id'     => $request->user()->id,
                'category'    => $request->category,
                'title'       => $request->title,
                'description' => $request->description,
                'images'      => json_encode($imagePaths),
                'address'     => $request->address,
                'status'      => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gửi phản ánh thành công',
                'data'    => $report,
            ], 201);
        } catch (\Exception $e) {
            \Log::error('STORE REPORT ERROR', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function location(Request $request)
    {
        $request->validate([
            'token'        => 'required|string',
            'access_token' => 'required|string',
        ]);

        $response = Http::withHeaders([
            'access_token' => $request->access_token,
            'code'         => $request->token,
            'secret_key'   => config('services.zalo.secret_key'),
        ])->get('https://graph.zalo.me/v2.0/me/info');

        $body = $response->json();

        if ($body['error'] !== 0) {
            return response()->json([
                'success' => false,
                'message' => $body['message'] ?? 'Không lấy được vị trí',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data'    => $body['data'],
        ]);
    }
}
