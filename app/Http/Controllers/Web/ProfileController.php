<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles=Profile::with(['user','officer'])->latest()->paginate(20);

        $stats=[
            'total'=>Profile::count(),
            'received'=>Profile::where('status','received')->count(),
            'processing'=>Profile::where('status','processing')->count(),
            'waiting'=>Profile::where('status','waiting')->count(),
            'completed'=>Profile::where('status','completed')->count(),
            'rejected'=>Profile::where('status','rejected')->count()
        ];

        $statusClasses=[
            'received'=>'profiles-badge-received',
            'processing'=>'profiles-badge-processing',
            'waiting'=>'profiles-badge-waiting',
            'completed'=>'profiles-badge-completed',
            'rejected'=>'profiles-badge-rejected'
        ];

        $statusTexts=[
            'received'=>'Đã tiếp nhận',
            'processing'=>'Đang xử lý',
            'waiting'=>'Chờ bổ sung',
            'completed'=>'Hoàn thành',
            'rejected'=>'Từ chối'
        ];

        return view('frontend.profiles.index',compact('profiles','stats','statusClasses','statusTexts'));
    }
}