<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments=Appointment::with('user')->latest()->paginate(20);

        $stats=[
            'total'=>Appointment::count(),
            'pending'=>Appointment::where('status','pending')->count(),
            'approved'=>Appointment::where('status','approved')->count(),
            'completed'=>Appointment::where('status','completed')->count(),
            'cancelled'=>Appointment::where('status','cancelled')->count()
        ];

        return view('frontend.appointments.index',compact('appointments','stats'));
    }
}