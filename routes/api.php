<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/verified-hello', function () {
        return ["message" => "Hello Verified API"];
    });
});

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::middleware('auth:sanctum')->post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->name('verification.send');