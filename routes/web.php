<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\RoleController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

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

    Route::get('/register', [RoleController::class, 'index'])->name('register');

    Route::get('/files/upload', function () {
    return Inertia::render('Files/Upload');
    })->name('files.upload');

    Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload');

    Route::get('{any}', function () {
        return redirect()->route('dashboard');
    })->where('any', '.*');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return Inertia::render('Auth/Login');
    })->name('login');
});

Route::get('{any}', function () {
    return redirect()->route('login');
})->where('any', '.*');
