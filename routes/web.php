<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BulkController;
use App\Http\Controllers\SwitchLocaleController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\PlanController;

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

        // Bulk
        Route::prefix("bulk")->as("bulk.")->group(function () {
            Route::delete("/", [BulkController::class, 'destroy'])->name("destroy");
            Route::get("/export", [BulkController::class, 'export'])->name("export");
        });

        // Course categories
        Route::resource("course-categories", CourseCategoryController::class)->except(["create", "show", "edit"]);

        // Plans
        Route::resource("plans", PlanController::class)->except(["create", "show", "edit"]);
        
    });

    require __DIR__ . '/auth.php';
});
