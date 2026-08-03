<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Carbon;

class CitizenController extends Controller
{
    public function index()
    {
        $citizens = User::query()
            ->where('role', 'citizen')
            ->withCount([
                'profiles',
                'appointments',
                'reports',
                'partyVoteResponses',
            ])
            ->latest()
            ->paginate(10);

        $citizens->getCollection()->transform(function ($user) {
            $user->activity = (object)[
                'profiles' => $user->profiles_count,
                'appointments' => $user->appointments_count,
                'reports' => $user->reports_count,
                'votes' => $user->party_vote_responses_count,
            ];

            return $user;
        });

        $stats = (object)[
            'total' => User::where('role', 'citizen')->count(),
            'verified' => User::where('role', 'citizen')
                ->where('is_verified', true)
                ->count(),
            'unverified' => User::where('role', 'citizen')
                ->where('is_verified', false)
                ->count(),
            'active_30d' => User::where('role', 'citizen')
                ->whereNotNull('last_login_at')
                ->where('last_login_at', '>=', Carbon::now()->subDays(30))
                ->count(),
        ];

        return view('frontend.citizens.index', compact(
            'citizens',
            'stats'
        ));
    }
}