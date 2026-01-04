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
}