<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MetricsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [MetricsController::class, 'index'])->name('metrics');
Route::get('/fetch-metrics', [MetricsController::class, 'fetchMetrics'])->name('fetch-metrics');
Route::post('/save-metric-run', [MetricsController::class, 'saveMetricRun'])->name('save-metric-run');
Route::get('/history', [MetricsController::class, 'showHistory'])->name('metric-history');
