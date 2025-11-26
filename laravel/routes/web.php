<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminPinController;
use App\Http\Controllers\InspectionListController;

Route::view('/', 'home')->name('home');

// Admin PIN login/logout
Route::get('/admin/login',  [AdminPinController::class, 'show'])->name('admin.login');
Route::post('/admin/login', [AdminPinController::class, 'verify'])->name('admin.login.verify');
Route::post('/admin/logout', [AdminPinController::class, 'logout'])->name('admin.logout');

Route::middleware('admin.pin')->group(function () {
    Route::view('/admin/menu', 'admin.menu')->name('admin.menu');
    Route::get('/reports/beheer', [ReportController::class, 'beheer'])->name('reports.beheer');
    Route::get('/inspections/beheer', [InspectionListController::class, 'beheer'])->name('inspections.beheer');
});

// Beheer – achter PIN
Route::redirect('/beheer', '/admin/menu');

// Publiek overzicht
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// Sent page 
Route::view('/reports/sent', 'sentPage')->name('reports.sent');

/**
 * >>> BELANGRIJK: eerst de beveiligde routes (create/edit/etc.)
 * zodat /reports/create niet wordt overschreven door /reports/{report}
 */
Route::middleware('admin.pin')->group(function () {
    Route::resource('reports', ReportController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
});

// Publieke show-route als laatste (optioneel met constraint)
Route::resource('reports', ReportController::class)
    ->only(['show'])
    ->whereNumber('report'); // voorkom conflict met 'create', 'edit', etc.

Route::post('/reports/{report}/progress', [ReportController::class, 'saveProgress'])
    ->name('reports.progress');
Route::post('/reports/{report}/submit', [ReportController::class, 'submit'])
    ->name('reports.submit');



Route::middleware(['admin.pin'])->group(function () {
    Route::get('/inspections/create', [InspectionListController::class, 'create'])->name('inspections.create');
    Route::post('/inspections', [InspectionListController::class, 'store'])->name('inspections.store');
    Route::get('/inspections/{inspectionList}/edit', [InspectionListController::class, 'edit'])->name('inspections.edit');
    Route::put('/inspections/{inspectionList}', [InspectionListController::class, 'update'])->name('inspections.update');
    Route::delete('/inspections/{inspectionList}', [InspectionListController::class, 'destroy'])->name('inspections.destroy');

    // (optioneel later) edit/update
    Route::get('/inspections/{inspectionList}', [InspectionListController::class, 'show'])->name('inspections.show');
});

// routes/web.php
// Route::post('/inspections', [InspectionListController::class, 'store'])->name('inspections.store'); // tijdelijk buiten group
