<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Welcome');
});

/**
 * Admin routes
 */
Route::middleware(["auth"])->prefix("/admin")->name("admin.")->group(function () {
    Route::inertia('/', "Admin/Dashboard")->name('dashboard');
});

require __DIR__ . '/auth.php';
