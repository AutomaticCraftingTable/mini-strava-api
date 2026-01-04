<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index()
    {
        // Calculate the range for the previous full week (Monday to Sunday).
        // If today is Monday, the previous week ended yesterday.
        // If today is Sunday, the previous week ended last Sunday.
        $startOfPreviousWeek = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY);
        $endOfPreviousWeek = Carbon::now()->subWeek()->endOfWeek(Carbon::SUNDAY);

        $ranking = User::select('users.name', 'users.surname')
            ->selectRaw('COALESCE(SUM(activities.distance), 0) as week_distance')
            ->leftJoin('activities', function ($join) use ($startOfPreviousWeek, $endOfPreviousWeek) {
                $join->on('users.id', '=', 'activities.user_id')
                    ->whereBetween('activities.created_at', [$startOfPreviousWeek, $endOfPreviousWeek]);
            })
            ->groupBy('users.id', 'users.name', 'users.surname')
            ->having('week_distance', '>', 0)
            ->orderByDesc('week_distance')
            ->get()
            ->map(function ($user) {
                $user->week_distance = (float) $user->week_distance;
                return $user;
            });

        return response()->json($ranking);
    }
}