<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Middleware\ApiKeyAuth;

Route::middleware(ApiKeyAuth::class)->group(function () {
    // Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
});

Route::get('/files/{unique_id}', [FileController::class, 'show'])->name('files.show');
Route::post('/files/{unique_id}/delete', [FileController::class, 'delete'])->name('files.delete');
Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');