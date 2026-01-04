<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;


Route::get('/', function () {
    // This project is an API-only application; avoid returning Blade views.
    return response()->json(['app' => 'Laravel API', 'status' => 'ok']);
});

// TODO: remove
Route::get('/html', function () {
    return response()->view('example')->header('Content-Type', 'text/html');
});

Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/auth/reset-password', function (Request $request) {
    return view('reset-password', [
        'token' => $request->query('token'),
        'email' => $request->query('email'),
    ]);
})->name('password.reset');
