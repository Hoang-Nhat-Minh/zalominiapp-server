<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $appointments = Appointment::where('user_id', $request->user()->id)
                ->orderBy('appointment_date', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Danh sách lịch hẹn',
                'data'    => $appointments,
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
            'title'            => 'required|string|max:255',
            'department'       => 'required|string|max:255',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'note'             => 'nullable|string|max:1000',
        ], [
            'title.required'            => 'Vui lòng nhập tiêu đề',
            'department.required'       => 'Vui lòng chọn bộ phận',
            'appointment_date.required' => 'Vui lòng chọn ngày hẹn',
            'appointment_date.after_or_equal' => 'Ngày hẹn không được là ngày trong quá khứ',
            'appointment_time.required' => 'Vui lòng chọn giờ hẹn',
            'appointment_time.date_format' => 'Giờ hẹn không hợp lệ',
        ]);

        try {
            $appointmentDateTime = \Carbon\Carbon::parse(
                $request->appointment_date . ' ' . $request->appointment_time
            );

            if ($appointmentDateTime->lt(now())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể đặt lịch trong quá khứ',
                ], 422);
            }

            if ($appointmentDateTime->lt(now()->addHours(2))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giờ hẹn phải cách thời điểm hiện tại ít nhất 2 tiếng',
                ], 422);
            }

            // Kiểm tra trùng lịch hẹn
            $exists = Appointment::where('department', $request->department)
                ->where('appointment_date', $request->appointment_date)
                ->where('appointment_time', $request->appointment_time)
                ->whereNotIn('status', ['cancelled'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Khung giờ này đã có người đặt, vui lòng chọn giờ khác',
                ], 422);
            }

            $appointment = Appointment::create([
                'user_id'          => $request->user()->id,
                'title'            => $request->title,
                'department'       => $request->department,
                'appointment_date' => $request->appointment_date,
                'appointment_time' => $request->appointment_time,
                'note'             => $request->note,
                'status'           => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đặt lịch hẹn thành công',
                'data'    => $appointment,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function cancel(Request $request, int $id)
    {
        try {
            $appointment = Appointment::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy lịch hẹn',
                ], 404);
            }

            if (!$appointment->isPending()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ có thể huỷ lịch hẹn đang chờ duyệt',
                ], 422);
            }

            $appointment->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Huỷ lịch hẹn thành công',
                'data'    => $appointment,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }
}
