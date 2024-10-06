<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('account/register', [AccountController::class, 'register'])->name('account.register');
