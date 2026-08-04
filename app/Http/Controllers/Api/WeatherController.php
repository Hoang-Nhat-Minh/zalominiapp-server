<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WeatherAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Default location: Hanoi coordinates (21.0285, 105.8542) or request coords
            $lat = $request->input('lat', '21.0285');
            $lng = $request->input('lng', '105.8542');

            // Cache live weather data for 30 minutes to optimize API calls
            $cacheKey = "weather_forecast_{$lat}_{$lng}";
            $forecast = Cache::remember($cacheKey, 1800, function () use ($lat, $lng) {
                return $this->fetchOpenMeteoWeather($lat, $lng);
            });

            // Fetch active weather alerts from database
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
                    'current' => $forecast['current'] ?? null,
                    'hourly'  => $forecast['hourly'] ?? [],
                    'daily'   => $forecast['daily'] ?? [],
                    'alerts'  => $alerts,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi lấy dữ liệu thời tiết: ' . $e->getMessage(),
                'data'    => $this->getFallbackWeatherData()
            ], 500);
        }
    }

    private function fetchOpenMeteoWeather($lat, $lng): array
    {
        try {
            $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lng}&current=temperature_2m,relative_humidity_2m,apparent_temperature,is_day,precipitation,rain,weather_code,surface_pressure,wind_speed_10m&hourly=temperature_2m,relative_humidity_2m,weather_code,pop&daily=weather_code,temperature_2m_max,temperature_2m_min,uv_index_max,precipitation_sum&timezone=Asia%2FHo_Chi_Minh";

            $response = Http::timeout(5)->get($url);

            if ($response->successful()) {
                $json = $response->json();
                $current = $json['current'] ?? [];
                $daily = $json['daily'] ?? [];
                $hourly = $json['hourly'] ?? [];

                return [
                    'current' => [
                        'temp'           => round($current['temperature_2m'] ?? 0),
                        'feels_like'     => round($current['apparent_temperature'] ?? 0),
                        'humidity'       => round($current['relative_humidity_2m'] ?? 0),
                        'wind_speed'     => round($current['wind_speed_10m'] ?? 0),
                        'pressure'       => round($current['surface_pressure'] ?? 0),
                        'weather_code'   => $current['weather_code'] ?? 0,
                        'condition_text' => $this->mapWeatherCode($current['weather_code'] ?? 0),
                        'uv_index'       => round($daily['uv_index_max'][0] ?? 0),
                        'aqi'            => 'Tốt',
                        'location_name'  => 'Địa bàn Phường',
                        'updated_at'     => now()->format('H:i - d/m/Y'),
                    ],
                    'hourly' => $this->parseHourlyData($hourly),
                    'daily'  => $this->parseDailyData($daily),
                ];
            }
        } catch (\Exception $e) {
            // Log or ignore
        }

        return $this->getFallbackWeatherData();
    }

    private function parseHourlyData($hourly): array
    {
        $result = [];
        $times = $hourly['time'] ?? [];
        $temps = $hourly['temperature_2m'] ?? [];
        $codes = $hourly['weather_code'] ?? [];
        $pops  = $hourly['pop'] ?? [];

        $nowHour = (int)now()->format('H');
        for ($i = $nowHour; $i < min($nowHour + 12, count($times)); $i++) {
            $hourStr = isset($times[$i]) ? date('H:i', strtotime($times[$i])) : "{$i}:00";
            $result[] = [
                'time' => $hourStr,
                'temp' => round($temps[$i] ?? 0),
                'code' => $codes[$i] ?? 0,
                'condition' => $this->mapWeatherCode($codes[$i] ?? 0),
                'pop'  => round($pops[$i] ?? 0) . '%',
            ];
        }

        return $result;
    }

    private function parseDailyData($daily): array
    {
        $result = [];
        $days = $daily['time'] ?? [];
        $maxs = $daily['temperature_2m_max'] ?? [];
        $mins = $daily['temperature_2m_min'] ?? [];
        $codes = $daily['weather_code'] ?? [];

        for ($i = 0; $i < min(7, count($days)); $i++) {
            $dateStr = isset($days[$i]) ? date('d/m', strtotime($days[$i])) : "Ngày {$i}";
            $dayName = $i === 0 ? 'Hôm nay' : ($i === 1 ? 'Ngày mai' : 'Thứ ' . date('N', strtotime($days[$i] ?? 'now')));

            $result[] = [
                'day'       => $dayName,
                'date'      => $dateStr,
                'temp_max'  => round($maxs[$i] ?? 0),
                'temp_min'  => round($mins[$i] ?? 0),
                'code'      => $codes[$i] ?? 0,
                'condition' => $this->mapWeatherCode($codes[$i] ?? 0),
            ];
        }

        return $result;
    }

    private function mapWeatherCode(int $code): string
    {
        return match (true) {
            $code === 0                 => 'Nắng quang mây',
            in_array($code, [1, 2, 3])  => 'Có mây rải rác',
            in_array($code, [45, 48])   => 'Có sương mù',
            in_array($code, [51, 53, 55, 56, 57]) => 'Mưa phun dầm',
            in_array($code, [61, 63, 65, 66, 67]) => 'Mưa vừa đến mưa to',
            in_array($code, [80, 81, 82]) => 'Mưa rào nặng hạt',
            in_array($code, [95, 96, 99]) => 'Giông bão có sấm sét',
            default                     => 'Trời nhiều mây',
        };
    }

    private function getFallbackWeatherData(): array
    {
        return [
            'current' => null,
            'hourly'  => [],
            'daily'   => [],
            'alerts'  => [],
        ];
    }
}
