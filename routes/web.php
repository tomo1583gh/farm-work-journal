<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkController;

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

// ログイン済みユーザーだけがアクセス出来るページ群
Route::middleware(['auth'])->group(function () {
    // トップページ（ルートURL)にアクセスしたら作業一覧へ
    Route::get('/', [WorkController::class,'index'])->name('home');

    Route::resource('works', WorkController::class);
});
