<?php

use App\Http\Controllers\ProcessController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/upload', [UploadController::class, 'create'])->name('upload');
    Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');

    Route::get('/process', [ProcessController::class, 'index'])->name('process.index');
    Route::get('/process/{process}/edit', [ProcessController::class, 'edit'])->name('process.edit');
    Route::get('/process/{process}', [ProcessController::class, 'show'])->name('process.show');
});



require __DIR__.'/auth.php';
