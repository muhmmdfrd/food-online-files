<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Middleware\ApiKeyAuth;

Route::get('/files/{unique_id}', [FileController::class, 'show'])->name('files.show');
Route::post('/files/{unique_id}/delete', [FileController::class, 'delete'])->name('files.delete');