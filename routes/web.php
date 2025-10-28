<?php

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\FinderReportController;
use App\Http\Controllers\WelcomeController;

use App\Http\Controllers\LostItemController;

// use App\Http\Controllers\FoundController;

use App\Http\Controllers\HomeController;

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
});

