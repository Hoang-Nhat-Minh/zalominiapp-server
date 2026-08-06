<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name'    => Setting::get('site_name', 'Dịch Vụ Số'),
            'favicon'      => Setting::get('favicon'),
            'weather_lat'  => Setting::get('weather_lat', '21.0285'),
            'weather_lng'  => Setting::get('weather_lng', '105.8542'),
            'weather_city' => Setting::get('weather_city', 'Hà Nội'),
        ];

        return view('frontend.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'    => 'nullable|string|max:255',
            'weather_lat'  => 'required|numeric|between:-90,90',
            'weather_lng'  => 'required|numeric|between:-180,180',
            'weather_city' => 'nullable|string|max:255',
            'favicon'      => 'nullable|file|mimes:ico,png,jpg,jpeg,svg|max:2048',
        ], [
            'weather_lat.required' => 'Vui lòng nhập vĩ độ (Latitude)',
            'weather_lat.numeric'  => 'Vĩ độ phải là một số hợp lệ',
            'weather_lng.required' => 'Vui lòng nhập kinh độ (Longitude)',
            'weather_lng.numeric'  => 'Kinh độ phải là một số hợp lệ',
            'favicon.max'          => 'Kích thước file favicon tối đa 2MB',
        ]);

        if ($request->has('site_name')) {
            Setting::set('site_name', $request->input('site_name'));
        }

        Setting::set('weather_lat', $request->input('weather_lat'));
        Setting::set('weather_lng', $request->input('weather_lng'));

        if ($request->has('weather_city')) {
            Setting::set('weather_city', $request->input('weather_city'));
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('settings', $filename, 'public');
            Setting::set('favicon', $path);
        }

        return redirect()->back()->with('success', 'Cập nhật cấu hình hệ thống thành công!');
    }
}
