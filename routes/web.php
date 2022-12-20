<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Lead\LeadController;
use App\Http\Controllers\Admin\BulkController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\SwitchLocaleController;
use App\Http\Controllers\Lead\DashboardController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Lead\AuthenticatedSessionController;
use App\Http\Controllers\Lead\Enrollments\EnrollmentController;
use App\Http\Controllers\Lead\Enrollments\EnrollmentPlanController;
use App\Http\Controllers\Lead\Enrollments\EnrollmentCourseController;
use App\Http\Controllers\Lead\Enrollments\EnrollmentPaymentController;
use App\Http\Controllers\Lead\Enrollments\EnrollmentFinancingController;
use App\Http\Controllers\Lead\Enrollments\EnrollmentValidationController;
use App\Http\Controllers\Admin\EnrollmentController as AdminEnrollmentController;

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
            Route::get("/", [DashboardController::class, "index"])->name("dashboard");

            // Update
            Route::put("/{lead}", [LeadController::class, "update"])->name("update");

            // Auth
            Route::post("/logout", [AuthenticatedSessionController::class, "destroy"])->name("logout");

            // Enrollment
            Route::prefix("/enrollments")
                ->as("enrollments.")
                ->group(function () {
                    Route::post("/", [EnrollmentController::class, "store"])->name("store");

                    Route::middleware(["enrollment"])->group(function () {
                        Route::get("/{enrollment}/course", [EnrollmentCourseController::class, "edit"])
                            ->name("course.edit");
                        Route::patch("/{enrollment}/course", [EnrollmentCourseController::class, "update"])
                            ->name("course.update");

                        Route::get("/{enrollment}/financing", [EnrollmentFinancingController::class, "edit"])
                            ->name("financing.edit");
                        Route::patch("/{enrollment}/financing", [EnrollmentFinancingController::class, "update"])
                            ->name("financing.update");

                        Route::get("/{enrollment}/plan", [EnrollmentPlanController::class, "edit"])
                            ->name("plan.edit");
                        Route::patch("/{enrollment}/plan", [EnrollmentPlanController::class, "update"])
                            ->name("plan.update");

                        Route::get("/{enrollment}/validation", [EnrollmentValidationController::class, "edit"])
                            ->name("validation.edit");
                        Route::patch("/{enrollment}/validation", [EnrollmentValidationController::class, "update"])
                            ->name("validation.update");

                        Route::get("/{enrollment}/payment", [EnrollmentPaymentController::class, "checkout"])
                            ->name("payment.checkout");
                    });

                    Route::get("/{enrollment}/success", [EnrollmentPaymentController::class, "success"])
                        ->name("payment.success");
                });
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

        // Enrollments
        Route::put("/enrollments/{enrollment}/cancel", [AdminEnrollmentController::class, "cancel"])
            ->name("enrollments.cancel");
        Route::resource("enrollments", AdminEnrollmentController::class)->only(["index", "show", "destroy"]);
    });

    // Auth
    require __DIR__ . '/auth.php';
});

// Localization
Route::put("/locale/switch", SwitchLocaleController::class)->name("locale.switch");

// Files
Route::get("/files/{path}", [FileController::class, "serve"])
    ->where('path', '(.*)')
    ->name("files.serve")
    ->middleware(["auth:lead"]);
