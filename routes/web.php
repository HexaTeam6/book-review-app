<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Home routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/book/{id}', [HomeController::class, 'detail'])->name('book.detail');
Route::post('/save-book-review', [HomeController::class, 'saveReview'])->name('book.saveReview');

// Account routes
Route::group(['prefix' => 'account'], function () {
    // Guest-only routes
    Route::group(['middleware' => 'guest'], function () {
        Route::get('register', [AccountController::class, 'register'])->name('account.register');
        Route::post('register', [AccountController::class, 'processRegister'])->name('account.processRegister');
        Route::get('login', [AccountController::class, 'login'])->name('account.login');
        Route::post('login', [AccountController::class, 'authenticate'])->name('account.authenticate');
    });

    // Authenticated-only routes
    Route::group(['middleware' => 'auth'], function () {
        Route::get('profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::post('update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::get('logout', [AccountController::class, 'logout'])->name('account.logout');

        // Book management routes
        Route::get('books', [BookController::class, 'index'])->name('books.index');
        Route::get('books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('books', [BookController::class, 'store'])->name('books.store');
        Route::get('books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::post('books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

        // Review management routes
        Route::get('reviews', [ReviewController::class, 'index'])->name('account.reviews');
        Route::get('reviews/{id}', [ReviewController::class, 'edit'])->name('account.reviews.edit');
        Route::post('reviews/{id}', [ReviewController::class, 'updateReview'])->name('account.reviews.updateReview');
        Route::post('delete-reviews', [ReviewController::class, 'deleteReview'])->name('account.reviews.deleteReview');
    });
});
