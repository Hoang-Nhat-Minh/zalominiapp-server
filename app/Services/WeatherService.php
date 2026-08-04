<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    public function getWeather($lat = '21.0285', $lng = '105.8542'): array
    {
        $lat = $lat ?: '21.0285';
        $lng = $lng ?: '105.8542';

        $cacheKey = "weather_forecast_{$lat}_{$lng}";

        $forecast = Cache::remember($cacheKey, 1800, function () use ($lat, $lng) {
            return $this->fetchOpenMeteoWeather($lat, $lng);
        });

        // Nếu dữ liệu trả về null, xóa cache ngay để không bị kẹt cache null trong 30 phút
        if (is_null($forecast['current'] ?? null)) {
            Log::warning('[WeatherService] Weather forecast "current" is NULL. Clearing cache so next request retries API.', [
                'cacheKey' => $cacheKey,
            ]);
            Cache::forget($cacheKey);
        }

        return $forecast;
    }

    public function fetchOpenMeteoWeather($lat, $lng): array
    {
        try {
            $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lng}&current=temperature_2m,relative_humidity_2m,apparent_temperature,is_day,precipitation,rain,weather_code,surface_pressure,wind_speed_10m&hourly=temperature_2m,relative_humidity_2m,weather_code,pop&daily=weather_code,temperature_2m_max,temperature_2m_min,uv_index_max,precipitation_sum&timezone=Asia%2FHo_Chi_Minh";

            $response = Http::timeout(5)->get($url);

            if (!$response->successful()) {
                Log::warning('[WeatherService] Open-Meteo HTTP request failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                    'lat'    => $lat,
                    'lng'    => $lng,
                ]);
                return $this->getFallbackWeatherData('HTTP request returned status ' . $response->status());
            }

            $json = $response->json();
            if (empty($json)) {
                Log::warning('[WeatherService] Open-Meteo returned empty or null JSON payload', [
                    'lat' => $lat,
                    'lng' => $lng,
                ]);
                return $this->getFallbackWeatherData('Empty JSON response from Open-Meteo');
            }

            $current = $json['current'] ?? null;
            $daily   = $json['daily'] ?? null;
            $hourly  = $json['hourly'] ?? null;

            if (is_null($current)) {
                Log::warning('[WeatherService] Open-Meteo payload missing "current" field (null or undefined)', [
                    'json_keys' => array_keys($json),
                ]);
                return $this->getFallbackWeatherData('Missing "current" field in Open-Meteo JSON');
            }

            // Ghi Log cảnh báo nếu phát hiện field con nào trong current bị null hoặc undefined
            $requiredFields = ['temperature_2m', 'apparent_temperature', 'relative_humidity_2m', 'wind_speed_10m', 'surface_pressure', 'weather_code'];
            $nullFields = [];
            foreach ($requiredFields as $field) {
                if (!isset($current[$field]) || is_null($current[$field])) {
                    $nullFields[] = $field;
                }
            }

            if (!empty($nullFields)) {
                Log::warning('[WeatherService] Open-Meteo "current" payload contains null or undefined fields', [
                    'null_fields'     => $nullFields,
                    'current_payload' => $current,
                ]);
            }

            $uvIndex = $daily['uv_index_max'][0] ?? null;
            if (is_null($uvIndex)) {
                Log::warning('[WeatherService] Open-Meteo "uv_index_max" is null or undefined in daily payload', [
                    'daily' => $daily,
                ]);
            }

            return [
                'current' => [
                    'temp'           => round($current['temperature_2m'] ?? 0),
                    'feels_like'     => round($current['apparent_temperature'] ?? 0),
                    'humidity'       => round($current['relative_humidity_2m'] ?? 0),
                    'wind_speed'     => round($current['wind_speed_10m'] ?? 0),
                    'pressure'       => round($current['surface_pressure'] ?? 0),
                    'weather_code'   => $current['weather_code'] ?? 0,
                    'condition_text' => $this->mapWeatherCode($current['weather_code'] ?? 0),
                    'uv_index'       => round($uvIndex ?? 0),
                    'aqi'            => 'Tốt',
                    'location_name'  => 'Địa bàn Phường',
                    'updated_at'     => now()->format('H:i - d/m/Y'),
                ],
                'hourly' => $this->parseHourlyData($hourly),
                'daily'  => $this->parseDailyData($daily),
            ];
        } catch (\Exception $e) {
            Log::error('[WeatherService] Exception caught during fetchOpenMeteoWeather', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
            return $this->getFallbackWeatherData('Exception: ' . $e->getMessage());
        }
    }

    private function parseHourlyData($hourly): array
    {
        if (empty($hourly) || !is_array($hourly)) {
            Log::warning('[WeatherService] Hourly weather payload is null, empty or invalid');
            return [];
        }

        $result = [];
        $times = $hourly['time'] ?? [];
        $temps = $hourly['temperature_2m'] ?? [];
        $codes = $hourly['weather_code'] ?? [];
        $pops  = $hourly['pop'] ?? [];

        $nowHour = (int)now()->format('H');
        for ($i = $nowHour; $i < min($nowHour + 12, count($times)); $i++) {
            $hourStr = isset($times[$i]) ? date('H:i', strtotime($times[$i])) : "{$i}:00";
            $result[] = [
                'time'      => $hourStr,
                'temp'      => round($temps[$i] ?? 0),
                'code'      => $codes[$i] ?? 0,
                'condition' => $this->mapWeatherCode($codes[$i] ?? 0),
                'pop'       => round($pops[$i] ?? 0) . '%',
            ];
        }

        return $result;
    }

    private function parseDailyData($daily): array
    {
        if (empty($daily) || !is_array($daily)) {
            Log::warning('[WeatherService] Daily weather payload is null, empty or invalid');
            return [];
        }

        $result = [];
        $days  = $daily['time'] ?? [];
        $maxs  = $daily['temperature_2m_max'] ?? [];
        $mins  = $daily['temperature_2m_min'] ?? [];
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

    public function mapWeatherCode(int $code): string
    {
        return match (true) {
            $code === 0                           => 'Nắng quang mây',
            in_array($code, [1, 2, 3])            => 'Có mây rải rác',
            in_array($code, [45, 48])             => 'Có sương mù',
            in_array($code, [51, 53, 55, 56, 57]) => 'Mưa phun dầm',
            in_array($code, [61, 63, 65, 66, 67]) => 'Mưa vừa đến mưa to',
            in_array($code, [80, 81, 82])         => 'Mưa rào nặng hạt',
            in_array($code, [95, 96, 99])         => 'Giông bão có sấm sét',
            default                               => 'Trời nhiều mây',
        };
    }

    private function getFallbackWeatherData(string $reason = ''): array
    {
        Log::warning('[WeatherService] Returning fallback weather data (current=null)', [
            'reason' => $reason
        ]);

        return [
            'current' => null,
            'hourly'  => [],
            'daily'   => [],
            'alerts'  => [],
        ];
    }
}
