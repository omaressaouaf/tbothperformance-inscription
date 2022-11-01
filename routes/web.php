<?php

use App\Models\Course;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SwitchLocaleController;

Route::get('/', function () {
    return inertia('Welcome');
});

// Localization
Route::put("/locale/switch", SwitchLocaleController::class)->name("locale.switch");

Route::middleware(["locale"])->group(function () {
    /**
     * Admin routes
     */
    Route::middleware(["auth"])->prefix("/admin")->name("admin.")->group(function () {
        Route::inertia('/', "Admin/Dashboard")->name('dashboard');
    });

    require __DIR__ . '/auth.php';
});
