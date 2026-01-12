<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmailVerificationController;


Route::get('/', function () {
    // This project is an API-only application; avoid returning Blade views.
    return response()->json(['app' => 'Laravel API', 'status' => 'ok']);
});

// TODO: remove
Route::get('/documentation', function () {
    return response()->view('openapi')->header('Content-Type', 'text/html');
});

Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/auth/reset-password', function (Request $request) {
    return view('reset-password', [
        'token' => $request->query('token'),
        'email' => $request->query('email'),
    ]);
})->name('password.reset');

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');
