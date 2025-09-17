<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/', fn() => redirect()->route('reports.index'));
Route::resource('reports', ReportController::class)->only(['index','create','store','show']);


