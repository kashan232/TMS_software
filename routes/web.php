<?php

use App\Http\Controllers\ClothTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MeasurementPartController;
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

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');
// Route::get('/adminpage', [HomeController::class, 'adminpage'])->middleware(['auth','admin'])->name('adminpage');


//cloth-type
Route::get('/cloth-type', [ClothTypeController::class, 'cloth_type'])->middleware(['auth','admin'])->name('cloth-type');
Route::post('/store-cloth-type', [ClothTypeController::class, 'store_cloth_type'])->name('store-cloth-type');
Route::post('/cloth-type/update', [ClothTypeController::class, 'update'])->name('cloth_type.update');

Route::get('/create-measurement-parts', [MeasurementPartController::class, 'create_measurement_parts'])->middleware(['auth','admin'])->name('create-measurement-parts');





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
