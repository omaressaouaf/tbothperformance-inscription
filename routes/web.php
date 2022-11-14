<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BulkController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\SwitchLocaleController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Lead\AuthenticatedSessionController;
use App\Http\Controllers\Lead\EnrollmentCourseController;
use App\Http\Controllers\Lead\LeadController;

Route::inertia('/', "Welcome");

Route::middleware(["locale"])->group(function () {
    /**
     * Leads routes
     */
    Route::prefix("/lead")->name("lead.")->group(function () {
        Route::middleware("guest:lead")->group(function () {
            // Lead enrollment
            Route::get("/enroll", [LeadController::class, "enroll"])->name("enroll");
            Route::post("/", [LeadController::class, "store"])->name("store");

            // Passwordless link
            Route::get("/passwordless-link", [AuthenticatedSessionController::class, "passwordlessLink"])
                ->name("passwordless-link");
            Route::post("/passwordless-link", [AuthenticatedSessionController::class, "sendPasswordlessLink"])
                ->name("passwordless-link.send");
        });

        Route::middleware(["auth:lead"])->group(function () {
            Route::inertia("/", "Lead/Dashboard")->name("dashboard");

            // Update
            Route::put("/{lead}", [LeadController::class, "update"])->name("update");

            // Auth
            Route::post("/logout", [AuthenticatedSessionController::class, "destroy"])->name("logout");

            // Enrollment
            Route::get("/enrollments/{enrollment}/course", [EnrollmentCourseController::class, "edit"])
                ->name("enrollments.course.edit");
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
