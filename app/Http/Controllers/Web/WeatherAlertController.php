<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WeatherAlert;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherAlertController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index(Request $request)
    {
        $query = WeatherAlert::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('area', 'like', "%{$search}%");
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->input('level'));
        }

        $alerts = $query->latest()->paginate(10)->withQueryString();

        $stats = (object)[
            'total'   => WeatherAlert::count(),
            'info'    => WeatherAlert::where('level', 'info')->count(),
            'warning' => WeatherAlert::where('level', 'warning')->count(),
            'danger'  => WeatherAlert::where('level', 'danger')->count(),
            'active'  => WeatherAlert::where('is_active', true)->count(),
        ];

        // Lấy dữ liệu thời tiết thực tế từ Open-Meteo API qua WeatherService
        $weatherData = $this->weatherService->getWeather();
        $currentWeather = $weatherData['current'] ?? null;

        return view('frontend.weather.index', compact('alerts', 'stats', 'currentWeather', 'weatherData'));
    }

    public function create()
    {
        return view('frontend.weather.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'level'     => 'required|in:info,warning,danger',
            'content'   => 'required|string|max:2000',
            'area'      => 'required|string|max:255',
            'start_at'  => 'nullable|date',
            'end_at'    => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        WeatherAlert::create($validated);

        return redirect()->route('weather-alerts.index')->with('success', 'Phát hành cảnh báo thời tiết thành công!');
    }

    public function edit(WeatherAlert $weatherAlert)
    {
        return view('frontend.weather.edit', ['alert' => $weatherAlert]);
    }

    public function update(Request $request, WeatherAlert $weatherAlert)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'level'     => 'required|in:info,warning,danger',
            'content'   => 'required|string|max:2000',
            'area'      => 'required|string|max:255',
            'start_at'  => 'nullable|date',
            'end_at'    => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $weatherAlert->update($validated);

        return redirect()->route('weather-alerts.index')->with('success', 'Cập nhật cảnh báo thời tiết thành công!');
    }

    public function destroy(WeatherAlert $weatherAlert)
    {
        $weatherAlert->delete();

        return redirect()->route('weather-alerts.index')->with('success', 'Xóa bản tin cảnh báo thời tiết thành công!');
    }
}
