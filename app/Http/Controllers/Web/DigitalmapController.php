<?php

namespace App\Http\Controllers\Web;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DigitalmapController extends Controller
{
    public function index()
    {
        $reports = Report::select([
            'id',
            'title',
            'address',
            'status',
            'category'
        ])
            ->whereNotNull('address')
            ->get()
            ->map(function (Report $report): ?array {
                $coord = $this->parseCoordinate($report->address);

                if (!$coord) {
                    return null;
                }

                return [
                    'id'       => $report->id,
                    'title'    => $report->title,
                    'category' => 'phan-anh',
                    'status'   => $report->status,
                    'lat'      => $coord['lat'],
                    'lng'      => $coord['lng'],
                ];
            })
            ->filter()
            ->values();

        return view('frontend.digitalmaps.index', compact('reports'));
    }

    private function parseCoordinate(string $address): ?array
    {
        $address = trim($address);

        if (!preg_match('/^\s*(-?\d+(\.\d+)?)\s*,\s*(-?\d+(\.\d+)?)\s*$/', $address)) {
            return null;
        }

        /** @var array{float, float} $parts */
        $parts = array_map('floatval', explode(',', $address));
        [$first, $second] = $parts;

        if (abs($first) <= 90 && abs($second) <= 180) {
            return ['lat' => $first, 'lng' => $second];
        }

        if (abs($first) <= 180 && abs($second) <= 90) {
            return ['lat' => $second, 'lng' => $first];
        }

        return null;
    }
}