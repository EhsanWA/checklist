<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminPinController;

Route::view('/', 'home');

// Admin PIN login/logout
Route::get('/admin/login',  [AdminPinController::class, 'show'])->name('admin.login');
Route::post('/admin/login', [AdminPinController::class, 'verify'])->name('admin.login.verify');
Route::post('/admin/logout', [AdminPinController::class, 'logout'])->name('admin.logout');

// Beheer — achter PIN
Route::get('/reports/beheer', [ReportController::class, 'beheer'])
    ->middleware('admin.pin')
    ->name('reports.beheer');

Route::redirect('/beheer', '/reports/beheer');

// Publiek overzicht
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

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
