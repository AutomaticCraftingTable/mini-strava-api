<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ActivityController as AdminActivityController;
use App\Http\Controllers\Admin\StatsController as AdminStatsController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\RankingController;
use Spatie\Permission\Middleware\RoleMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->put('/user', [UserController::class, 'update'])->name('auth.user.update');
Route::middleware('auth:sanctum')->get('/user/stats', [UserController::class, 'stats'])->name('user.stats');

Route::prefix('auth')->group(function () {
    Route::name('auth.')->group(function () {
        Route::post('/register', [RegisterController::class, 'register'])->name('register');
        Route::post('/login', [LoginController::class, 'login'])->name('login');
        Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout'])->name('logout');
    });

    Route::post('/send-reset-password-email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('auth.password.reset');
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/export', [ActivityController::class, 'exportAll'])->name('activities.export_all');
    Route::post('/activity', [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/activities/{id}', [ActivityController::class, 'show'])->name('activities.show');
    Route::get('/activities/{id}/export', [ActivityController::class, 'export'])->name('activities.export');
    Route::delete('/activities/{id}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
});

Route::prefix('admin')->group(function () {
    Route::post('/auth/login', [AdminLoginController::class, 'login'])->name('admin.auth.login');

    Route::middleware(['auth:sanctum', RoleMiddleware::class . ':admin'])->group(function () {
        Route::get('/', function () {
            return ['message' => 'Welcome to the Admin Panel'];
        });

        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/activities', [AdminActivityController::class, 'index'])->name('admin.activities.index');
        Route::delete('/activities/{id}', [AdminActivityController::class, 'destroy'])->name('admin.activities.destroy');
        Route::get('/stats', [AdminStatsController::class, 'index'])->name('admin.stats.index');
    });
});