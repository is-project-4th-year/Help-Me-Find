<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\FinderReportController;
use App\Http\Controllers\WelcomeController;

use App\Http\Controllers\LostItemController;

use App\Http\Controllers\ChatController;
// use App\Http\Controllers\FoundController;

use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Broadcast;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::get('/welcome', [WelcomeController::class, 'welcome'])->name('welcome');


Route::middleware(['auth'])->group(function () {

    Route::get('/', [HomeController::class, 'viewPage'])->name('home');
    Route::get('/home', [HomeController::class, 'viewPage'])->name('home');

    Route::match(['get', 'post'], '/found', [HomeController::class, 'found'])->name('found');

    Route::get('/lostItems', [LostItemController::class, 'lostItems'])->name('lostItems');
    Route::get('/rag-search', [LostItemController::class, 'ragSearch'])->name('ragSearch');
    Route::get('/item/{id}', [LostItemController::class, 'itemDetail'])->name('itemDetail');

    Route::get('/item/{id}/map', [LostItemController::class, 'showItemMap'])->name('item.map');

    // ** UPDATED REPORT ROUTES **
    Route::get('/report/{token}', [FinderReportController::class, 'showReportForm'])->name('finder.report');
    Route::post('/report/{token}', [FinderReportController::class, 'submitReport'])->name('finder.submit');

    Route::get('/chat/item/{item_id}', [ChatController::class, 'showChat'])->name('chat.show');
    Route::get('/chat/{chat}/messages', [ChatController::class, 'getMessages'])->name('chat.messages.get');
    Route::post('/chat/{chat}/send', [ChatController::class, 'sendMessage'])->name('chat.messages.send');

    Broadcast::routes(['middleware' => ['auth']]);

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'withUser'])->name('chat.with');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

});
