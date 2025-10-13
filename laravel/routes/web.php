<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;


Route::get('/', function () {
    return view('home');
});

// Route::get('/', fn() => redirect()->route('home'));
Route::resource('reports', ReportController::class); // nu ook edit, update, destroy


Route::get('/tabblad', function () {
    return view('tabblad');
});
