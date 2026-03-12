<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GoalSettingController;
use App\Http\Controllers\WeightLogController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// 修正: ルートURLをログインへ
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');


// 会員登録
Route::get('/register/step1', [RegisterController::class, 'createStep1'])->name('register.step1');
Route::post('/register/step1', [RegisterController::class, 'storeStep1'])->name('register.step1.store');
Route::get('/register/step2', [RegisterController::class, 'createStep2'])->name('register.step2');
Route::post('/register/step2', [RegisterController::class, 'storeStep2'])->name('register.step2.store');

// Fortify の /login, /logout は Fortify 側で持つ想定
Route::middleware(['auth', 'preventBackHistory'])->group(function () {
    Route::get('/weight_logs', [WeightLogController::class, 'index'])->name('weight_logs.index');
    Route::get('/weight_logs/search', [WeightLogController::class, 'search'])->name('weight_logs.search');
    Route::get('/weight_logs/create', [WeightLogController::class, 'create'])->name('weight_logs.create');

    Route::get('/weight_logs/goal_setting', [GoalSettingController::class, 'edit'])->name('goal_setting.edit');
    Route::put('/weight_logs/goal_setting', [GoalSettingController::class, 'update'])->name('goal_setting.update');

    Route::post('/weight_logs', [WeightLogController::class, 'store'])->name('weight_logs.store');
    Route::get('/weight_logs/{weightLog}/update', [WeightLogController::class, 'edit'])->name('weight_logs.edit');
    Route::put('/weight_logs/{weightLog}', [WeightLogController::class, 'update'])->name('weight_logs.update');
    Route::delete('/weight_logs/{weightLog}', [WeightLogController::class, 'destroy'])->name('weight_logs.destroy');
});