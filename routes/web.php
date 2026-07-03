<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/properties', function () {
    return view('properties');
})->name('properties');

Route::get('/property-detail', function () {
    return view('property-detail');
})->name('property-detail');
