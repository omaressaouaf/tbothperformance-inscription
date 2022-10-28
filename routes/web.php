<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

/**
 * Admin routes
 */
Route::middleware(["auth"])->prefix("/admin")->name("admin.")->group(function () {
    Route::inertia('/', "Admin/Dashboard")->name('dashboard');
});

require __DIR__ . '/auth.php';
