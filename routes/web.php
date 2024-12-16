<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('account/register', [AccountController::class, 'register'])->name('account.register');
Route::post('account/register', [AccountController::class, 'processRegister'])->name('account.processRegister');

Route::post('account/login', [AccountController::class, 'authenticate'])->name('account.authenticate');

//dicopy semua?
Route::group(['middleware' => 'auth'], function() {
	Route::get('profile', [AccountController::class, 'profile'])->name('account.profile');
	Route::post('update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
	Route::get('logout', [AccountController::class, 'logout'])->name('account.logout');
});