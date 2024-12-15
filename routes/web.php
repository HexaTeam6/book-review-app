<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/book/{id}', [HomeController::class, 'detail'])->name('book.detail');
Route::post('/save-book-review', [HomeController::class, 'saveReview'])->name('book.saveReview');

Route::group(['prefix' => 'account'], function() {
    Route::group(['middleware' => 'guest'], function() {
        Route::get('register', [AccountController::class, 'register'])->name('account.register');
        Route::post('register', [AccountController::class, 'processRegister'])->name('account.processRegister');
        Route::get('login', [AccountController::class, 'login'])->name('account.login');
        Route::post('login', [AccountController::class, 'authenticate'])->name('account.authenticate');
    });

    Route::group(['middleware' => 'auth'], function() {
        Route::get('profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::post('update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::get('logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::get('books', [BookController::class, 'index'])->name('books.index');
        Route::get('books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('books', [BookController::class, 'store'])->name('books.store');
        Route::get('books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::delete('books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
        Route::get('reviews', [AccountController::class, 'myReviews'])->name('account.myReviews');
    });
});
