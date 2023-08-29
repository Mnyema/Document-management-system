<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/upload', [HomeController::class, 'upload']);

Route::get('/', [HomeController::class, 'index']);

Route::post('/upload_doc',[HomeController::class, 'store']);

Route::get('/showDoc/{id}', [DocumentController::class, 'showDoc']);

Route::get('/document/show/{id}', [DocumentController::class, 'showDoc'])->name('document.show');


Route::get('/download/{id}', [DocumentController::class, 'download']);

Route::get('/download/{id}/{timestamp}', [DocumentController::class, 'download'])->name('document.download');

Route::get('/download/{id}/{hash}', [DocumentController::class, 'download'])->name('document.download');



Route::get('/delete_file/{id}', [HomeController::class, 'delete_file']);


Route::get('/welcome', function () {
    return view('welcome');
})->middleware(['auth', 'verified'])->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
