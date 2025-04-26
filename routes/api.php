<?php

use App\Http\Controllers\ChatController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/chat/message', [ChatController::class, 'message'])
    ->middleware(['throttle.custom:10,1'])
    ->withoutMiddleware(['auth', 'auth:sanctum']);

Route::get('/get-chat-history', [ChatController::class, 'getChatHistory'])->withoutMiddleware(['auth', 'auth:sanctum']);

Route::options('/chat/message', function (Request $request) {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Chatbot-Token');
});
