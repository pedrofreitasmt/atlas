<?php

use App\Http\Controllers\CityController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('auth/Login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('cities', CityController::class)->only('index', 'show');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
