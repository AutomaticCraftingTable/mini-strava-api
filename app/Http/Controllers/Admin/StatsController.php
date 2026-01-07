<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'users_number' => User::count(),
            'activities_number' => Activity::count(),
            'distance_summary' => Activity::sum('distance'),
        ]);
    }
}