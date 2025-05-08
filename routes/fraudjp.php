<?php

use App\Http\Controllers\FraudJp\ChatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| FraudJP Routes
|--------------------------------------------------------------------------
|
| Routes for the fraud.jp chat AI feature
|
*/

Route::get('/', [ChatController::class, 'index'])->name('fraudjp.index');

Route::prefix('api/fraudjp')->group(function () {
    Route::post('/message', [ChatController::class, 'message']);
    Route::get('/history', [ChatController::class, 'getHistory']);
});
