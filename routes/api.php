<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\FortuneController;
use App\Http\Controllers\Api\V1\MoodController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\TarotController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// 認証不要のエンドポイント
Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login'])->name('api.v1.auth.login');
});

// 認証が必要なエンドポイント
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.v1.auth.logout');
    Route::get('/auth/me', [AuthController::class, 'me'])->name('api.v1.auth.me');
    
    // 今日の運勢
    Route::get('/fortunes/today', [FortuneController::class, 'today'])->name('api.v1.fortunes.today');
    
    // 感情ログ
    Route::get('/moods', [MoodController::class, 'index'])->name('api.v1.moods.index');
    Route::post('/moods', [MoodController::class, 'store'])->name('api.v1.moods.store');
    Route::get('/moods/date/{date}', [MoodController::class, 'show'])->name('api.v1.moods.show');
    Route::put('/moods/{mood}', [MoodController::class, 'update'])->name('api.v1.moods.update');
    Route::delete('/moods/{mood}', [MoodController::class, 'destroy'])->name('api.v1.moods.destroy');
    
    // ユーザープロフィール
    Route::get('/profile', [ProfileController::class, 'show'])->name('api.v1.profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('api.v1.profile.update');
    
    // タロット
    Route::post('/tarot/draw', [TarotController::class, 'draw'])->name('api.v1.tarot.draw');

    // チャット占い
    Route::get('/chat/categories', [App\Http\Controllers\Api\V1\FortuneChatController::class, 'categories'])->name('api.v1.chat.categories');
    Route::post('/chat/consult', [App\Http\Controllers\Api\V1\FortuneChatController::class, 'consult'])->name('api.v1.chat.consult');
});

