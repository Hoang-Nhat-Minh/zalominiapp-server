<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('full_name', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->input('department'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->input('date'));
        }

        $appointments = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total'     => Appointment::count(),
            'pending'   => Appointment::where('status', 'pending')->count(),
            'approved'  => Appointment::where('status', 'approved')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count()
        ];

        $users = User::select('id', 'full_name', 'name', 'phone')->latest()->limit(200)->get();

        return view('frontend.appointments.index', compact('appointments', 'stats', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'title'            => 'required|string|max:255',
            'department'       => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'note'             => 'nullable|string|max:1000',
        ], [
            'user_id.required'          => 'Vui lòng chọn công dân đặt lịch',
            'title.required'            => 'Vui lòng nhập tiêu đề lịch hẹn',
            'department.required'       => 'Vui lòng chọn hoặc nhập phòng ban',
            'appointment_date.required' => 'Vui lòng chọn ngày hẹn',
            'appointment_time.required' => 'Vui lòng chọn giờ hẹn',
        ]);

        Appointment::create([
            'user_id'          => $request->user_id,
            'title'            => $request->title,
            'department'       => $request->department,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'note'             => $request->note,
            'status'           => 'approved', // Admin creating manually auto-approves
        ]);

        return redirect()->route('appointments')->with('success', 'Tạo mới lịch hẹn thành công!');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,completed,cancelled',
        ]);

        $appointment->update([
            'status' => $request->status,
        ]);

        $statusText = match ($request->status) {
            'approved'  => 'Duyệt lịch hẹn thành công!',
            'completed' => 'Cập nhật hoàn thành lịch hẹn!',
            'cancelled' => 'Đã hủy lịch hẹn!',
            default     => 'Cập nhật trạng thái thành công!'
        };

        return redirect()->back()->with('success', $statusText);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->back()->with('success', 'Xóa lịch hẹn thành công!');
    }
}