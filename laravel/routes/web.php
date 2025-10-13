<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminPinController;

// Home
Route::view('/', 'home');

// Admin PIN login/logout
Route::get('/admin/login',  [AdminPinController::class, 'show'])->name('admin.login');
Route::post('/admin/login', [AdminPinController::class, 'verify'])->name('admin.login.verify');
Route::post('/admin/logout', [AdminPinController::class, 'logout'])->name('admin.logout');

// Beheer — beveiligd met PIN
Route::get('/reports/beheer', [ReportController::class, 'beheer'])
    ->middleware('admin.pin')
    ->name('reports.beheer');

// Redirect /beheer → juiste URL
Route::redirect('/beheer', '/reports/beheer');

// Publiek overzicht
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// Resource voor CRUD
Route::resource('reports', ReportController::class)->except(['index']);
