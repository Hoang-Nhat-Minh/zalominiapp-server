<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        $reports=Report::with('user')->latest()->paginate(20);

        $stats=(object)[
            'total'=>Report::count(),
            'pending'=>Report::where('status','pending')->count(),
            'processing'=>Report::where('status','processing')->count(),
            'resolved'=>Report::where('status','resolved')->count(),
            'rejected'=>Report::where('status','rejected')->count()
        ];

        $statusConfigs=[
            'pending'=>[
                'class'=>'reports-badge-pending',
                'label'=>'Chờ tiếp nhận'
            ],
            'processing'=>[
                'class'=>'reports-badge-processing',
                'label'=>'Đang xử lý'
            ],
            'resolved'=>[
                'class'=>'reports-badge-resolved',
                'label'=>'Đã xử lý'
            ],
            'rejected'=>[
                'class'=>'reports-badge-rejected',
                'label'=>'Từ chối'
            ]
        ];

        $categories=[
            'environment'=>'Môi trường',
            'urban_order'=>'Trật tự đô thị',
            'traffic'=>'Giao thông',
            'infrastructure'=>'Hạ tầng'
        ];

        return view('frontend.reports.index',compact(
            'reports',
            'stats',
            'statusConfigs',
            'categories'
        ));
    }
}