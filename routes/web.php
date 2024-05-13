<?php

use App\Http\Controllers\MetaController;
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

    Route::get('/upload/{upload:sid}/trim', [UploadController::class, 'editStepOne'])->name('upload.trim');
    Route::post('/upload/{upload:sid}/trim', [ProcessController::class, 'doTrim'])->name('process.trim');

    Route::get('/upload/{upload:sid}/versions', [UploadController::class, 'editStepTwo'])->name('upload.versions');
    Route::get('/upload/{upload:sid}/meta', [UploadController::class, 'editStepThree'])->name('upload.meta');
    Route::post('/upload/{upload:sid}/meta', [MetaController::class, 'store'])->name('meta.store');

    Route::get('/process/{process}', [ProcessController::class, 'show'])->name('process.show');
    Route::post('/process/{upload:sid}', [ProcessController::class, 'store'])->name('process.store');

    Route::post('/process/{process}/thumbnail', [ProcessController::class, 'thumbnailForTrim'])->name('process.thumbnail');


});



require __DIR__.'/auth.php';
