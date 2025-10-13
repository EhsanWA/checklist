<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::view('/', 'home');

// Beheer (geeft $counts mee via controller)
Route::get('/reports/beheer', [ReportController::class, 'beheer'])->name('reports.beheer');

// (optioneel) oude pad laten redirecten
Route::redirect('/beheer', '/reports/beheer');

// Publiek overzicht via controller
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// Resource routes voor CRUD (index staat hierboven)
Route::resource('reports', ReportController::class)->except(['index']);
