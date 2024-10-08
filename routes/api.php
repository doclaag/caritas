<?php

use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/roles', [RoleController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('tags', TagController::class);
});