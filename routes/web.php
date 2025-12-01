<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // This project is an API-only application; avoid returning Blade views.
    return response()->json(['app' => 'Laravel API', 'status' => 'ok']);
});

Route::get('/html', function () {
    return response()->view('example')->header('Content-Type', 'text/html');
});
