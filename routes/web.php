<?php

use App\Http\Controllers\CsvUploadController;
use Illuminate\Support\Facades\Route;

Route::get('', [CsvUploadController::class, 'showUploadForm'])->name('home');

Route::post('/upload', [CsvUploadController::class, 'upload'])->name('upload');
