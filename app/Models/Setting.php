<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        try {
            if (!Schema::hasTable('settings')) {
                return $default;
            }

            return Cache::remember("setting_{$key}", 86400, function () use ($key, $default) {
                $setting = static::where('key', $key)->first();
                return $setting && !is_null($setting->value) ? $setting->value : $default;
            });
        } catch (\Exception $e) {
            return $default;
        }
    }

    public static function set(string $key, $value): void
    {
        try {
            if (Schema::hasTable('settings')) {
                static::updateOrCreate(['key' => $key], ['value' => $value]);
                Cache::forget("setting_{$key}");
            }
        } catch (\Exception $e) {
            // Silently fail if table doesn't exist yet
        }
    }
}
