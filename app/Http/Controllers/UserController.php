<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string|max:255',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
        ]);

        $user->update($data);

        return response()->json($user);
    }

    public function stats(Request $request)
    {
        $stats = $request->user()->activities()
            ->toBase()
            ->selectRaw('count(*) as activities_number, sum(distance) as distance_total, avg(speed) as speed_avg')
            ->first();

        return response()->json([
            'activities_number' => (int) $stats->activities_number,
            'distance_total' => (float) $stats->distance_total,
            'speed_avg' => (float) $stats->speed_avg,
        ]);
    }
}