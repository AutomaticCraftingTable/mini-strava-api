<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmailVerificationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/hello', function () {
    return ["message" => "Hello Laravel API"];
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/verified-hello', function () {
        return ["message" => "Hello Verified API"];
    });
});

// Email verification routes for API
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::middleware('auth:sanctum')->post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->name('verification.send');