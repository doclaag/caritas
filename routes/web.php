<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FileController;
use App\Http\Controllers\TagController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::get('/public-files', [FileController::class, 'listPublicFiles'])->name('public.files');
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

// RUTA ARCHIVOS
Route::get('/files/list', [FileController::class, 'list'])->name('list');
Route::get('/files/upload', function () {
    return Inertia::render('Files/Upload');
})->name('files');

// Ruta para subir archivos
Route::middleware(['auth'])->group(function () {
    Route::post('/files/upload', [FileController::class, 'upload']);
});

// Ruta para obtener las categorías.
Route::middleware(['auth'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'getCategorias'])->name('categories');
    Route::get('/categories/{categoriaPadreId}/subcategories', [CategoryController::class, 'getSubcategorias']);
    Route::get('/tags', [TagController::class, 'index']);
});

// Ruta para crear, actualizar y eliminar categorías.
Route::middleware(['auth'])->group(function () {
    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags', [TagController::class, 'store']);
    Route::put('/tags/{tag}', [TagController::class, 'update']);
    Route::delete('/tags/{tag}', [TagController::class, 'destroy']);
    Route::put('/tags/{tag}/estado', [TagController::class, 'cambiarEstado']);
});
