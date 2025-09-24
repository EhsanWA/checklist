<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/', fn() => redirect()->route('reports.index'));
Route::resource('reports', ReportController::class); // nu ook edit, update, destroy

Route::get('/meetrapport', function () {
    return view('meetrapport');
});

Route::get('/tabblad', function () {
    return view('tabblad');
});
