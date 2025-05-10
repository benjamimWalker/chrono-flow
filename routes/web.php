<?php

use App\Http\Controllers\CsvUploadController;
use App\Http\Controllers\ListWorkLogsController;
use Illuminate\Support\Facades\Route;

Route::get('', [CsvUploadController::class, 'showUploadForm'])->name('home');
Route::post('/upload', [CsvUploadController::class, 'upload'])->name('upload');
Route::get('work-logs', ListWorkLogsController::class)->name('work-logs');
