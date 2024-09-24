<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FileUploadController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::get('/files/upload', function () {
    return Inertia::render('Files/Upload');
})->name('files.upload');


// Ruta para subir archivos
Route::middleware(['auth'])->group(function () {
    Route::post('/upload', [FileUploadController::class, 'upload']);
});

//Ruta para obtener las categorias.
Route::get('/get-categorias-principales', [CategoryController::class, 'getCategoriasPrincipales']);
Route::get('/get-categorias', [CategoryController::class, 'getCategorias']);