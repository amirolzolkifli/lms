<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseMaterialController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Webhook route (no CSRF protection needed)
Route::post('/webhook/chip', [WebhookController::class, 'handleChipWebhook'])->name('webhook.chip');

// Member area routes (all authenticated users) - /app prefix
Route::middleware(['auth', 'verified'])->prefix('app')->name('app.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Plan selection routes
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/plans/select', [PlanController::class, 'select'])->name('plans.select');

    // Payment redirect routes
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');

    // Course management routes (trainers)
    Route::resource('courses', CourseController::class);

    // Course materials routes
    Route::get('/courses/{course}/materials', [CourseMaterialController::class, 'index'])->name('course-materials.index');
    Route::get('/courses/{course}/materials/create', [CourseMaterialController::class, 'create'])->name('course-materials.create');
    Route::post('/courses/{course}/materials', [CourseMaterialController::class, 'store'])->name('course-materials.store');
    Route::delete('/courses/{course}/materials/{material}', [CourseMaterialController::class, 'destroy'])->name('course-materials.destroy');
    Route::get('/courses/{course}/materials/{material}/download', [CourseMaterialController::class, 'download'])->name('course-materials.download');

    // Admin routes (admin only) - /app/admin prefix
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::put('/settings/pricing', [SettingController::class, 'updatePricing'])->name('settings.pricing.update');
    });
});

// Legacy redirect for old /dashboard URL
Route::get('/dashboard', function () {
    return redirect()->route('app.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
