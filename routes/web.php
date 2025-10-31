<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\BookmarkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// API routes for AJAX requests
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/weather', [WeatherController::class, 'fetchWeather'])->name('weather.fetch');
    Route::get('/currency', [CurrencyController::class, 'fetchRates'])->name('currency.fetch');
    Route::get('/news', [NewsController::class, 'fetchHeadlines'])->name('news.fetch');

    // User-specific data
    Route::apiResource('todos', TodoController::class);
    Route::apiResource('calendar-events', CalendarEventController::class);
    Route::apiResource('bookmarks', BookmarkController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
