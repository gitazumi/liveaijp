<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Models\GoogleCalendar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleCloudController;

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

Route::get('/', function () {
    return view('index');
});
Route::get('/', [FaqController::class, 'faqView']);

Route::get('privacy-policy', function(){
    return view('company_description.privacy');
});
Route::get('policy', function(){
    return view('company_description.policy');
});
Route::get('company', function(){
    return view('company_description.company');
});

Route::get('contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('contact', [ContactController::class, 'send'])->name('contact.send');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'role:user'])->group(function () {
   
    Route::get('/welcome', function () {
        $calendar = GoogleCalendar::where('user_id', Auth::id())->first() ?? ''; 
        
        $user = Auth::user();
        $isExistingAccount = $user->created_at <= '2025-04-16 23:59:59';
        
        $faqCount = \App\Models\Faq::where('user_id', $user->id)->count();
        
        if ($user->faq_limit === null) {
            $faqLimit = '無制限';
        } else {
            $faqLimit = $user->faq_limit;
        }
        
        if ($user->api_request_limit === null) {
            $chatLimit = '無制限';
        } else {
            $chatLimit = $user->api_request_limit;
        }
        
        $chatCount = 0;
        if ($chatLimit !== '無制限') {
            $today = date('Y-m-d');
            $requestCount = \App\Models\ChatRequestCount::where('user_id', $user->id)
                ->where('date', $today)
                ->first();
            
            if ($requestCount) {
                $chatCount = $requestCount->count;
            }
        }
        
        $usageInfo = [
            'faqCount' => $faqCount,
            'faqLimit' => $faqLimit,
            'chatCount' => $chatCount,
            'chatLimit' => $chatLimit,
            'isExistingAccount' => $isExistingAccount
        ];
        
        return view('welcome', compact('calendar', 'usageInfo'));
    })->middleware(['auth', 'verified'])->name('welcome');
    
    Route::get('/dashboard', function () {
        $calendar = GoogleCalendar::where('user_id', Auth::id())->first() ?? ''; 
        return view('dashboard', compact('calendar'));
    })->middleware(['auth', 'verified'])->name('dashboard');
    Route::get('information/create', [InformationController::class, 'create'])->name('information.create');
    Route::put('information', [InformationController::class, 'update'])->name('information.update');
    Route::get('faq', [FaqController::class, 'index'])->name('faq.index');
    Route::post('faq', [FaqController::class, 'store'])->name('faq.store');
    Route::put('faq/{id}', [FaqController::class, 'update'])->name('faq.update');
    Route::delete('faq/{id}', [FaqController::class, 'destroy'])->name('faq.destroy');
    Route::get('chat-history', [ChatController::class, 'history'])->name('chat.history');
    Route::get('chat-history/{chatId}', [ChatController::class, 'chat'])->name('chat.chat');
    Route::get('chat-bot', [ChatController::class, 'chatBot'])->name('chat.bot');
    Route::post('chat/message', [ChatController::class, 'message'])->name('chat.message');
    Route::get('chat/generate-snippet', [ChatController::class, 'generateSnippet'])->name('chat.generate-snippet');
    // Route::get('google-calendar', [GoogleCalendarController::class, 'index'])->name('google-calendar.index');
    // Route::put('google-calendar', [GoogleCalendarController::class, 'update'])->name('google-calendar.update');
    // Route::get('google-calendar/callback', [GoogleCalendarController::class, 'handleGoogleCallback'])->name('google-calendar.callback');
    // Route::get('google-calendar/events', [GoogleCalendarController::class, 'getEvents'])->name('google-calendar.events');
    
    
    Route::get('google-calendar', [GoogleCloudController::class, 'index'])->name('google-calendar.index');
    Route::get('/google/redirect', [GoogleCloudController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/oauth2/callback', [GoogleCloudController::class, 'handleGoogleCallback'])->name('google.callback');
    Route::get('/google/calendar', [GoogleCloudController::class, 'listGoogleCalendarEvents'])->name('google-calendar.events');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('users/auto-login/{id}', [UserController::class, 'autoLogin'])->name('users.auto-login');
    Route::get('users/{id}/manage', [UserController::class, 'manage'])->name('users.manage');
    Route::put('users/{id}/manage', [UserController::class, 'updateManage'])->name('users.update-manage');
});

Route::get('return-to-admin', [UserController::class, 'returnToAdmin'])->name('return-to-admin');


require __DIR__ . '/auth.php';
