<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/meetrapport', function () {
    return view('meetrapport');
});

Route::get('/tabblad', function () {
    return view('tabblad');
});