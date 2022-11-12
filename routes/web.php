<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BulkController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\SwitchLocaleController;
use App\Http\Controllers\Admin\CourseCategoryController;

Route::inertia('/', "Welcome");

Route::middleware(["locale"])->group(function () {
    /**
     * Leads routes
     */
    Route::prefix("/lead")->name("lead.")->group(function () {
        Route::inertia("/enroll", "Leads/Enroll")->middleware("guest:lead")->name("enroll");

        Route::middleware(["auth:lead"])->group(function () {
            Route::inertia("/", "Leads/Dashboard")->name("dashboard");
        });
    });

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

        // Plans
        Route::resource("plans", PlanController::class)->except(["create", "show", "edit"]);

        // Course categories
        Route::resource("course-categories", CourseCategoryController::class)->except(["create", "show", "edit"]);

        // Courses
        Route::resource("courses", CourseController::class)->except(["show"]);
    });

    // Auth
    require __DIR__ . '/auth.php';
});

// Localization
Route::put("/locale/switch", SwitchLocaleController::class)->name("locale.switch");
