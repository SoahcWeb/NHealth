<?php

use App\Http\Controllers\CheckInController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteModuleController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\HealthProfileController;
use App\Http\Controllers\HealthReminderController;
use App\Http\Controllers\NHealthDashboardController;
use App\Http\Controllers\NHealthExportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WeightEntryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/check-ins', [CheckInController::class, 'index'])->name('check-ins.index');
    Route::post('/check-ins', [CheckInController::class, 'store'])->name('check-ins.store');

    Route::prefix('nhealth')->name('nhealth.')->group(function () {
        Route::get('/dashboard', [NHealthDashboardController::class, 'index'])->name('dashboard');
        Route::get('/export/summary', [NHealthExportController::class, 'summary'])->name('export.summary');
        Route::post('/module/toggle', [FavoriteModuleController::class, 'toggleNhealth'])->name('module.toggle');

        Route::get('/profile', [HealthProfileController::class, 'edit'])->name('profile.edit');
        Route::match(['put', 'patch'], '/profile', [HealthProfileController::class, 'update'])->name('profile.update');

        Route::get('/reminders', [HealthReminderController::class, 'index'])->name('reminders.index');
        Route::match(['put', 'patch'], '/reminders', [HealthReminderController::class, 'update'])->name('reminders.update');

        Route::get('/goals', [GoalController::class, 'index'])->name('goals.index');
        Route::get('/goals/create', [GoalController::class, 'create'])->name('goals.create');
        Route::post('/goals', [GoalController::class, 'store'])->name('goals.store');
        Route::get('/goals/{goal}/edit', [GoalController::class, 'edit'])->name('goals.edit');
        Route::match(['put', 'patch'], '/goals/{goal}', [GoalController::class, 'update'])->name('goals.update');
        Route::delete('/goals/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');

        Route::get('/weight', [WeightEntryController::class, 'index'])->name('weight.index');
        Route::post('/weight', [WeightEntryController::class, 'store'])->name('weight.store');
        Route::get('/weight/{weightEntry}/edit', [WeightEntryController::class, 'edit'])->name('weight.edit');
        Route::match(['put', 'patch'], '/weight/{weightEntry}', [WeightEntryController::class, 'update'])->name('weight.update');
        Route::delete('/weight/{weightEntry}', [WeightEntryController::class, 'destroy'])->name('weight.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
