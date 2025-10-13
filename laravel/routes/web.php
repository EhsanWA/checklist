<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminPinController;

Route::view('/', 'home');

// Admin PIN login/logout
Route::get('/admin/login',  [AdminPinController::class, 'show'])->name('admin.login');
Route::post('/admin/login', [AdminPinController::class, 'verify'])->name('admin.login.verify');
Route::post('/admin/logout', [AdminPinController::class, 'logout'])->name('admin.logout');

// Beheer — achter PIN-middleware
Route::get('/reports/beheer', [ReportController::class, 'beheer'])
    ->middleware('admin.pin')
    ->name('reports.beheer');

// (optioneel) oude pad redirecten
Route::redirect('/beheer', '/reports/beheer');

// Publiek overzicht (index) via controller
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// Resource routes voor CRUD (index staat hierboven al)
Route::resource('reports', ReportController::class)->except(['index']);
