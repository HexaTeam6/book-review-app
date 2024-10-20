<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::get('/', function () {
    return view('welcome');
});

// Guest routes (only accessible when not authenticated)
Route::group(['middleware' => 'guest'], function() {
    Route::get('account/register', [AccountController::class, 'register'])->name('account.register');
    Route::post('account/register', [AccountController::class, 'processRegister'])->name('account.processRegister');
    
    Route::get('account/login', [AccountController::class, 'login'])->name('account.login');
    Route::post('account/login', [AccountController::class, 'authenticate'])->name('account.authenticate');
});

// Authenticated user routes (only accessible when logged in)
Route::group(['middleware' => 'auth'], function() {
    Route::get('account/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::post('account/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
    
    Route::get('account/logout', [AccountController::class, 'logout'])->name('account.logout');
});

// Default fallback route for unauthorized access (if needed)
Route::fallback(function() {
    return redirect()->route('account.login');
});
