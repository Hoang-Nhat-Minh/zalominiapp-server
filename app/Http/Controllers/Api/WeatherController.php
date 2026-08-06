<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WeatherAlert;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index(Request $request)
    {
        try {
            $forecast = $this->weatherService->getWeather();

            $alerts = [];
            if (Schema::hasTable('weather_alerts')) {
                $alerts = WeatherAlert::where('is_active', true)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            return response()->json([
                'success' => true,
                'message' => 'Dữ liệu dự báo thời tiết & cảnh báo cực đoan',
                'data' => [
                    'location' => $forecast['location'] ?? null,
                    'current'  => $forecast['current'] ?? null,
                    'hourly'   => $forecast['hourly'] ?? [],
                    'daily'    => $forecast['daily'] ?? [],
                    'alerts'   => $alerts,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('[WeatherController API] Exception in index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi lấy dữ liệu thời tiết: ' . $e->getMessage(),
                'data'    => [
                    'current' => null,
                    'hourly'  => [],
                    'daily'   => [],
                    'alerts'  => [],
                ]
            ], 500);
        }
    }
}
