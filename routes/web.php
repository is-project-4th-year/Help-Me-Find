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

    Route::get('/', [HomeController::class, 'viewPage'])->name('home'); // Form page
    Route::get('/home', [HomeController::class, 'viewPage'])->name('home'); // Form page

    Route::match(['get', 'post'], '/found', [HomeController::class, 'found'])->name('found');

    Route::get('/lostItems', [LostItemController::class, 'lostItems'])->name('lostItems');
    Route::get('/rag-search', [LostItemController::class, 'ragSearch'])->name('ragSearch');
    Route::get('/item/{id}', [LostItemController::class, 'itemDetail'])->name('itemDetail');


    Route::get('/report/{token}', [FinderReportController::class, 'showReportForm'])->name('finder.report');

    Route::get('/chat/item/{item_id}', [ChatController::class, 'showChat'])->name('chat.show');
    Route::get('/chat/{chat}/messages', [ChatController::class, 'getMessages'])->name('chat.messages.get');
    Route::post('/chat/{chat}/send', [ChatController::class, 'sendMessage'])->name('chat.messages.send');

    Broadcast::routes(['middleware' => ['auth']]);

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'withUser'])->name('chat.with');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

});



// // Authentication Routes
// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [LoginController::class, 'login']);

// // Point 'GET /register' to the login form, which now includes the register tab
// Route::get('/register', [LoginController::class, 'showLoginForm'])->name('register');
// // The POST route for registration remains the same, pointing to the RegisterController
// Route::post('/register', [RegisterController::class, 'register']);

// Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');


// // Routes protected by authentication
// Route::middleware(['auth'])->group(function () {
//     Route::get('/home', [HomeController::class, 'index'])->name('home');

//     // Lost Item Routes
//     Route::get('/lost-items', [LostItemController::class, 'index'])->name('lost-items');
//     Route::get('/lost-items/{id}', [LostItemController::class, 'show'])->name('item.detail');

//     // Finder Report Routes
//     Route::get('/report', [FinderReportController::class, 'create'])->name('report');
//     Route::post('/report', [FinderReportController::class, 'store'])->name('report.store');

//     // Chat Routes
//     Route::get('/chat', [ChatController::class, 'index'])->name('chat.list');
//     Route::get('/chat/{chatId}', [ChatController::class, 'show'])->name('chat.show');
//     Route::post('/chat/{chatId}/send', [ChatController::class, 'sendMessage'])->name('chat.send');

//     // Route to start a chat with a user (e.g., from an item page)
//     Route::get('/chat/with/{userId}', [ChatController::class, 'startChat'])->name('chat.start');
// });

// // Welcome/Landing Page
// Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
